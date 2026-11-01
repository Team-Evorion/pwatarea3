<?php
/**
 * Ejercicio 04 — API del juego.
 * El número secreto se genera en PHP y nunca se envía al navegador mientras
 * la partida continúa. Se permiten dos intentos por partida.
 */
declare(strict_types=1);

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');

const MINIMO = 1;
const MAXIMO = 10;
const MAX_INTENTOS = 2;

/** Respuesta JSON consistente para JavaScript. */
function responder(array $datos, int $codigo = 200): void
{
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    responder(['mensaje' => 'Utiliza el formulario para iniciar una partida.', 'terminado' => true], 405);
}

$entrada = json_decode(file_get_contents('php://input'), true);
if (!is_array($entrada)) {
    responder(['mensaje' => 'Datos enviados no válidos.', 'terminado' => false], 400);
}

$accion = $entrada['accion'] ?? null;

if ($accion === 'nuevo') {
    // Nueva cifra cada vez que se reinicia la partida.
    $_SESSION['numero_secreto'] = random_int(MINIMO, MAXIMO);
    $_SESSION['intentos'] = 0;

    responder([
        'mensaje' => '¡Partida lista! Escribe tu primer número.',
        'terminado' => false,
        'acertado' => false,
        'intentos' => 0,
        'restantes' => MAX_INTENTOS,
    ]);
}

if ($accion !== 'adivinar') {
    responder(['mensaje' => 'Acción no reconocida.', 'terminado' => false], 400);
}

if (!isset($_SESSION['numero_secreto'], $_SESSION['intentos'])) {
    responder([
        'mensaje' => 'No hay una partida activa. Pulsa «Nuevo juego».',
        'terminado' => true,
        'acertado' => false,
        'restantes' => 0,
    ], 409);
}

$numero = $entrada['numero'] ?? null;
if (!is_int($numero) || $numero < MINIMO || $numero > MAXIMO) {
    responder([
        'mensaje' => 'Introduce un número entero del 1 al 10.',
        'terminado' => false,
        'acertado' => false,
        'intentos' => $_SESSION['intentos'],
        'restantes' => MAX_INTENTOS - $_SESSION['intentos'],
    ], 422);
}

$_SESSION['intentos']++;
$intentos = $_SESSION['intentos'];
$restantes = MAX_INTENTOS - $intentos;
$secreto = $_SESSION['numero_secreto'];

if ($numero === $secreto) {
    unset($_SESSION['numero_secreto'], $_SESSION['intentos']);
    responder([
        'mensaje' => "¡Felicidades! Adivinaste el número $secreto en $intentos " . ($intentos === 1 ? 'intento.' : 'intentos.'),
        'terminado' => true,
        'acertado' => true,
        'intentos' => $intentos,
        'restantes' => $restantes,
    ]);
}

if ($restantes > 0) {
    $pista = $numero < $secreto ? 'mayor' : 'menor';
    responder([
        'mensaje' => "No es $numero. Pista: el número secreto es $pista. ¡Te queda una oportunidad!",
        'terminado' => false,
        'acertado' => false,
        'intentos' => $intentos,
        'restantes' => $restantes,
        'pista' => $pista,
    ]);
}

unset($_SESSION['numero_secreto'], $_SESSION['intentos']);
responder([
    'mensaje' => "¡Se acabaron los intentos! El número secreto era $secreto. Puedes jugar otra vez.",
    'terminado' => true,
    'acertado' => false,
    'intentos' => $intentos,
    'restantes' => 0,
]);
