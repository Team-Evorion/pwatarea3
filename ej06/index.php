<?php
session_start();

$error = $_SESSION["error_login"] ?? "";
$exito = $_SESSION["registro_exitoso"] ?? "";

unset($_SESSION["error_login"]);
unset($_SESSION["registro_exitoso"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | Team Evorion</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    
    <main>
        <section class="login">
            <h1>Iniciar sesión</h1>
            <p>Bienvenido al sistema de Team Evorion</p>

            <form action="login.php" method="POST">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" placeholder="Ingresa tu usuario" autocomplete="username" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" placeholder="Ingresa tu contraseña" autocomplete="current-password" maxlength="20" required>
            <button type="button" class="mostrar-contrasena" data-campo="contrasena">Mostrar contraseña</button>

            <button type="submit">Ingresar</button>
            <?php if ($exito !== ""): ?>
                <p class="mensaje-exito">
                    <?php echo htmlspecialchars($exito); ?>
                </p>
            <?php endif; ?>
            <?php if ($error !== ""): ?>
                <p class="mensaje-error">
                    <?php echo htmlspecialchars($error); ?>
                </p>
            <?php endif; ?>

            <p class="enlace-registro">
                ¿No tienes una cuenta?
                <a href="registro.php">Regístrate aquí</a>
            </p>
            </form>
        </section>
    </main>
    <script src="mostrarContrasena.js"></script>
</body>
</html>