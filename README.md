# Agenda Argenis

## 1. URL pública

http://agenda-personal-argenis-cordero.wuaze.com/

## 2. Capturas de pantalla

Las capturas del despliegue están incluidas en la carpeta `capturas pantalla` del proyecto:

### Panel de MySQL

![Panel de MySQL](<capturas pantalla/01_panel_mysql_miguel.png>)

### Formulario de importación

![Formulario de importación](<capturas pantalla/03_formulario_importacion_miguel.png>)

### Importación exitosa

![Importación exitosa](<capturas pantalla/04_importacion_exitosa_miguel.png>)

### Archivos subidos

![Archivos subidos](<capturas pantalla/proyecto archivos subidos.jpg>)

## 3. Tecnologías utilizadas

- PHP 8.
- MySQL o MariaDB.
- Apache mediante XAMPP.
- HTML5 y CSS3.
- PDO y consultas preparadas.
- `password_hash()` y `password_verify()`.

## 4. Instrucciones para instalar el proyecto localmente

1. Instale XAMPP en Windows.
2. Copie `Agenda_Argenis-main` dentro de `C:\xampp\htdocs\`.
3. Inicie **Apache** y **MySQL** desde el panel de XAMPP.
4. Abra `http://localhost/phpmyadmin`.
5. Cree una base de datos llamada `agenda_uno`.
6. Use **Importar** para cargar el archivo `agenda.sql` del proyecto.
7. En `conexion.php`, configure servidor `localhost`, usuario `root`, contraseña vacía y base `agenda_uno`.
8. Abra `http://localhost/Agenda_Argenis-main/registro.php` para registrar un usuario e iniciar sesión.

## 5. Detalle de las mejoras respecto a la versión anterior

- Se añadieron categorías para clasificar los contactos: Familia, Amigos, Trabajo, Universidad y Otro.
- Se incorporó el campo de contactos favoritos y un filtro para mostrar únicamente los favoritos.
- Se corrigieron las rutas PHP y se reforzó la validación del usuario propietario mediante `id_usuario`.
