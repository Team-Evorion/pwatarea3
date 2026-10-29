
# 💻 Tarea Práctica 3 — Programación Web Avanzada

**Universidad Bolivariana del Ecuador (UBE)**  
**Asignatura:** Programación Web Avanzada  
**Unidad 2:** Programación BackEnd y FrontEnd  
**Equipo:** Team Evorion  
**Repositorio:** `pwatarea3`

---

## 📖 Descripción del proyecto

Este repositorio contiene el desarrollo de la **Tarea Práctica 3 de Programación Web Avanzada**, cuyo objetivo es fortalecer las habilidades de programación y resolución de problemas mediante la implementación de aplicaciones web utilizando HTML, CSS, JavaScript y PHP.

El proyecto reúne seis ejercicios de complejidad baja y media, enfocados en la creación de interfaces interactivas, validación de formularios, procesamiento de información y desarrollo de funcionalidades del lado del servidor.

Cada ejercicio se organiza en su propio directorio para facilitar el desarrollo, mantenimiento y trabajo colaborativo.

## 🌐 Enlaces del proyecto

**Repositorio GitHub:**  
https://github.com/Team-Evorion/pwatarea3

**Sitio web publicado:**  
🚧 Pendiente de publicación.

> Una vez implementados todos los ejercicios y configurado el hosting, se incorporará aquí la URL del sitio web funcional.

---

## 🎯 Objetivo

Desarrollar habilidades de programación web mediante la aplicación de conceptos fundamentales de HTML, CSS, JavaScript y PHP, integrando funcionalidades de FrontEnd y BackEnd en aplicaciones web.

## 🛠️ Tecnologías utilizadas

| Tecnología | Aplicación |
|---|---|
| HTML5 | Estructura y contenido de las páginas web |
| CSS3 | Diseño, estilos y presentación visual |
| JavaScript | Interactividad y validaciones del lado del cliente |
| PHP | Procesamiento de datos y lógica del servidor |
| MySQL | Almacenamiento y gestión de usuarios |
| XAMPP | Entorno de desarrollo local |
| Git y GitHub | Control de versiones y colaboración |
| Visual Studio Code | Desarrollo y edición del código |

---

## 📚 Ejercicios de la tarea

### Ejercicio 1 — Menú desplegable

**Estado:** Incorporado al repositorio.

Desarrollo de un menú de navegación interactivo utilizando HTML, CSS y JavaScript.

**Requerimientos:**
- Crear un menú de navegación.
- Incorporar opciones desplegables.
- Mantener ocultas las opciones secundarias inicialmente.
- Mostrar las opciones adicionales al hacer clic en el elemento principal.

**Directorio:** `ej01/`

### Ejercicio 2 — Formulario de contacto

**Estado:** Pendiente de incorporación.

Creación de un formulario de contacto con validación mediante JavaScript.

**Requerimientos:**
- Campo para nombre.
- Campo para correo electrónico.
- Campo para mensaje.
- Botón para enviar el formulario.
- Validación de campos obligatorios y datos ingresados.

**Directorio previsto:** `ej02/`

### Ejercicio 3 — Contador de visitas en PHP

**Estado:** Pendiente de incorporación.

Implementación de un contador de visitas utilizando PHP.

**Requerimientos:**
- Crear un archivo `index.php`.
- Utilizar una variable global para almacenar el número de visitas.
- Actualizar el contador cuando el usuario visite el sitio.
- Mostrar el número de visitas en pantalla.

**Directorio previsto:** `ej03/`

### Ejercicio 4 — Juego de adivinanzas

**Estado:** Pendiente de incorporación.

Desarrollo de un juego interactivo de adivinanzas utilizando PHP y JavaScript.

**Requerimientos:**
- Generar un número aleatorio.
- Permitir al usuario introducir una respuesta.
- Comprobar si el número ingresado es correcto.
- Mostrar un mensaje de éxito cuando acierte.
- Permitir nuevos intentos cuando la respuesta sea incorrecta.

**Directorio previsto:** `ej04/`

### Ejercicio 5 — Galería de imágenes

**Estado:** Pendiente de incorporación.

Creación de una galería de imágenes utilizando HTML, CSS y PHP.

**Requerimientos:**
- Almacenar imágenes en un directorio.
- Crear una lista de imágenes en HTML.
- Utilizar PHP para cargar las imágenes.
- Aplicar estilos CSS para organizar y presentar la galería.

**Directorio previsto:** `ej05/`

### Ejercicio 6 — Sistema de inicio de sesión

**Estado:** Incorporado al repositorio.

Desarrollo de un sistema de registro, autenticación y gestión de sesiones utilizando PHP y MySQL.

**Funcionalidades implementadas:**
- Registro de nuevos usuarios.
- Validación de formularios con JavaScript.
- Comprobación de contraseñas y confirmación.
- Almacenamiento de contraseñas mediante `password_hash()`.
- Verificación de contraseñas mediante `password_verify()`.
- Consultas preparadas mediante PDO.
- Inicio de sesión de usuarios registrados.
- Control de acceso mediante sesiones PHP.
- Panel de usuario autenticado.
- Cierre de sesión.
- Mensajes de error y confirmación.
- Opción para mostrar y ocultar contraseñas.

**Directorio:** `ej06/`

---

## 📁 Estructura del proyecto

La estructura se completará progresivamente conforme los integrantes incorporen sus ejercicios.

```text
pwatarea3/
│
├── Imagenes/
│
├── ej01/
│   ├── css/
│   ├── js/
│   └── ej01.html
│
├── ej02/                    # Pendiente
├── ej03/                    # Pendiente
├── ej04/                    # Pendiente
├── ej05/                    # Pendiente
│
├── ej06/
│   ├── conexion.php
│   ├── estilos.css
│   ├── guardar_registro.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── mostrarContrasena.js
│   ├── panel.php
│   ├── registro.php
│   └── validadorRegistro.js
│
├── estilos.css
├── index.php
└── README.md
```

**Nota:** Los directorios pendientes son parte de la estructura prevista y todavía no están disponibles en la rama principal.

---

## ⚙️ Instalación y ejecución local

### Requisitos

Para ejecutar el proyecto se recomienda contar con:

- XAMPP con Apache y MySQL.
- PHP con soporte para PDO MySQL.
- Navegador web actualizado.
- Visual Studio Code u otro editor de código.

### 1. Clonar el repositorio

```bash
git clone https://github.com/Team-Evorion/pwatarea3.git
```

### 2. Ubicar el proyecto

Colocar el repositorio dentro del directorio `htdocs` de XAMPP.

Ejemplo:

```text
C:\xampp\htdocs\pwatarea3
```

### 3. Iniciar los servicios

Desde el panel de control de XAMPP, iniciar:

- Apache.
- MySQL.

### 4. Configurar la base de datos del ejercicio 6

Acceder a phpMyAdmin:

```text
http://localhost/phpmyadmin
```

Crear una base de datos llamada:

```sql
CREATE DATABASE IF NOT EXISTS pwatarea3
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE pwatarea3;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(255) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL
);
```

Esta estructura permite almacenar usuarios y contraseñas protegidas mediante hash.

No se incluyen contraseñas reales ni registros personales en este script.

### 5. Configurar la conexión

El archivo `ej06/conexion.php` contiene los parámetros de conexión utilizados durante el desarrollo local.

La configuración predeterminada de XAMPP utiliza:

```text
Servidor: localhost
Usuario: root
Contraseña: vacía
Base de datos: pwatarea3
```

Si se utiliza otra configuración de MySQL, deberán ajustarse los parámetros de conexión.

**Importante:** En un entorno de producción deben utilizarse credenciales privadas y un usuario de base de datos con permisos limitados.

### 6. Abrir el proyecto

Desde el navegador:

```text
http://localhost/pwatarea3/
```

Para acceder directamente al ejercicio 6:

```text
http://localhost/pwatarea3/ej06/
```

Si el repositorio se encuentra dentro de otra subcarpeta de `htdocs`, se deberá ajustar la URL.

---

## 🔐 Consideraciones de seguridad

El ejercicio 6 implementa mecanismos básicos de seguridad para la autenticación de usuarios:

- Las contraseñas se almacenan mediante hash, no como texto plano.
- Se utiliza `password_verify()` para comprobar las credenciales.
- Las consultas a la base de datos utilizan sentencias preparadas.
- Las sesiones PHP permiten controlar el acceso al panel.
- Las validaciones del cliente se complementan con validaciones del servidor.

Estas medidas forman parte de una implementación académica y no sustituyen una auditoría de seguridad para su uso en producción.

---

## 👥 Trabajo colaborativo

El proyecto se desarrolla de manera grupal mediante la organización **Team Evorion**.

Cada integrante es responsable de implementar y mantener el ejercicio asignado.

Para facilitar la colaboración se recomienda:

1. Actualizar el repositorio antes de comenzar a trabajar.
2. Crear una rama independiente para los cambios.
3. Realizar commits descriptivos.
4. Subir la rama correspondiente a GitHub.
5. Crear un Pull Request para revisar e integrar los cambios.
6. Evitar modificar archivos de otros integrantes sin coordinación.

De esta manera se conserva el historial de contribuciones y se reducen los conflictos entre archivos.

---

## 🚀 Publicación del sitio web

**Estado:** Pendiente.

Una vez incorporados y verificados los seis ejercicios, el proyecto se desplegará en un servicio de hosting compatible con PHP y MySQL.

**URL del sitio web:**

`Pendiente de agregar`

**Nota:** GitHub Pages no ejecuta código PHP ni proporciona una base de datos MySQL. Por ello, la publicación funcional requiere un servidor compatible con las tecnologías utilizadas.

---

## 📋 Estado general del proyecto

| Ejercicio | Descripción | Estado |
|---|---|---|
| 01 | Menú desplegable | Incorporado |
| 02 | Formulario de contacto | Pendiente |
| 03 | Contador de visitas | Pendiente |
| 04 | Juego de adivinanzas | Pendiente |
| 05 | Galería de imágenes | Pendiente |
| 06 | Sistema de login | Incorporado |

**Pendientes generales:**
- Incorporar los ejercicios restantes.
- Verificar los enlaces del menú principal.
- Comprobar el funcionamiento conjunto de los ejercicios.
- Publicar el sitio web en un hosting compatible.
- Añadir la URL definitiva del sitio web.
- Realizar las pruebas finales antes de la entrega.

---

## 📖 Referencia académica

La actividad se desarrolla conforme al documento **Tarea Práctica 3 — Programación Web Avanzada**, Unidad 2, de la Universidad Bolivariana del Ecuador.

Como referencia bibliográfica, la consigna incluye:

Nixon, R. (2014). *Learning PHP, MySQL, JavaScript, CSS & HTML5* (3.ª ed.).

---

## 🏫 Información académica

**Universidad:** Universidad Bolivariana del Ecuador (UBE)  
**Asignatura:** Programación Web Avanzada  
**Actividad:** Tarea Práctica 3  
**Unidad:** Programación BackEnd y FrontEnd  
**Equipo de desarrollo:** Team Evorion  

---

**Desarrollado por Team Evorion — Universidad Bolivariana del Ecuador.**
