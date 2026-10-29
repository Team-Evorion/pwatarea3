<?php
$servidor = "localhost";
$usuarioBD = "root";
$contrasenaBD = "";
$baseDatos = "pwatarea3";

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$baseDatos;charset=utf8mb4",
    $usuarioBD,
    $contrasenaBD
    );

    $conexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $error) {
    die("No se pudo conectar con la base de datos.");
}

?>