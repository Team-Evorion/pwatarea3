
<?php
session_start();
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$usuario = trim($_POST["usuario"] ?? "");
$contrasena = $_POST["contrasena"] ?? "";

if ($usuario === "" || $contrasena === "" || strlen($contrasena) > 20) {
    $_SESSION["error_login"] = "Datos inválidos. Revisa el formulario.";
    header("Location: index.php");
    exit;
}

$sql = "SELECT id, usuario, contrasena
        FROM usuarios
        WHERE usuario = :usuario
        LIMIT 1";

$consulta = $conexion->prepare($sql);
$consulta->execute(["usuario" => $usuario]);

$datos = $consulta->fetch(PDO::FETCH_ASSOC);

if ($datos && password_verify($contrasena, $datos["contrasena"])) {
    session_regenerate_id(true);
    $_SESSION["usuario_id"] = $datos["id"];
    $_SESSION["usuario"] = $datos["usuario"];

    header("Location: ../index.php");
    exit;
}

$_SESSION["error_login"] = "Usuario o contraseña incorrectos.";

header("Location: index.php");
exit;
