<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

$usuario = htmlspecialchars($_SESSION["usuario"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de usuario</title>
</head>
<body>
    <h1>Bienvenido, <?php echo $usuario; ?></h1>
    <p>Has iniciado sesión correctamente.</p>

    <a href="logout.php">Cerrar sesión</a>
</body>
</html>