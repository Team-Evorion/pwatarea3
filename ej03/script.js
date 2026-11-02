'use strict';
const tema = document.querySelector('#tema');
try { if (localStorage.getItem('evorion-theme') === 'light') document.documentElement.classList.add('light'); } catch (_) {}
function refrescarTema() { if (!tema) return; const claro = document.documentElement.classList.contains('light'); tema.textContent = claro ? '☾' : '☼'; tema.setAttribute('aria-label', claro ? 'Activar modo oscuro' : 'Activar modo claro'); }
refrescarTema();
if (tema) tema.addEventListener('click', () => { document.documentElement.classList.toggle('light'); try { localStorage.setItem('evorion-theme', document.documentElement.classList.contains('light') ? 'light' : 'dark'); } catch (_) {} refrescarTema(); });

const totalElemento = document.querySelector('.total[data-total]');
const total = totalElemento ? Number(totalElemento.dataset.total) : NaN;
const formato = new Intl.NumberFormat('es-EC');
const movimientoReducido = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
if (totalElemento && Number.isSafeInteger(total) && total >= 0 && total <= 1e9 && !movimientoReducido) {
    const inicio = performance.now();
    function animar(tiempo) {
        const p = Math.min((tiempo - inicio) / 1000, 1);
        totalElemento.textContent = formato.format(Math.round(total * (1 - Math.pow(1 - p, 4))));
        if (p < 1) requestAnimationFrame(animar);
        else totalElemento.textContent = formato.format(total);
    }
    requestAnimationFrame(animar);
}

const toast = document.querySelector('#toast');
let toastTimeout;
function notificar(mensaje) {
    toast.textContent = mensaje;
    toast.classList.add('visible');
    clearTimeout(toastTimeout);
    toastTimeout = setTimeout(() => toast.classList.remove('visible'), 2800);
}
const copiar = document.querySelector('#copiar');
if (copiar) copiar.addEventListener('click', async () => {
    if (!Number.isSafeInteger(total)) return;
    const texto = `Visitas globales de Team Evorion: ${formato.format(total)}`;
    try {
        if (navigator.clipboard?.writeText) { await navigator.clipboard.writeText(texto); notificar('✓ Total copiado al portapapeles'); }
        else notificar('Copia el total mostrado en pantalla');
    } catch (_) { notificar('Tu navegador no permitió copiar automáticamente'); }
});

const barras = [...document.querySelectorAll('.barra-dia')];
const botonesRango = [...document.querySelectorAll('[data-rango]')];
const detalle = document.querySelector('#grafico-detalle');
function seleccionarBarra(barra) {
    barras.forEach(b => b.classList.toggle('seleccionada', b === barra));
    const n = Number(barra.dataset.cantidad);
    const cantidad = barra.dataset.cantidad === '' ? 'sin datos registrados' : `${formato.format(n)} ${n === 1 ? 'visita' : 'visitas'}`;
    detalle.textContent = `${barra.dataset.etiqueta}: ${cantidad}.`;
}
barras.forEach(barra => barra.addEventListener('click', () => seleccionarBarra(barra)));
botonesRango.forEach(boton => boton.addEventListener('click', () => {
    const rango = Number(boton.dataset.rango);
    barras.forEach((barra, indice) => barra.classList.toggle('fuera-rango', indice < barras.length - rango));
    botonesRango.forEach(b => { const activo = b === boton; b.classList.toggle('activo', activo); b.setAttribute('aria-pressed', String(activo)); });
    const seleccionada = document.querySelector('.barra-dia.seleccionada');
    if (seleccionada?.classList.contains('fuera-rango')) seleccionada.classList.remove('seleccionada');
    detalle.textContent = `Mostrando los últimos ${rango} días. Selecciona una barra para ver su detalle.`;
}));

const reloj = document.querySelector('#reloj');
function actualizarReloj() { if (reloj) reloj.textContent = new Date().toLocaleTimeString('es-EC', {hour12:false}); }
actualizarReloj();
setInterval(actualizarReloj, 1000); // Solo actualiza el reloj; NO añade visitas.
