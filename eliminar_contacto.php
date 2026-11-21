<?php
require 'conexion.php';
require 'sesion.php';

exigirIngreso();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$buscar = conectarBaseDatos()->prepare(
    'SELECT nombre FROM contactos WHERE id = ? AND id_usuario = ?'
);
$buscar->execute([$id, $_SESSION['usuario']['id']]);
$contacto = $buscar->fetch(PDO::FETCH_ASSOC);

if (!$contacto) {
    header('Location: inicio.php?aviso=Contacto no disponible.');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrar = conectarBaseDatos()->prepare(
        'DELETE FROM contactos WHERE id = ? AND id_usuario = ?'
    );
    $borrar->execute([$id, $_SESSION['usuario']['id']]);
    header('Location: inicio.php?aviso=Contacto eliminado.');
    exit;
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Eliminar contacto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h1>Eliminar contacto</h1>
        <p>¿Desea eliminar a <?= escapar($contacto['nombre']) ?>?</p>

        <form method="post">
            <button>Eliminar</button>
        </form>

        <p><a href="inicio.php">Cancelar</a></p>
    </main>
</body>
</html>
