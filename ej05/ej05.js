/* Ejercicio 05 · Interacción del visor de fotografías WEB/INTL */
'use strict';

const tarjetas = Array.from(document.querySelectorAll('[data-gallery-item]'));
const visor = document.getElementById('visor');

// La sección de galería puede estar vacía; en ese caso no hay eventos que registrar.
if (tarjetas.length > 0 && visor && typeof visor.showModal === 'function') {
    const imagenAmpliada = document.getElementById('visor-imagen');
    const tituloAmpliado = document.getElementById('visor-titulo');
    const contador = document.getElementById('visor-contador');
    const botonCerrar = visor.querySelector('[data-close]');
    const botonAnterior = visor.querySelector('[data-prev]');
    const botonSiguiente = visor.querySelector('[data-next]');

    let indiceActual = 0;
    let tarjetaDeOrigen = null;

    function mostrarImagen(indice) {
        // El operador % permite circular de la última a la primera y viceversa.
        indiceActual = (indice + tarjetas.length) % tarjetas.length;
        const tarjeta = tarjetas[indiceActual];

        imagenAmpliada.src = tarjeta.dataset.src;
        imagenAmpliada.alt = tarjeta.dataset.alt;
        tituloAmpliado.textContent = tarjeta.dataset.title;
        contador.textContent = `Imagen ${indiceActual + 1} de ${tarjetas.length}`;
    }

    function abrirVisor(indice) {
        tarjetaDeOrigen = tarjetas[indice];
        mostrarImagen(indice);
        visor.showModal();
        document.body.classList.add('visor-abierto');
        botonCerrar.focus();
    }

    function cerrarVisor() {
        if (visor.open) {
            visor.close();
        }
    }

    tarjetas.forEach((tarjeta, indice) => {
        tarjeta.addEventListener('click', () => abrirVisor(indice));
    });

    botonCerrar.addEventListener('click', cerrarVisor);
    botonAnterior.addEventListener('click', () => mostrarImagen(indiceActual - 1));
    botonSiguiente.addEventListener('click', () => mostrarImagen(indiceActual + 1));

    visor.addEventListener('keydown', (evento) => {
        if (evento.key === 'ArrowLeft') {
            evento.preventDefault();
            mostrarImagen(indiceActual - 1);
        } else if (evento.key === 'ArrowRight') {
            evento.preventDefault();
            mostrarImagen(indiceActual + 1);
        }
        // ESC cierra el <dialog> automáticamente mediante el navegador.
    });

    // Un clic sobre el área oscura, fuera del panel de la imagen, cierra el visor.
    visor.addEventListener('click', (evento) => {
        if (evento.target === visor) {
            cerrarVisor();
        }
    });

    visor.addEventListener('close', () => {
        document.body.classList.remove('visor-abierto');
        imagenAmpliada.removeAttribute('src');
        if (tarjetaDeOrigen && tarjetaDeOrigen.isConnected) {
            tarjetaDeOrigen.focus();
        }
    });
}
