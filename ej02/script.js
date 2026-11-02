'use strict';

const form = document.querySelector('#contacto');
const tema = document.querySelector('#tema');
const storedTheme = (() => { try { return localStorage.getItem('evorion-theme'); } catch (_) { return null; } })();
if (storedTheme === 'light') document.documentElement.classList.add('light');
function actualizarTema() {
    if (tema) {
        const claro = document.documentElement.classList.contains('light');
        tema.textContent = claro ? '☾' : '☼';
        tema.setAttribute('aria-label', claro ? 'Activar modo oscuro' : 'Activar modo claro');
    }
}
actualizarTema();
if (tema) tema.addEventListener('click', () => {
    document.documentElement.classList.toggle('light');
    try { localStorage.setItem('evorion-theme', document.documentElement.classList.contains('light') ? 'light' : 'dark'); } catch (_) {}
    actualizarTema();
});

if (form) {
    const campos = {nombre: document.querySelector('#nombre'), correo: document.querySelector('#correo'), mensaje: document.querySelector('#mensaje')};
    const errores = {nombre: document.querySelector('#error-nombre'), correo: document.querySelector('#error-correo'), mensaje: document.querySelector('#error-mensaje')};
    const tipos = form.querySelectorAll('input[name="tipo"]');
    const preview = {nombre: document.querySelector('#preview-nombre'), correo: document.querySelector('#preview-correo'), mensaje: document.querySelector('#preview-mensaje'), tipo: document.querySelector('#preview-tipo'), palabras: document.querySelector('#preview-palabras'), avatar: document.querySelector('#avatar')};
    const mensajes = {
        consulta: {tipo: 'Consulta', texto: 'Hola, me gustaría conocer más información sobre este proyecto de Programación Web. ¿Podrían explicarme cómo funciona y qué tecnologías utiliza?'},
        sugerencia: {tipo: 'Sugerencia', texto: 'Hola, tengo una sugerencia para mejorar la experiencia del sitio: incorporar nuevas funciones interactivas y facilitar la navegación en dispositivos móviles.'},
        colaboracion: {tipo: 'Colaboración', texto: 'Hola, me gustaría proponer una colaboración para desarrollar nuevas ideas y funcionalidades en este proyecto web. ¿Podemos conversar sobre ello?'}
    };
    const contadorPalabras = (s) => s.trim() ? s.trim().split(/\s+/u).length : 0;
    function valor(nombre) { return campos[nombre].value.trim(); }
    function obtenerError(nombre) {
        const v = valor(nombre);
        if (!v) return nombre === 'nombre' ? 'Introduce tu nombre.' : nombre === 'correo' ? 'Introduce tu correo.' : 'Escribe un mensaje.';
        if (nombre === 'nombre' && (v.length < 2 || v.length > 80)) return 'Utiliza entre 2 y 80 caracteres.';
        if (nombre === 'correo' && (v.length > 254 || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v))) return 'Introduce un correo válido.';
        if (nombre === 'mensaje' && (v.length < 10 || v.length > 1000)) return 'Escribe entre 10 y 1000 caracteres.';
        return '';
    }
    function validar(nombre, mostrar = true) {
        const error = obtenerError(nombre);
        const campo = campos[nombre];
        if (mostrar) {
            errores[nombre].textContent = error;
            campo.setAttribute('aria-invalid', String(Boolean(error)));
            campo.closest('.campo').classList.toggle('campo-error', Boolean(error));
        }
        campo.closest('.campo').classList.toggle('campo-ok', !error && Boolean(valor(nombre)));
        return !error;
    }
    function refrescar() {
        const nombre = valor('nombre');
        const correo = valor('correo');
        const mensaje = valor('mensaje');
        const tipo = form.querySelector('input[name="tipo"]:checked')?.value || 'Consulta';
        const validos = Object.keys(campos).filter(c => !obtenerError(c)).length;
        const porcentaje = Math.round(validos / 3 * 100);
        preview.nombre.textContent = nombre || 'Tu nombre aparecerá aquí';
        preview.correo.textContent = correo || 'tu-correo@ejemplo.com';
        preview.mensaje.textContent = mensaje || 'Tu mensaje se mostrará aquí en tiempo real. ¡Prueba a escribir algo en el formulario!';
        preview.tipo.textContent = `● ${tipo}`;
        preview.palabras.textContent = `${contadorPalabras(mensaje)} PALABRAS`;
        preview.avatar.textContent = nombre ? nombre.split(/\s+/).slice(0, 2).map(p => p[0]).join('').toUpperCase() : '?';
        document.querySelector('#caracteres').textContent = `${campos.mensaje.value.length} / 1000`;
        document.querySelector('#palabras').textContent = `${contadorPalabras(mensaje)} palabras`;
        document.querySelector('#campos-completos').textContent = `${validos} / 3`;
        document.querySelector('#porcentaje').textContent = `${porcentaje}%`;
        document.querySelector('#progreso-leyenda').textContent = validos === 3 ? '¡Todo listo para procesar!' : `${validos} de 3 campos completos`;
        document.querySelector('#relleno-progreso').style.width = `${porcentaje}%`;
        document.querySelector('#barra-progreso').setAttribute('aria-valuenow', String(validos));
        const consola = document.querySelector('#consola-texto');
        const linea = validos === 3 ? '✓ Validación completada. Ya puedes procesar el formulario.' : validos === 0 ? '› Esperando datos del formulario...' : `› ${validos} de 3 campos correctos. Sigue completando el formulario.`;
        consola.textContent = linea;
        consola.classList.toggle('listo', validos === 3);
        document.querySelector('#tarjeta-mensaje').classList.toggle('tiene-contenido', Boolean(mensaje));
    }
    Object.entries(campos).forEach(([nombre, campo]) => {
        campo.addEventListener('input', () => { if (campo.getAttribute('aria-invalid') === 'true' || campo.closest('.campo').classList.contains('campo-ok')) validar(nombre); refrescar(); });
        campo.addEventListener('blur', () => { validar(nombre); refrescar(); });
    });
    tipos.forEach(tipo => tipo.addEventListener('change', refrescar));
    document.querySelectorAll('[data-idea]').forEach(boton => boton.addEventListener('click', () => {
        const idea = mensajes[boton.dataset.idea];
        if (!idea) return;
        campos.mensaje.value = idea.texto;
        const radio = [...tipos].find(r => r.value === idea.tipo);
        if (radio) radio.checked = true;
        validar('mensaje'); refrescar(); campos.mensaje.focus();
    }));
    const modal = document.querySelector('#modal-preview');
    const abrirPreview = document.querySelector('#ver-preview');
    const cerrarPreview = document.querySelector('#cerrar-preview');
    abrirPreview.addEventListener('click', () => {
        document.querySelector('#modal-tipo').textContent = form.querySelector('input[name="tipo"]:checked')?.value || 'Consulta';
        document.querySelector('#modal-nombre').textContent = valor('nombre') || 'Tu nombre';
        document.querySelector('#modal-correo').textContent = valor('correo') || 'tu-correo@ejemplo.com';
        document.querySelector('#modal-mensaje').textContent = valor('mensaje') || 'Escribe un mensaje para visualizarlo.';
        if (typeof modal.showModal === 'function') modal.showModal();
    });
    cerrarPreview.addEventListener('click', () => modal.close());
    modal.addEventListener('click', evento => { if (evento.target === modal) modal.close(); });
    document.querySelector('#limpiar').addEventListener('click', () => {
        form.reset();
        Object.entries(campos).forEach(([nombre, campo]) => { campo.closest('.campo').classList.remove('campo-error', 'campo-ok'); campo.setAttribute('aria-invalid', 'false'); errores[nombre].textContent = ''; });
        refrescar(); campos.nombre.focus();
    });
    form.addEventListener('submit', (event) => {
        let primerError = null;
        Object.keys(campos).forEach(nombre => { if (!validar(nombre) && !primerError) primerError = campos[nombre]; });
        if (primerError) { event.preventDefault(); primerError.focus(); refrescar(); return; }
        const boton = document.querySelector('#boton-enviar');
        boton.disabled = true;
        boton.querySelector('span').textContent = 'Procesando con PHP...';
        // El envío normal del navegador continúa; PHP vuelve a validar todos los campos.
    });
    refrescar();
    if (document.querySelector('#alerta-exito')) {
        document.querySelector('#alerta-exito').focus({preventScroll: true});
        if (window.matchMedia('(prefers-reduced-motion: no-preference)').matches) {
            document.body.classList.add('exito-animado');
        }
    }
}
