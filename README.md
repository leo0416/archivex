# 🗃️ ArchiveX - Sistema de Gestión de Expedientes

ArchiveX es un sistema integral desarrollado en **PHP Nativo** bajo el patrón de diseño **MVC (Modelo-Vista-Controlador)**. Su objetivo principal es la organización, búsqueda y gestión de expedientes físicos, permitiendo un control preciso sobre su ubicación en estantes y cajuelas.

<p align="center">
  <img src="public/img/favicon.png" alt="ArchiveX Logo" width="120">
</p>

---

## 🛠️ Stack Tecnológico

* **Lenguaje:** PHP 7.4+ / 8.x
* **Base de Datos:** MySQL (PDO para máxima seguridad)
* **Frontend:** HTML5, CSS3, JavaScript (Vanilla JS)
* **Librerías de Terceros:**
    * **Dompdf:** Generación de reportes profesionales en PDF.
    * **Chart.js:** Visualización de estadísticas en el Dashboard.
    * **Font Awesome 5:** Iconografía técnica y de interfaz.

---

## 🚀 Funcionalidades Principales

* **🔐 Autenticación y Roles:** Control de acceso diferenciado para Administradores e Invitados.
* **📊 Dashboard Estadístico:** Panel visual con métricas clave del sistema.
* **🔍 Buscador Avanzado:** Filtros por CI, nombre, núcleos políticos y condecoraciones.
* **📄 Generación de Reportes:** Exportación de fichas de militantes y listados de estantes a PDF.
* **📦 Gestión de Archivo:** CRUD completo de expedientes, estantes y núcleos.
* **🛡️ Sistema de Seguridad:**
    * **Manejo de Logs:** Registro detallado de actividades.
    * **Backups:** Módulo dedicado para respaldos de la base de datos SQL.
    * **Papelera de Reciclaje:** Sistema de eliminación lógica para recuperación de datos.

---

## 📂 Estructura del Proyecto

El sistema sigue una arquitectura de carpetas limpia y organizada:

* **`/app`**: Corazón del sistema. Contiene los Controladores (lógica), Modelos (datos) y archivos de configuración/logs.
* **`/backups`**: Almacenamiento automático de respaldos SQL.
* **`/database`**: Scripts de inicialización de la base de datos.
* **`/public`**: Único punto de acceso público. Contiene el `index.php` (Router), estilos CSS, JS y recursos multimedia.
* **`/vendor`**: Dependencias gestionadas (Dompdf, Autoload).
* **`/views`**: Plantillas divididas por módulos y layouts (Main/Invitado).

---

## 🔧 Configuración e Instalación

1.  **Requisitos:** Servidor local (XAMPP, WAMP o Laragon) con PHP 7.4 o superior.
2.  **Base de Datos:** * Crea una base de datos llamada `archivex_db`.
    * Importa el archivo `database/archivex_db(vacio).sql`.
3.  **Configuración:** * Ajusta las credenciales de conexión en `app/config/config.php`.
4.  **Acceso:**
    * Apunta tu servidor local a la carpeta del proyecto y accede vía: `http://localhost/archivex`.

---

## 📝 Información de Desarrollo

- **Arquitectura:** MVC Manual.
- **Seguridad:** Implementación de sentencias preparadas (PDO) y manejo de sesiones seguras.
- **Mantenimiento:** El sistema incluye un controlador de ayuda y manual de usuario integrado.

---
<p align="center">
  Diseñado para la eficiencia operativa y el control documental.
</p>