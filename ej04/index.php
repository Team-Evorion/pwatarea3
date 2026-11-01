<?php
/**
 * WEB/INTL — Ejercicio 04: Juego de adivinanzas.
 * Página de presentación del juego. La comprobación se realiza en ej04.php,
 * que utiliza sesiones PHP para conservar el número secreto entre intentos.
 */
declare(strict_types=1);

$minimo = 1;
$maximo = 10;
$maximoIntentos = 2;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F172A">
    <meta name="description" content="Pon a prueba tu intuición en el juego de adivinanzas de WEB/INTL, desarrollado con PHP y JavaScript.">
    <title>Juego de adivinanzas | Ejercicio 04 · WEB/INTL</title>
    <link rel="stylesheet" href="ej04.css">
    <script src="ej04.js" defer></script>
</head>
<body>
    <a class="saltar" href="#zona-juego">Ir al juego</a>

    <header class="cabecera">
        <div class="contenedor cabecera-interior">
            <a class="marca" href="../index.php" aria-label="WEB/INTL, ir al menú principal">
                <span class="marca-principal">WEB<span class="marca-barra">/</span>INTL</span>
                <span class="marca-subtitulo">LABORATORIO WEB</span>
            </a>
            <nav class="navegacion" aria-label="Navegación principal">
                <a class="enlace-seccion" href="#zona-juego">Jugar ahora</a>
                <a class="volver" href="../index.php"><span aria-hidden="true">←</span> Menú principal</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" aria-labelledby="titulo">
            <div class="hero-decoracion decoracion-uno" aria-hidden="true"></div>
            <div class="hero-decoracion decoracion-dos" aria-hidden="true"></div>
            <div class="contenedor hero-interior">
                <div class="hero-texto">
                    <p class="etiqueta"><span class="raya" aria-hidden="true"></span> EXPERIMENTO 04 <span class="separador">/</span> LÓGICA + AZAR</p>
                    <h1 id="titulo">Un número.<br><span class="titulo-degradado">Dos intentos.</span><br>¿Lo adivinas?</h1>
                    <p class="hero-descripcion">Estoy pensando en un número del <strong><?= $minimo ?></strong> al <strong><?= $maximo ?></strong>. Pon a prueba tu intuición y descubre si puedes acertar antes de que se agoten tus oportunidades.</p>
                    <div class="hero-acciones">
                        <a class="accion-principal" href="#zona-juego">Comenzar a jugar <span aria-hidden="true">↗</span></a>
                        <span class="hero-leyenda"><span class="punto" aria-hidden="true"></span> DESAFÍO INTERACTIVO</span>
                    </div>
                </div>
                <div class="hero-visual" aria-hidden="true">
                    <div class="halo halo-exterior"></div>
                    <div class="halo halo-interior"></div>
                    <div class="orbita orbita-superior">?</div>
                    <div class="esfera"><span>?</span></div>
                    <div class="orbita orbita-inferior">01—10</div>
                    <p class="visual-nota">EL RETO ES DESCUBRIRLO</p>
                </div>
            </div>
            <div class="contenedor hero-pie">
                <span>WEB/INTL — UNIVERSIDAD BOLIVARIANA DEL ECUADOR</span>
                <span>PHP <span aria-hidden="true">✳</span> JAVASCRIPT <span aria-hidden="true">✳</span> CSS</span>
            </div>
        </section>

        <section class="zona-juego" id="zona-juego" aria-labelledby="titulo-juego">
            <div class="contenedor">
                <div class="seccion-cabecera">
                    <div>
                        <p class="etiqueta etiqueta-violeta">04 / EL DESAFÍO</p>
                        <h2 id="titulo-juego">Confía en tu <span>intuición.</span></h2>
                    </div>
                    <p>Una cifra secreta. Dos oportunidades para descubrirla. ¿Te animas?</p>
                </div>

                <div class="tablero">
                    <section class="tarjeta-juego" aria-label="Juego de adivinanzas">
                        <div class="tarjeta-superior">
                            <span class="identificador"><span class="indicador" aria-hidden="true"></span> PARTIDA EN CURSO</span>
                            <span class="tarjeta-numero">Nº 004</span>
                        </div>
                        <div class="tarjeta-contenido">
                            <div class="barra-progreso">
                                <div>
                                    <span class="microtitulo">OPORTUNIDADES</span>
                                    <p class="intentos-texto"><strong id="intentosRestantes"><?= $maximoIntentos ?></strong> de <?= $maximoIntentos ?> intentos disponibles</p>
                                </div>
                                <div class="marcas" id="marcasIntentos" aria-label="Dos intentos disponibles">
                                    <?php for ($i = 0; $i < $maximoIntentos; $i++): ?>
                                    <span class="marca-intento" aria-hidden="true"></span>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <h3>¿Cuál es el número?</h3>
                            <p class="instruccion">Escribe un número entero entre <?= $minimo ?> y <?= $maximo ?>.</p>

                            <form id="formAdivinar" class="formulario" novalidate>
                                <label for="numero">TU RESPUESTA</label>
                                <div class="entrada-grupo">
                                    <input id="numero" name="numero" type="number" min="<?= $minimo ?>" max="<?= $maximo ?>" step="1" inputmode="numeric" placeholder="Ej. 7" autocomplete="off" required aria-describedby="ayudaNumero">
                                    <span class="entrada-limite" aria-hidden="true">01 — 10</span>
                                </div>
                                <p class="ayuda" id="ayudaNumero">Solo números enteros, sin decimales.</p>
                                <button id="btnAdivinar" class="boton-adivinar" type="submit">Probar suerte <span aria-hidden="true">→</span></button>
                            </form>

                            <div class="resultado" id="resultado" role="status" aria-live="polite" aria-atomic="true" data-estado="neutral">
                                <span class="resultado-icono" id="resultadoIcono" aria-hidden="true">✦</span>
                                <div>
                                    <strong id="resultadoTitulo">Preparando la partida…</strong>
                                    <p id="resultadoMensaje">El servidor está generando tu número secreto.</p>
                                </div>
                            </div>

                            <div class="tarjeta-pie">
                                <span id="estadoPartida" class="estado-partida">CARGANDO PARTIDA</span>
                                <button class="boton-reinicio" id="btnReiniciar" type="button"><span aria-hidden="true">↻</span> Nuevo juego</button>
                            </div>
                        </div>
                    </section>

                    <aside class="panel-info" aria-labelledby="titulo-reglas">
                        <div class="panel-info-superior">
                            <p class="etiqueta etiqueta-clara">GUÍA RÁPIDA / 001</p>
                            <span aria-hidden="true" class="icono-reglas">✳</span>
                        </div>
                        <h3 id="titulo-reglas">Las reglas<br>son simples.</h3>
                        <ol class="lista-reglas">
                            <li><span class="paso">01</span><div><strong>Elige tu número</strong><p>Piensa en un número entre el 1 y el 10.</p></div></li>
                            <li><span class="paso">02</span><div><strong>Prueba tu intuición</strong><p>Envía tu respuesta; PHP comprobará si acertaste.</p></div></li>
                            <li><span class="paso">03</span><div><strong>Última oportunidad</strong><p>Si fallas, tendrás un intento más y una pista.</p></div></li>
                        </ol>
                        <div class="nota-info"><span aria-hidden="true">✦</span><p>El número secreto se genera en el servidor y se conserva mediante una sesión PHP.</p></div>
                    </aside>
                </div>
                <div class="pie-ejercicio"><span>TECNOLOGÍAS: PHP  ·  JAVASCRIPT  ·  HTML5  ·  CSS3</span><a href="../index.php">Volver a todos los ejercicios <span aria-hidden="true">↗</span></a></div>
            </div>
        </section>
    </main>

    <footer class="pie-pagina"><div class="contenedor pie-interior"><span class="pie-marca">WEB<span>/</span>INTL</span><span>Ejercicio 04 — Programación Web Avanzada</span><span>UBE · 2026</span></div></footer>
</body>
</html>
