/** WEB/INTL — Ejercicio 04. Interacciones del juego (PHP gestiona el número). */
'use strict';

const formulario = document.getElementById('formAdivinar');
const campoNumero = document.getElementById('numero');
const botonAdivinar = document.getElementById('btnAdivinar');
const botonReiniciar = document.getElementById('btnReiniciar');
const intentosRestantes = document.getElementById('intentosRestantes');
const marcasIntentos = Array.from(document.querySelectorAll('.marca-intento'));
const resultado = document.getElementById('resultado');
const resultadoIcono = document.getElementById('resultadoIcono');
const resultadoTitulo = document.getElementById('resultadoTitulo');
const resultadoMensaje = document.getElementById('resultadoMensaje');
const estadoPartida = document.getElementById('estadoPartida');

let ocupada = false;
let terminada = false;

/** Mensajes accesibles visibles dentro de la tarjeta del juego. */
function mostrarMensaje(estado, titulo, mensaje) {
    resultado.dataset.estado = estado;
    resultadoTitulo.textContent = titulo;
    resultadoMensaje.textContent = mensaje;
    resultadoIcono.textContent = ({ exito: '✓', error: '!', pista: '↗', neutral: '✦' })[estado] || '✦';
}

function actualizarIntentos(restantes) {
    const cantidad = Math.max(0, Math.min(2, Number(restantes)));
    intentosRestantes.textContent = String(cantidad);
    marcasIntentos.forEach((marca, indice) => {
        marca.classList.toggle('usada', indice >= cantidad);
    });
    document.getElementById('marcasIntentos').setAttribute('aria-label', `${cantidad} de 2 intentos disponibles`);
}

function actualizarControles() {
    campoNumero.disabled = ocupada || terminada;
    botonAdivinar.disabled = ocupada || terminada;
    botonReiniciar.disabled = ocupada;
    estadoPartida.textContent = ocupada ? 'CONECTANDO CON PHP' : terminada ? 'PARTIDA FINALIZADA' : 'PARTIDA ACTIVA';
}

/** La misma sesión PHP identifica la partida a través de la cookie del navegador. */
async function enviarDatos(datos) {
    const respuesta = await fetch('ej04.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(datos),
        cache: 'no-store',
    });

    let informacion;
    try {
        informacion = await respuesta.json();
    } catch {
        throw new Error('No se pudo leer la respuesta de PHP. Comprueba que Apache esté activo.');
    }

    // Los errores de validación también incluyen un mensaje útil desde PHP.
    if (!respuesta.ok && respuesta.status !== 422 && respuesta.status !== 409) {
        throw new Error(informacion.mensaje || 'No se pudo conectar con el servidor.');
    }
    return informacion;
}

async function nuevoJuego() {
    if (ocupada) return;
    ocupada = true;
    terminada = false;
    actualizarControles();
    mostrarMensaje('neutral', 'Preparando la partida…', 'Generando tu nuevo número secreto.');
    campoNumero.value = '';
    actualizarIntentos(2);

    try {
        const respuesta = await enviarDatos({ accion: 'nuevo' });
        mostrarMensaje('neutral', '¡Todo listo!', respuesta.mensaje);
    } catch (error) {
        terminada = true;
        mostrarMensaje('error', 'No se pudo iniciar', error.message);
    } finally {
        ocupada = false;
        actualizarControles();
        if (!terminada) campoNumero.focus({ preventScroll: true });
    }
}

async function adivinarNumero(evento) {
    evento.preventDefault();
    if (ocupada || terminada) return;

    const texto = campoNumero.value.trim();
    const numero = Number(texto);
    if (!/^\d+$/.test(texto) || !Number.isInteger(numero) || numero < 1 || numero > 10) {
        mostrarMensaje('error', 'Número no válido', 'Ingresa un número entero entre 1 y 10.');
        campoNumero.focus();
        return;
    }

    ocupada = true;
    actualizarControles();

    try {
        const respuesta = await enviarDatos({ accion: 'adivinar', numero });
        terminada = Boolean(respuesta.terminado);
        if (Number.isInteger(respuesta.restantes)) actualizarIntentos(respuesta.restantes);

        if (respuesta.acertado) {
            mostrarMensaje('exito', '¡Lo lograste!', respuesta.mensaje);
        } else if (terminada) {
            mostrarMensaje('error', 'Fin de la partida', respuesta.mensaje);
        } else if (respuesta.pista) {
            mostrarMensaje('pista', '¡Casi! Sigue intentándolo', respuesta.mensaje);
        } else {
            mostrarMensaje('error', 'Revisa tu respuesta', respuesta.mensaje);
        }
        campoNumero.value = '';
    } catch (error) {
        mostrarMensaje('error', 'Ocurrió un problema', error.message);
    } finally {
        ocupada = false;
        actualizarControles();
        if (!terminada) campoNumero.focus({ preventScroll: true });
        else botonReiniciar.focus({ preventScroll: true });
    }
}

formulario.addEventListener('submit', adivinarNumero);
botonReiniciar.addEventListener('click', nuevoJuego);
nuevoJuego();
