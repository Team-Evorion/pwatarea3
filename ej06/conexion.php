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
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION["error_login"] =
        "No se pudo conectar con la base de datos. Inténtalo más tarde.";

    header("Location: index.php");
    exit;
}

?>