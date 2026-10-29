
<?php
session_start();
require_once "conexion.php";

// Solo aceptar datos enviados desde el formulario
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: registro.php");
    exit;
}

// Recibir los datos
$usuario = trim($_POST["usuario"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";
$confirmar = $_POST["confirmar"] ?? "";

// Comprobar que los campos no estén vacíos
if ($usuario === "" || $contrasena === "" || $confirmar === "") {
    $_SESSION["error_registro"] = "Debes completar todos los campos.";
    header("Location: registro.php");
    exit;
}

// Validar los requisitos de la contraseña
if (
    strlen($contrasena) < 8 ||
    strlen($contrasena) > 20 ||
    !preg_match("/[a-z]/", $contrasena) ||
    !preg_match("/[A-Z]/", $contrasena) ||
    !preg_match("/[0-9]/", $contrasena)
) {
    $_SESSION["error_registro"] = "La contraseña no cumple los requisitos.";
    header("Location: registro.php");
    exit;
}

// Comprobar que las contraseñas coincidan
if ($contrasena !== $confirmar) {
    $_SESSION["error_registro"] = "Las contraseñas no coinciden.";
    header("Location: registro.php");
    exit;
}

// Comprobar la longitud del usuario
if (mb_strlen($usuario) > 50) {
    $_SESSION["error_registro"] = "El usuario no puede superar los 50 caracteres.";
    header("Location: registro.php");
    exit;
}

// Verificar que el usuario no exista
$sql = "SELECT id FROM usuarios WHERE usuario = :usuario LIMIT 1";

$consulta = $conexion->prepare($sql);
$consulta->execute(["usuario" => $usuario]);

if ($consulta->fetch()) {
    $_SESSION["error_registro"] = "Este nombre de usuario ya está registrado.";
    header("Location: registro.php");
    exit;
}

// Crear un hash seguro de la contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Guardar el nuevo usuario
$sql = "INSERT INTO usuarios (usuario, contrasena)
        VALUES (:usuario, :contrasena)";

$consulta = $conexion->prepare($sql);

$consulta->execute([
    "usuario" => $usuario,
    "contrasena" => $contrasenaHash
]);

// Regresar al inicio de sesión
$_SESSION["registro_exitoso"] = "Cuenta creada correctamente. Ya puedes iniciar sesión.";

header("Location: index.php");
exit;
