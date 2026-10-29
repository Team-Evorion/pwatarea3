
const contrasena = document.getElementById("contrasena");
const nivel = document.getElementById("nivel");
const progreso = document.getElementById("progreso");

const requisitos = [
    {
        id: "req-longitud",
        texto: "Mínimo 8 caracteres",
        validar: valor => valor.length >= 8
    },
    {
        id: "req-maximo",
        texto: "Máximo 20 caracteres",
        validar: valor => valor.length > 0 && valor.length <= 20
    },
    {
        id: "req-minuscula",
        texto: "Al menos una letra minúscula",
        validar: valor => /[a-z]/.test(valor)
    },
    {
        id: "req-mayuscula",
        texto: "Al menos una letra mayúscula",
        validar: valor => /[A-Z]/.test(valor)
    },
    {
        id: "req-numero",
        texto: "Al menos un número",
        validar: valor => /[0-9]/.test(valor)
    }
];

contrasena.addEventListener("input", () => {
    const valor = contrasena.value;
    let cumplidos = 0;

    requisitos.forEach(requisito => {
        const elemento = document.getElementById(requisito.id);
        const correcto = requisito.validar(valor);

        elemento.classList.toggle("cumplido", correcto);
        elemento.textContent =
            `${correcto ? "✓" : "✕"} ${requisito.texto}`;

        if (correcto) cumplidos++;
    });

    progreso.style.width = valor.length === 0
        ? "0%"
        : `${cumplidos * 20}%`;

    if (valor.length === 0) {
        nivel.textContent = "Sin contraseña";
        nivel.style.color = "#f87171";
        progreso.style.backgroundColor = "#ef4444";
    } else if (cumplidos <= 2) {
        nivel.textContent = "Débil";
        nivel.style.color = "#f87171";
        progreso.style.backgroundColor = "#ef4444";
    } else if (cumplidos < 5) {
        nivel.textContent = "Media";
        nivel.style.color = "#facc15";
        progreso.style.backgroundColor = "#facc15";
    } else {
        nivel.textContent = "Cumple requisitos";
        nivel.style.color = "#22c55e";
        progreso.style.backgroundColor = "#22c55e";
    }
});


const confirmar = document.getElementById("confirmar");

const mensajeConfirmacion = document.getElementById("mensaje-confirmacion");

function verificarCoincidencia() {
    if (confirmar.value === "") {
        mensajeConfirmacion.textContent = "";
        return;
    }

    if (contrasena.value === confirmar.value) {
        mensajeConfirmacion.textContent = "✓ Las contraseñas coinciden";
        mensajeConfirmacion.style.color = "#16a34a";
    } else {
        mensajeConfirmacion.textContent = "✕ Las contraseñas no coinciden";
        mensajeConfirmacion.style.color = "#dc2626";
    }
}

contrasena.addEventListener("input", verificarCoincidencia);
confirmar.addEventListener("input", verificarCoincidencia);


const botonesMostrar = document.querySelectorAll(".mostrar-contrasena");

botonesMostrar.forEach(boton => {
    boton.addEventListener("click", () => {
        const campo = document.getElementById(boton.dataset.campo);

        if (campo.type === "password") {
            campo.type = "text";
            boton.textContent = "Ocultar contraseña";
        } else {
            campo.type = "password";
            boton.textContent = "Mostrar contraseña";
        }
    });
});


const formularioRegistro = document.querySelector("form");

formularioRegistro.addEventListener("submit", (evento) => {
    const claveValida = requisitos.every(requisito =>
        requisito.validar(contrasena.value)
    );

    const coinciden = contrasena.value === confirmar.value;

    if (!claveValida || !coinciden) {
        evento.preventDefault();

        mensajeConfirmacion.textContent = !claveValida
            ? "✕ La contraseña no cumple todos los requisitos."
            : "✕ Las contraseñas no coinciden.";

        mensajeConfirmacion.style.color = "#dc2626";
    }
});
