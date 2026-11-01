<?php
/**
 * Ejercicio 05 · Galería de imágenes
 * Lee los archivos del directorio «imagenes» y genera la galería automáticamente.
 * No necesita base de datos ni una lista manual de fotografías.
 */

declare(strict_types=1);

$directorioImagenes = __DIR__ . '/imagenes';
$extensionesPermitidas = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
$archivosImagenes = [];

// El directorio se comprueba antes de abrirlo para evitar advertencias PHP.
if (is_dir($directorioImagenes) && is_readable($directorioImagenes)) {
    $contenido = scandir($directorioImagenes);

    if ($contenido !== false) {
        foreach ($contenido as $archivo) {
            if ($archivo === '.' || $archivo === '..') {
                continue;
            }

            // Solo se admiten archivos directos de la carpeta y con extensión de imagen.
            $rutaFisica = $directorioImagenes . DIRECTORY_SEPARATOR . $archivo;
            $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

            if (!is_file($rutaFisica) || !in_array($extension, $extensionesPermitidas, true)) {
                continue;
            }

            $archivosImagenes[] = $archivo;
        }
    }
}

// Orden estable y legible al añadir o cambiar imágenes.
natcasesort($archivosImagenes);
$archivosImagenes = array_values($archivosImagenes);

/** Escapa texto destinado a un atributo o contenido HTML. */
function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Devuelve el nombre para mostrar, sin extensión y con espacios. */
function tituloImagen(string $archivo): string
{
    $nombre = pathinfo($archivo, PATHINFO_FILENAME);

    // Retira prefijos automáticos, identificadores aleatorios y resoluciones.
    $nombre = preg_replace('/^canva[-_]/iu', '', $nombre) ?? $nombre;
    $nombre = preg_replace('/[-_](?=[A-Za-z0-9]{8,}$)(?=[A-Za-z0-9]*[A-Z])(?=[A-Za-z0-9]*[a-z])[A-Za-z0-9]+$/u', '', $nombre) ?? $nombre;
    $nombre = preg_replace('/[-_]\d+(?:[-_]\d+x\d+)?$/iu', '', $nombre) ?? $nombre;
    $nombre = preg_replace('/[-_]+/u', ' ', $nombre) ?? $nombre;
    $nombre = preg_replace('/\s+/u', ' ', $nombre) ?? $nombre;
    $nombre = trim($nombre);

    if ($nombre === '' || in_array(strtolower($nombre), ['img', 'image', 'images', 'pai', 'foto', 'photo'], true)) {
        return 'Paisaje natural';
    }

    return ucfirst($nombre);
}

/** Codifica el nombre real del archivo para crear una URL segura. */
function urlImagen(string $archivo): string
{
    return 'imagenes/' . rawurlencode($archivo);
}

$totalImagenes = count($archivosImagenes);
$primeraImagen = $totalImagenes > 0 ? $archivosImagenes[0] : null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0F172A">
    <meta name="description" content="Galería dinámica de paisajes de WEB/INTL, desarrollada con PHP, CSS y JavaScript.">
    <title>Galería de paisajes | Ejercicio 05 · WEB/INTL</title>
    <link rel="stylesheet" href="ej05.css">
    <script src="ej05.js" defer></script>
</head>
<body>
    <a class="saltar-contenido" href="#galeria">Saltar a la galería</a>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="../index.php" aria-label="WEB/INTL, ir al menú principal">
                <span class="brand-name">WEB<span class="brand-slash">/</span>INTL</span>
                <span class="brand-detail">LABORATORIO WEB</span>
            </a>

            <nav class="header-navigation" aria-label="Navegación de esta página">
                <a class="header-link" href="#galeria">Colección</a>
                <a class="header-link header-link-secondary" href="#proceso">El proyecto</a>
                <a class="back-link" href="../index.php"><span aria-hidden="true">←</span> Menú principal</a>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero" aria-labelledby="hero-title">
            <div class="hero-glow hero-glow-one" aria-hidden="true"></div>
            <div class="hero-glow hero-glow-two" aria-hidden="true"></div>
            <div class="container hero-layout">
                <div class="hero-content">
                    <p class="eyebrow"><span class="eyebrow-line"></span> EJERCICIO 05 <span class="eyebrow-divider">/</span> GALERÍA DE IMÁGENES</p>
                    <h1 id="hero-title">Paisajes que<br><span>inspiran.</span></h1>
                    <p class="hero-description">Un recorrido visual por lugares extraordinarios. Descubre la naturaleza a través de una colección que cobra vida con cada fotografía.</p>
                    <div class="hero-actions">
                        <a class="primary-button" href="#galeria">Explorar galería <span aria-hidden="true">↗</span></a>
                        <span class="hero-note">PHP · CSS · JAVASCRIPT</span>
                    </div>
                    <div class="hero-bottom-line" aria-hidden="true"><span>WEB/INTL — 2026</span><span>SCROLL TO EXPLORE ↓</span></div>
                </div>

                <div class="hero-visual" aria-label="Vista destacada de la galería">
                    <div class="visual-orbit visual-orbit-one" aria-hidden="true"></div>
                    <div class="visual-orbit visual-orbit-two" aria-hidden="true"></div>
                    <div class="feature-card">
                        <?php if ($primeraImagen !== null): ?>
                            <img
                                class="feature-image"
                                src="<?= escapar(urlImagen($primeraImagen)) ?>"
                                alt="Vista destacada: <?= escapar(tituloImagen($primeraImagen)) ?>"
                                fetchpriority="high">
                        <?php else: ?>
                            <div class="feature-placeholder" aria-hidden="true">
                                <span class="placeholder-sun"></span>
                                <span class="placeholder-mountain placeholder-mountain-back"></span>
                                <span class="placeholder-mountain placeholder-mountain-front"></span>
                            </div>
                        <?php endif; ?>
                        <div class="feature-caption">
                            <span class="feature-caption-dot"></span>
                            <span>LA NATURALEZA, EN PRIMER PLANO</span>
                            <span aria-hidden="true">↗</span>
                        </div>
                    </div>
                    <div class="visual-index" aria-hidden="true">05<span>/06</span></div>
                    <div class="visual-vertical" aria-hidden="true">COLECCIÓN / PAISAJES</div>
                </div>
            </div>
        </section>

        <section class="gallery-section" id="galeria" aria-labelledby="gallery-title">
            <div class="container">
                <div class="section-heading">
                    <div>
                        <p class="section-eyebrow"><span class="section-eyebrow-mark">✦</span> 01 / LA COLECCIÓN</p>
                        <h2 id="gallery-title">Una ventana a<br><em>otros horizontes.</em></h2>
                        <p class="section-description">Cada imagen, una pausa. Selecciona una fotografía para verla en detalle y recorrer la colección.</p>
                    </div>
                    <div class="collection-count" aria-label="Total de fotografías: <?= $totalImagenes ?>">
                        <span class="collection-count-number"><?= str_pad((string) $totalImagenes, 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="collection-count-label">FOTOGRAFÍAS<br>EN LA GALERÍA</span>
                    </div>
                </div>

                <?php if ($totalImagenes > 0): ?>
                    <ul class="gallery-grid" aria-label="Lista de fotografías de paisajes">
                        <?php foreach ($archivosImagenes as $indice => $archivo): ?>
                            <?php
                            $numero = str_pad((string) ($indice + 1), 2, '0', STR_PAD_LEFT);
                            $titulo = tituloImagen($archivo);
                            if ($titulo === 'Paisaje natural') {
                                $titulo = 'Paisaje ' . $numero;
                            }
                            $url = urlImagen($archivo);
                            ?>
                            <li class="gallery-item">
                                <button
                                    class="gallery-card"
                                    type="button"
                                    data-gallery-item
                                    data-src="<?= escapar($url) ?>"
                                    data-title="<?= escapar($titulo) ?>"
                                    data-alt="<?= escapar('Fotografía de paisaje: ' . $titulo) ?>"
                                    aria-label="Ampliar imagen <?= $indice + 1 ?> de <?= $totalImagenes ?>: <?= escapar($titulo) ?>">
                                    <img
                                        class="gallery-card-image"
                                        src="<?= escapar($url) ?>"
                                        alt="<?= escapar('Fotografía de paisaje: ' . $titulo) ?>"
                                        loading="lazy"
                                        decoding="async">
                                    <span class="card-shade" aria-hidden="true"></span>
                                    <span class="card-index" aria-hidden="true">/ <?= $numero ?></span>
                                    <span class="card-zoom" aria-hidden="true">↗</span>
                                    <span class="card-content">
                                        <span class="card-kicker">PAISAJE · <?= $numero ?></span>
                                        <span class="card-title"><?= escapar($titulo) ?></span>
                                        <span class="card-hint">VER FOTOGRAFÍA <span aria-hidden="true">↗</span></span>
                                    </span>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="empty-state" role="status">
                        <div class="empty-illustration" aria-hidden="true">
                            <div class="empty-symbol">✳</div>
                            <div class="empty-line empty-line-one"></div>
                            <div class="empty-line empty-line-two"></div>
                            <div class="empty-line empty-line-three"></div>
                        </div>
                        <div class="empty-content">
                            <span class="empty-label">LA GALERÍA ESTÁ LISTA</span>
                            <h3>Las mejores vistas<br>aún están por llegar.</h3>
                            <p>Extrae las cinco imágenes de <strong>Imagines.zip</strong> y colócalas en <code>ej05/imagenes/</code>. La galería aparecerá automáticamente cuando recargues esta página.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="gallery-footnote">
                    <span><span class="footnote-dot" aria-hidden="true"></span> UNA GALERÍA QUE SE ACTUALIZA SOLA</span>
                    <span>05 — WEB/INTL</span>
                </div>
            </div>
        </section>

        <section class="process-section" id="proceso" aria-labelledby="process-title">
            <div class="container process-layout">
                <div>
                    <p class="section-eyebrow process-eyebrow">02 / DETRÁS DE LA GALERÍA</p>
                    <h2 id="process-title">Diseño que se ve.<br><span>Código que funciona.</span></h2>
                </div>
                <div class="process-description">
                    <p>La colección se genera automáticamente: PHP identifica las imágenes disponibles, CSS transforma cada archivo en una tarjeta visual y JavaScript permite explorarlas sin salir de la página.</p>
                    <div class="tech-tags" aria-label="Tecnologías utilizadas"><span>PHP</span><span>HTML5</span><span>CSS3</span><span>JAVASCRIPT</span></div>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <a class="footer-brand" href="../index.php">WEB<span>/</span>INTL</a>
            <p>Tarea Práctica 3 · Ejercicio 05</p>
            <p>Universidad Bolivariana del Ecuador · 2026</p>
        </div>
    </footer>

    <!-- Visor accesible: JavaScript asigna la imagen y controla la navegación. -->
    <dialog class="lightbox" id="visor" aria-label="Visor ampliado de fotografías">
        <div class="lightbox-panel">
            <div class="lightbox-topbar">
                <span class="lightbox-brand">WEB<span>/</span>INTL <span class="lightbox-slash">— VISOR</span></span>
                <button class="lightbox-close" type="button" data-close aria-label="Cerrar visor">CERRAR <span aria-hidden="true">×</span></button>
            </div>
            <div class="lightbox-stage">
                <button class="lightbox-arrow lightbox-prev" type="button" data-prev aria-label="Ver imagen anterior">←</button>
                <figure class="lightbox-figure">
                    <img id="visor-imagen" alt="">
                    <figcaption class="lightbox-caption">
                        <span id="visor-titulo"></span>
                        <span id="visor-contador" aria-live="polite"></span>
                    </figcaption>
                </figure>
                <button class="lightbox-arrow lightbox-next" type="button" data-next aria-label="Ver siguiente imagen">→</button>
            </div>
            <p class="lightbox-instructions">Usa ← → para navegar · ESC para cerrar</p>
        </div>
    </dialog>
</body>
</html>
