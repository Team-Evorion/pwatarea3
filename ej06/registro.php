<?php
session_start();

$errorRegistro = $_SESSION["error_registro"] ?? "";

unset($_SESSION["error_registro"]);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse | Team Evorion</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <main>
        <section class="login">
            <h1>Crear cuenta</h1>
            <p>Regístrate en Team Evorion</p>

            <form action="guardar_registro.php" method="POST">
                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" name="usuario"
                       placeholder="Elige un usuario" required>

                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" placeholder="Crea una contraseña" maxlength="20" required>
                <button type="button" class="mostrar-contrasena" data-campo="contrasena"> Mostrar contraseña </button>
                
                <div class="validador">
                    <div class="seguridad-titulo">
                        <strong>Seguridad de la contraseña</strong>
                        <span id="nivel">Sin contraseña</span>
                    </div>

                    <div class="barra-seguridad">
                        <div id="progreso"></div>
                    </div>

                    <p id="req-longitud" class="requisito">✕ Mínimo 8 caracteres</p>
                    <p id="req-maximo" class="requisito">✕ Máximo 20 caracteres</p>
                    <p id="req-minuscula" class="requisito">✕ Al menos una letra minúscula</p>
                    <p id="req-mayuscula" class="requisito">✕ Al menos una letra mayúscula</p>
                    <p id="req-numero" class="requisito">✕ Al menos un número</p>
                </div>
                
                <label for="confirmar">Confirmar contraseña</label>
                <input type="password" id="confirmar" name="confirmar"
                    placeholder="Repite tu contraseña" maxlength="20" required>

                <button type="button" class="mostrar-contrasena" data-campo="confirmar">
                    Mostrar contraseña
                </button>

                <p id="mensaje-confirmacion"></p>

                <button type="submit">Registrarse</button>

                <?php if ($errorRegistro !== ""): ?>
                    <p class="mensaje-error">
                        <?php echo htmlspecialchars($errorRegistro, ENT_QUOTES, "UTF-8"); ?>
                    </p>
                <?php endif; ?>
            </form>

            <p class="enlace-registro">
                ¿Ya tienes una cuenta?
                <a href="index.php">Inicia sesión</a>
            </p>
        </section>
    </main>
    <script src="validadorRegistro.js"></script>
</body>
</html>
