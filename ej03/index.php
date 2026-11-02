<?php
declare(strict_types=1);
session_start();
date_default_timezone_set('America/Guayaquil');
header('Content-Type: text/html; charset=UTF-8');

// Requisito del ejercicio: variable global con el total de visitas.
$visitas = 0;
$visitasHoy = null;
$historial = null;
$inicioHistorial = null;
$errorContador = '';
$errorHistorial = '';

/**
 * Registra una carga de página. El archivo visitas.txt conserva el total global.
 * El bloqueo exclusivo impide que dos peticiones modifiquen el contador a la vez.
 * El historial diario se escribe dentro del mismo bloqueo del contador.
 */
function registrarVisita(string $rutaContador, string $rutaHistorial): bool
{
    global $visitas, $visitasHoy, $historial, $inicioHistorial, $errorHistorial;
    $archivo = @fopen($rutaContador, 'c+');
    if ($archivo === false) return false;
    if (!flock($archivo, LOCK_EX)) { fclose($archivo); return false; }
    try {
        rewind($archivo);
        $contenido = stream_get_contents($archivo);
        if ($contenido === false) return false;
        $contenido = trim($contenido);
        if ($contenido !== '' && !ctype_digit($contenido)) return false;
        $actual = $contenido === '' ? 0 : (int) $contenido;
        if ($actual >= PHP_INT_MAX) return false;
        $nuevo = $actual + 1;
        rewind($archivo);
        if (!ftruncate($archivo, 0)) return false;
        $texto = (string) $nuevo;
        $escritos = fwrite($archivo, $texto);
        if ($escritos !== strlen($texto) || !fflush($archivo)) return false;
        $visitas = $nuevo;

        // Estadísticas reales desde que se instala esta versión (no se inventan visitas anteriores).
        $hoy = date('Y-m-d');
        $datos = ['inicio' => $hoy, 'dias' => []];
        if (is_file($rutaHistorial)) {
            $json = @file_get_contents($rutaHistorial);
            $leido = is_string($json) ? json_decode($json, true) : null;
            if (!is_array($leido) || !isset($leido['inicio'], $leido['dias']) || !is_string($leido['inicio']) || !is_array($leido['dias'])) {
                $errorHistorial = 'El historial diario no pudo leerse. El contador global sigue funcionando.';
                return true;
            }
            $datos = $leido;
        }
        $datos['dias'][$hoy] = max(0, (int) ($datos['dias'][$hoy] ?? 0)) + 1;
        $limite = date('Y-m-d', strtotime('-59 days'));
        $datos['dias'] = array_filter($datos['dias'], static function ($v, $fecha) use ($limite) {
            return is_string($fecha) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha) && $fecha >= $limite && is_numeric($v) && (int) $v >= 0;
        }, ARRAY_FILTER_USE_BOTH);
        $salida = json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        if ($salida === false || @file_put_contents($rutaHistorial, $salida, LOCK_EX) === false) {
            $errorHistorial = 'No se pudo actualizar el historial diario. Revisa los permisos de escritura de la carpeta ej03.';
            return true;
        }
        $historial = $datos['dias'];
        $inicioHistorial = $datos['inicio'];
        $visitasHoy = (int) $datos['dias'][$hoy];
        return true;
    } finally {
        flock($archivo, LOCK_UN);
        fclose($archivo);
    }
}

$registroCorrecto = registrarVisita(__DIR__ . '/visitas.txt', __DIR__ . '/historial.json');
if (!$registroCorrecto) {
    $errorContador = 'No se pudo actualizar el contador. Comprueba que visitas.txt tenga permisos de escritura.';
}
if ($registroCorrecto) {
    $_SESSION['ej03_visitas_sesion'] = (int) ($_SESSION['ej03_visitas_sesion'] ?? 0) + 1;
}
$visitasSesion = (int) ($_SESSION['ej03_visitas_sesion'] ?? 0);

$semanas = [];
$nombreDia = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];
for ($i = 13; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $conocido = is_array($historial) && is_string($inicioHistorial) && $fecha >= $inicioHistorial;
    $cantidad = $conocido ? max(0, (int) ($historial[$fecha] ?? 0)) : null;
    $semanas[] = ['fecha' => $fecha, 'dia' => $nombreDia[(int) date('N', strtotime($fecha))], 'numero' => date('d/m', strtotime($fecha)), 'cantidad' => $cantidad];
}
$maximo = max(1, ...array_map(static fn($fila) => (int) ($fila['cantidad'] ?? 0), $semanas));
function formato(int $n): string { return number_format($n, 0, ',', '.'); }
function escapar(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ejercicio 03: contador de visitas global en PHP con estadísticas diarias reales.">
    <title>03 · Contador de visitas | Team Evorion</title>
    <link rel="stylesheet" href="estilos.css?v=3"><script src="script.js?v=3" defer></script>
</head>
<body>
<div class="luces" aria-hidden="true"><i></i><i></i></div>
<div class="envoltura">
    <header class="cabecera"><a class="logo" href="../index.php"><span class="logo-marca">E</span><span>EVORION<span class="logo-punto">.</span><small>WEB LABORATORY</small></span></a><div class="cabecera-acciones"><span class="estado-online"><span></span> PHP EN EJECUCIÓN</span><button id="tema" type="button" class="tema" aria-label="Cambiar tema">☼</button><a href="../index.php" class="volver">← <span>Menú principal</span></a></div></header>
    <main>
        <div class="intro"><div class="ruta">PROGRAMACIÓN WEB AVANZADA <span>/</span> EJERCICIO 03</div><h1>Cada visita deja<br><em>su huella digital.</em></h1><p>Un pequeño laboratorio de analítica real: PHP registra las visitas globales, conserva el total y construye un historial diario desde la instalación de esta versión.</p></div>
        <?php if ($errorContador !== ''): ?><div class="alerta" role="alert"><?= escapar($errorContador) ?></div><?php endif; ?>
        <?php if ($errorHistorial !== ''): ?><div class="alerta" role="alert"><?= escapar($errorHistorial) ?></div><?php endif; ?>
        <div class="tablero">
            <section class="contador-panel" aria-labelledby="contador-titulo">
                <div class="panel-arriba"><span class="chip">◉ CONTADOR GLOBAL</span><span class="estado <?= $registroCorrecto ? '' : 'estado-error' ?>"><i></i><?= $registroCorrecto ? 'ACTIVO' : 'REVISAR' ?></span></div>
                <h2 id="contador-titulo">Visitas acumuladas</h2><p class="panel-ayuda">Todas las personas comparten este mismo contador.</p>
                <div class="circulo"><div class="orbita uno"></div><div class="orbita dos"></div><div class="orbita tres"></div><div class="centro"><span class="centro-icono">↗</span><strong class="total" data-total="<?= $visitas ?>" aria-label="<?= formato($visitas) ?> visitas"><?= formato($visitas) ?></strong><small>VISITAS TOTALES</small></div></div>
                <div class="contador-acciones"><a class="boton-primario" href="index.php" id="sumar"><span>↻ Sumar una visita</span><span>↗</span></a><button class="boton-secundario" type="button" id="copiar">▢ Copiar total</button></div>
                <p class="advertencia">Cada clic recarga la página y suma exactamente una visita al total compartido.</p>
                <div class="estado-archivo"><span>▣ Archivo persistente: <code>visitas.txt</code></span><span>● <?= $registroCorrecto ? 'GUARDADO' : 'ERROR' ?></span></div>
            </section>
            <section class="datos-panel" aria-labelledby="datos-titulo">
                <div class="panel-arriba"><span class="chip">✦ DATOS EN TIEMPO DE CARGA</span><span class="codigo">PHP / 03</span></div>
                <h2 id="datos-titulo">Tu panel de actividad</h2><p class="panel-ayuda">Cifras reales obtenidas al cargar esta página. No son visitantes únicos.</p>
                <div class="tarjetas"><article class="dato"><div class="dato-cabecera"><span>VISITAS DE HOY</span><span class="dato-icono">◷</span></div><strong><?= $visitasHoy === null ? '—' : formato($visitasHoy) ?></strong><small>Registradas desde que se activó el historial</small></article><article class="dato"><div class="dato-cabecera"><span>ESTA SESIÓN</span><span class="dato-icono">◎</span></div><strong><?= formato($visitasSesion) ?></strong><small>Cargas realizadas en tu sesión actual</small></article></div>
                <div class="grafico-panel"><div class="grafico-cabecera"><div><h3>Historial de actividad</h3><p>Visitas registradas por día</p></div><div class="rango" role="group" aria-label="Período del gráfico"><button type="button" class="activo" data-rango="7" aria-pressed="true">7 días</button><button type="button" data-rango="14" aria-pressed="false">14 días</button></div></div>
                    <div class="grafico" id="grafico" role="group" aria-label="Gráfico interactivo de visitas diarias">
                        <?php foreach ($semanas as $indice => $dia): ?>
                            <?php $alto = $dia['cantidad'] === null ? 0 : max(5, (int) round($dia['cantidad'] / $maximo * 100)); ?>
                            <button type="button" class="barra-dia <?= $indice < 7 ? 'fuera-rango' : '' ?> <?= $dia['cantidad'] === null ? 'sin-datos' : '' ?>" data-fecha="<?= escapar($dia['fecha']) ?>" data-cantidad="<?= $dia['cantidad'] === null ? '' : (int) $dia['cantidad'] ?>" data-etiqueta="<?= escapar($dia['dia'] . ' ' . $dia['numero']) ?>" aria-label="<?= escapar($dia['dia'] . ' ' . $dia['numero'] . ': ' . ($dia['cantidad'] === null ? 'sin datos anteriores' : formato($dia['cantidad']) . ' visitas')) ?>" title="<?= escapar($dia['numero'] . ': ' . ($dia['cantidad'] === null ? 'sin datos' : formato($dia['cantidad']) . ' visitas')) ?>"><span class="barra-altura"><i style="--alto:<?= $alto ?>%"></i></span><span class="barra-etiqueta"><?= escapar($dia['dia']) ?></span></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="grafico-detalle" id="grafico-detalle" aria-live="polite">Selecciona una barra para ver las visitas de ese día.</div>
                    <p class="nota-historial">El historial comienza cuando instalas esta versión. Los días anteriores aparecen sin datos; el total global se conserva.</p>
                </div>
                <div class="tiempo"><span><i></i> DATOS ACTUALIZADOS AL CARGAR LA PÁGINA</span><span id="reloj" aria-label="Hora actual del dispositivo">--:--:--</span></div>
            </section>
        </div>
        <section class="explicacion" aria-labelledby="como-funciona"><div class="explicacion-encabezado"><div><span class="kicker">DETRÁS DEL CONTADOR</span><h2 id="como-funciona">¿Qué sucede cuando entras?</h2></div><p>Explora cada etapa del proceso. El contador no depende de JavaScript para guardar los datos.</p></div><div class="pasos"><details open><summary><span class="paso-n">01</span><span>PHP lee el archivo</span><b>＋</b></summary><p>Abre <code>visitas.txt</code>, bloquea el archivo para evitar escrituras simultáneas y obtiene el valor anterior.</p></details><details><summary><span class="paso-n">02</span><span>Aumenta el contador</span><b>＋</b></summary><p>La variable global <code>$visitas</code> suma una unidad y PHP guarda el nuevo total. También registra la fecha en <code>historial.json</code>.</p></details><details><summary><span class="paso-n">03</span><span>Muestra las estadísticas</span><b>＋</b></summary><p>PHP genera la página con los valores reales. JavaScript solo anima los números, permite consultar el gráfico y copiar el total.</p></details></div></section>
        <p class="aclaracion">Importante: las visitas son cargas de página, no personas diferentes. El contador no se reinicia al cerrar el navegador; depende de que el hosting conserve los archivos y permita escribir en ellos.</p>
    </main>
    <footer><span>© <?= date('Y') ?> TEAM EVORION</span><span>UNIVERSIDAD BOLIVARIANA DEL ECUADOR · PROGRAMACIÓN WEB AVANZADA</span></footer>
</div>
<div id="toast" class="toast" role="status" aria-live="polite"></div>
</body>
</html>
