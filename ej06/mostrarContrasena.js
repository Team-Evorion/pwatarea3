
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
