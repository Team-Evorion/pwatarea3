<?php
declare(strict_types=1);
session_start();
header('Content-Type: text/html; charset=UTF-8');

// Ejercicio académico: valida y simula el procesamiento; NO envía correos ni guarda mensajes.
if (empty($_SESSION['contacto_csrf'])) {
    $_SESSION['contacto_csrf'] = bin2hex(random_bytes(32));
}

$valores = ['nombre' => '', 'correo' => '', 'mensaje' => '', 'tipo' => 'Consulta'];
$tiposPermitidos = ['Consulta', 'Sugerencia', 'Colaboración'];
$errores = [];
$confirmacion = !empty($_SESSION['contacto_enviado']);
unset($_SESSION['contacto_enviado']);

function e(string $valor): string
{
    return htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function caracteres(string $valor): int
{
    return function_exists('mb_strlen') ? mb_strlen($valor, 'UTF-8') : strlen($valor);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmacion = false;
    $token = $_POST['csrf_token'] ?? null;
    if (!is_string($token) || !hash_equals($_SESSION['contacto_csrf'], $token)) {
        $errores['general'] = 'La sesión de seguridad caducó. Recarga la página y vuelve a intentarlo.';
    } else {
        foreach (array_keys($valores) as $campo) {
            $entrada = $_POST[$campo] ?? '';
            $valores[$campo] = is_string($entrada) ? trim($entrada) : '';
        }
        if ($valores['nombre'] === '') {
            $errores['nombre'] = 'Escribe tu nombre.';
        } elseif (caracteres($valores['nombre']) < 2 || caracteres($valores['nombre']) > 80) {
            $errores['nombre'] = 'El nombre debe tener entre 2 y 80 caracteres.';
        }
        if ($valores['correo'] === '') {
            $errores['correo'] = 'Escribe tu correo electrónico.';
        } elseif (caracteres($valores['correo']) > 254 || !filter_var($valores['correo'], FILTER_VALIDATE_EMAIL)) {
            $errores['correo'] = 'Introduce un correo electrónico válido.';
        }
        if ($valores['mensaje'] === '') {
            $errores['mensaje'] = 'Escribe un mensaje.';
        } elseif (caracteres($valores['mensaje']) < 10 || caracteres($valores['mensaje']) > 1000) {
            $errores['mensaje'] = 'El mensaje debe tener entre 10 y 1000 caracteres.';
        }
        if (!in_array($valores['tipo'], $tiposPermitidos, true)) {
            $errores['tipo'] = 'Selecciona un motivo válido.';
            $valores['tipo'] = 'Consulta';
        }
        if ($errores === []) {
            $_SESSION['contacto_enviado'] = true;
            $_SESSION['contacto_csrf'] = bin2hex(random_bytes(32));
            header('Location: index.php?procesado=1', true, 303);
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ejercicio 02: formulario de contacto interactivo con validación JavaScript y PHP.">
    <title>02 · Contacto interactivo | Team Evorion</title>
    <link rel="stylesheet" href="estilos.css?v=3">
    <script src="script.js?v=3" defer></script>
</head>
<body>
<div class="luces" aria-hidden="true"><i></i><i></i><i></i></div>
<div class="envoltura">
    <header class="cabecera">
        <a class="logo" href="../index.php" aria-label="Ir al menú principal"><span class="logo-marca">E</span><span>EVORION<span class="logo-punto">.</span><small>WEB LABORATORY</small></span></a>
        <div class="cabecera-acciones"><span class="estado-online"><span></span> DEMO INTERACTIVA</span><button type="button" id="tema" class="tema" aria-label="Cambiar tema" title="Cambiar tema">☼</button><a class="volver" href="../index.php">← <span>Menú principal</span></a></div>
    </header>

    <main>
        <div class="intro"><div class="ruta">PROGRAMACIÓN WEB AVANZADA <span>/</span> EJERCICIO 02</div><h1>Un mensaje.<br><em>Muchas posibilidades.</em></h1><p>Escribe, experimenta y observa cómo tu mensaje cobra vida. Validación en tiempo real, vista previa y procesamiento seguro con PHP.</p></div>
        <div class="rejilla">
            <section class="zona-preview" aria-labelledby="titulo-preview">
                <div class="preview-top"><span class="chip"><span class="chip-luz"></span> VISTA PREVIA EN VIVO</span><span class="mini-codigo">&lt; / CONTACTO &gt;</span></div>
                <h2 id="titulo-preview">Así se verá<br>tu mensaje <span>↗</span></h2>
                <p class="preview-ayuda">Esta tarjeta se actualiza mientras escribes. Nada se publica ni se envía realmente.</p>
                <div class="tarjeta-mensaje" id="tarjeta-mensaje">
                    <div class="tarjeta-mensaje-arriba"><div class="avatar" id="avatar">?</div><div class="identidad"><strong id="preview-nombre">Tu nombre aparecerá aquí</strong><span id="preview-correo">tu-correo@ejemplo.com</span></div><span class="mensaje-icono">✉</span></div>
                    <div class="etiqueta-motivo" id="preview-tipo">● Consulta</div>
                    <div class="texto-mensaje" id="preview-mensaje">Tu mensaje se mostrará aquí en tiempo real. ¡Prueba a escribir algo en el formulario!</div>
                    <div class="tarjeta-mensaje-pie"><span><span class="punto-vivo"></span> BORRADOR LOCAL</span><span id="preview-palabras">0 PALABRAS</span></div>
                </div>
                <div class="indicadores"><div><span class="indicador-ico">⌁</span><strong id="campos-completos">0 / 3</strong><small>Campos válidos</small></div><div><span class="indicador-ico">✦</span><strong id="porcentaje">0%</strong><small>Completado</small></div><div><span class="indicador-ico">⌘</span><strong>PHP + JS</strong><small>Doble validación</small></div></div>
                <div class="terminal"><div class="terminal-top"><span><i></i><i></i><i></i></span> CONSOLA DE VALIDACIÓN <span class="terminal-en-vivo">● LIVE</span></div><p id="consola-texto" aria-live="polite"><span>›</span> Esperando datos del formulario...</p></div>
            </section>

            <section class="panel-formulario" aria-labelledby="titulo-formulario">
                <div class="panel-cabecera"><span class="panel-icono">✳</span><span class="etiqueta-panel">FORM / 02 — 06</span></div>
                <h2 id="titulo-formulario">Cuéntanos tu idea</h2><p class="panel-subtitulo">Completa los campos y mira cómo se transforma tu mensaje.</p>
                <div class="pasos" aria-label="Progreso del formulario"><div class="pasos-titulos"><span>01 · IDENTIDAD</span><span>02 · MENSAJE</span><span>03 · LISTO</span></div><div class="barra-progreso" id="barra-progreso" role="progressbar" aria-valuemin="0" aria-valuemax="3" aria-valuenow="0" aria-label="Campos correctos"><span id="relleno-progreso"></span></div><div class="progreso-leyenda" id="progreso-leyenda" aria-live="polite">0 de 3 campos completos</div></div>
                <?php if ($confirmacion): ?>
                    <div class="alerta exito" id="alerta-exito" role="status" tabindex="-1"><span class="alerta-icono">✓</span><div><strong>¡Mensaje procesado con éxito!</strong><p>La demostración funcionó. No se envió ningún correo ni se guardaron tus datos.</p></div></div>
                <?php endif; ?>
                <?php if (isset($errores['general'])): ?><div class="alerta error-general" role="alert"><?= e($errores['general']) ?></div><?php endif; ?>
                <form id="contacto" method="post" action="index.php" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= e($_SESSION['contacto_csrf']) ?>">
                    <fieldset class="motivos"><legend>¿Sobre qué quieres hablar?</legend><div class="motivos-fila" id="motivos-fila">
                        <?php foreach ($tiposPermitidos as $tipo): ?><label class="motivo"><input type="radio" name="tipo" value="<?= e($tipo) ?>" <?= $valores['tipo'] === $tipo ? 'checked' : '' ?>><span><?= $tipo === 'Consulta' ? '◉' : ($tipo === 'Sugerencia' ? '✦' : '⌁') ?> <?= e($tipo) ?></span></label><?php endforeach; ?>
                    </div><?php if (isset($errores['tipo'])): ?><small class="error-campo" role="alert"><?= e($errores['tipo']) ?></small><?php endif; ?></fieldset>
                    <div class="fila-campos">
                        <div class="campo"><label for="nombre">Nombre completo <span>*</span></label><div class="entrada"><span class="entrada-icono">♙</span><input id="nombre" name="nombre" type="text" placeholder="Ej. Ana Martínez" autocomplete="name" maxlength="80" required value="<?= e($valores['nombre']) ?>" aria-describedby="error-nombre" aria-invalid="<?= isset($errores['nombre']) ? 'true' : 'false' ?>"><span class="marca-valida" aria-hidden="true">✓</span></div><small class="error-campo" id="error-nombre" aria-live="polite"><?= e($errores['nombre'] ?? '') ?></small></div>
                        <div class="campo"><label for="correo">Correo electrónico <span>*</span></label><div class="entrada"><span class="entrada-icono">@</span><input id="correo" name="correo" type="email" placeholder="nombre@correo.com" autocomplete="email" maxlength="254" required value="<?= e($valores['correo']) ?>" aria-describedby="error-correo" aria-invalid="<?= isset($errores['correo']) ? 'true' : 'false' ?>"><span class="marca-valida" aria-hidden="true">✓</span></div><small class="error-campo" id="error-correo" aria-live="polite"><?= e($errores['correo'] ?? '') ?></small></div>
                    </div>
                    <div class="campo campo-mensaje"><div class="label-fila"><label for="mensaje">Tu mensaje <span>*</span></label><span class="caracteres" id="caracteres">0 / 1000</span></div><textarea id="mensaje" name="mensaje" placeholder="Escribe aquí lo que tienes en mente..." minlength="10" maxlength="1000" rows="5" required aria-describedby="error-mensaje" aria-invalid="<?= isset($errores['mensaje']) ? 'true' : 'false' ?>"><?= e($valores['mensaje']) ?></textarea><div class="mensaje-metrica"><small class="error-campo" id="error-mensaje" aria-live="polite"><?= e($errores['mensaje'] ?? '') ?></small><span id="palabras">0 palabras</span></div></div>
                    <div class="ideas"><div class="ideas-cabecera"><strong>¿No sabes qué escribir?</strong><span>Prueba una idea rápida ↓</span></div><div class="ideas-botones"><button type="button" class="idea" data-idea="consulta">✧ Solicitar información</button><button type="button" class="idea" data-idea="sugerencia">✧ Compartir una sugerencia</button><button type="button" class="idea" data-idea="colaboracion">✧ Proponer colaboración</button></div></div>
                    <button type="button" class="ver-preview" id="ver-preview">◉ Ver vista previa del mensaje</button>
                    <div class="acciones"><button class="enviar" type="submit" id="boton-enviar"><span>Validar y procesar</span><span class="flecha">↗</span></button><button class="limpiar" type="button" id="limpiar" title="Vaciar el formulario">↺ Limpiar</button></div>
                    <p class="aviso-demo">ⓘ Demostración académica: no se envían correos reales.</p>
                </form>
            </section>
        </div>
        <section class="extra" aria-label="Detalles del ejercicio"><div><span>01</span><h3>Validación instantánea</h3><p>Los campos te indican si los datos son correctos antes de procesarlos.</p></div><div><span>02</span><h3>Vista previa dinámica</h3><p>El mensaje cambia en tiempo real mientras escribes, sin salir del formulario.</p></div><div><span>03</span><h3>Validación en PHP</h3><p>El servidor verifica nuevamente los datos antes de confirmar la demostración.</p></div></section>
    </main>
    <footer><span>© <?= date('Y') ?> TEAM EVORION</span><span>UNIVERSIDAD BOLIVARIANA DEL ECUADOR · PROGRAMACIÓN WEB AVANZADA</span></footer>
</div>
<dialog id="modal-preview" class="modal-preview" aria-labelledby="modal-titulo"><div class="modal-cabecera"><strong id="modal-titulo">Vista previa de tu mensaje</strong><button type="button" id="cerrar-preview" aria-label="Cerrar vista previa">✕</button></div><div class="modal-contenido"><span id="modal-tipo">Consulta</span><h3 id="modal-nombre">Tu nombre</h3><p id="modal-correo">tu-correo@ejemplo.com</p><div id="modal-mensaje">Escribe un mensaje para visualizarlo.</div></div><p>Vista previa local: no se enviará ningún correo.</p></dialog>
</body>
</html>
