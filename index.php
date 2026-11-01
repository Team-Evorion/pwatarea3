<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: ej06/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WEB/INTL | Laboratorio Web</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <header class="encabezado">
        <a href="index.php" class="marca">WEB/INTL</a>

        <nav class="navegacion">
            <a href="ej06/logout.php" class="btn-cerrar-sesion">Cerrar sesión</a>
            <a href="#inicio">INICIO</a>
            <a href="#ejercicios">EJERCICIOS</a>
            <a href="#integrantes">INTEGRANTES</a>
            <a href="#acerca">ACERCA DE</a>
        </nav>
    </header>
    <main>
        <section id="inicio" class="portada">
            <h1>
                WEB. <br>
                <span class="texto-contorno">Interactiva</span><br>
                / Laboratorio
            </h1>
            <p class="portada-etiqueta">PROYECTO 03 / HTML - CSS - JAVASCRIPT - PHP - MYSQL</p>
            <p class="portada-descripcion">Seis ejercicios. Cinco tecnologías. Una experiencia</p>
            <a href="#ejercicios" class="boton-principal">EXPLORAR EJERCICIOS</a>
        </section>
        <section id="ejercicios" class="seccion-ejercicios">
            <p class="etiqueta-seccion">ÍNDICE GENERAL / 001/006</p>
            <h2>EXPERIMENTOS</h2>
            <div class="lista-ejercicios">
                <a href="ej01/ej01.html" class="ejercicio"><span>01</span><h3>Menú desplegable</h3><span>&nearr;</span></a>
                
                <a href="ej02/ej02.html" class="ejercicio"><span>02</span><h3>Formulario de contacto</h3><span>&nearr;</span></a>
                
                <a href="ej03/ej03.html" class="ejercicio"><span>03</span><h3>Contador de visitas en PHP</h3><span>&nearr;</span></a>
                
                <a href="ej04/ej04.html" class="ejercicio"><span>04</span><h3>Juego de adivinanzas</h3><span>&nearr;</span></a>
                
                <a href="ej05/ej05.html" class="ejercicio"><span>05</span><h3>Galería de imágenes</h3><span>&nearr;</span></a>
                
                <a href="ej06/index.php" class="ejercicio"><span>06</span><h3>Sistema de login</h3><span>&nearr;</span></a>
            </div>
        </section>

        <section id="integrantes">
            <p class="etiqueta-seccion">03 NUESTRO EQUIPO / 006 INTEGRANTES</p>
            <h2>Integrantes del proyecto</h2>

            <p class="integrantes-descripcion">
                Equipo encargado del desarrollo de los seis ejercicios interactivos de programación web.
            </p>

            <div class="integrantes-grid">
                <article class="integrantes-card">
                    <span class="integrantes-iniciales">JF</span>
                    <h3>Jesús Rafael Fernández Perez</h3>
                </article>

                <article class="integrantes-card">
                    <span class="integrantes-iniciales">JR</span>
                    <h3>José Alberto Rosado Domínguez</h3>
                </article>
                
                <article class="integrantes-card">
                    <span class="integrantes-iniciales">JM</span>
                    <h3>Jose Andres Merlin Gamboa</h3>
                </article>
                
                <article class="integrantes-card">
                    <span class="integrantes-iniciales">TP</span>
                    <h3>Terry Steven Ponce Farias</h3>
                </article>
                
                <article class="integrantes-card">
                    <span class="integrantes-iniciales">EV</span>
                    <h3>Elkin Daniel Velastegui Zambrano</h3>
                </article>
                
                <article class="integrantes-card">
                    <span class="integrantes-iniciales">ZZ</span>
                    <h3>Zahid Joel Zambrano Moreira</h3>
                </article>
            </div>

        </section>

        <section id="acerca" class="seccion-acerca">
            <p class="etiqueta-acerca">04 / ACERCA DEL PROYECTO</p>
        
        <div class="acerca-contenido">
            <h2>
                Diseñar. <br>
                Programar. <br>
                Experimentar.
            </h2>

            <img src="Imagenes/Imagen PC.jfif" alt="Ilustración relacionada con programación web" class="imagen-acerca">
        </div>
        
    <p class="descripcion-acerca">
        Un laboratorio de programación web que reúne seis ejercicios interactivos desarrollados con HTML, CSS, JavaScript, PHP y MySQL.
    </p>
            <p class="universidad">Universidad Bolivariana del Ecuador / 2026</p>
        </section>
    </main>
</body>
</html>