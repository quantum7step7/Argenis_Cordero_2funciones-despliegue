<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';

exigirIngreso();

$soloFavoritos = ($_GET['favoritos'] ?? '') === '1';
$conexion = conectarBaseDatos();
$sql = 'SELECT contactos.id, contactos.nombre, contactos.telefono, contactos.correo,
               contactos.ciudad, contactos.favorito, categorias.nombre AS categoria
        FROM contactos
        INNER JOIN categorias ON categorias.id = contactos.id_categoria
        WHERE contactos.id_usuario = ?';

if ($soloFavoritos) {
    $sql .= ' AND contactos.favorito = 1';
}

$sql .= ' ORDER BY contactos.nombre';
$consulta = $conexion->prepare($sql);
$consulta->execute([$_SESSION['usuario']['id']]);
$contactos = $consulta->fetchAll(PDO::FETCH_ASSOC);
$aviso = $_GET['aviso'] ?? '';
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Mis contactos | Agenda azul</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <header>
        <strong>Agenda azul</strong>
        <a href="salir.php">Cerrar sesión</a>
    </header>

    <main>
        <h1>Hola, <?= escapar($_SESSION['usuario']['nombre']) ?></h1>
        <p><?= escapar($_SESSION['usuario']['correo']) ?></p>
        <p><a class="boton" href="crear_contacto.php">Nuevo contacto</a></p>
        <p class="filtro-favoritos">
            <?php if ($soloFavoritos): ?>
                Mostrando solamente favoritos. <a href="inicio.php">Ver todos</a>
            <?php else: ?>
                <a href="inicio.php?favoritos=1">Ver solamente favoritos</a>
            <?php endif; ?>
        </p>

        <?php if ($aviso): ?>
            <p class="aviso"><?= escapar($aviso) ?></p>
        <?php endif; ?>

        <table>
            <tr>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Ciudad</th>
                <th>Categoría</th>
                <th>Favorito</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($contactos as $contacto): ?>
                <tr>
                    <td><?= escapar($contacto['nombre']) ?></td>
                    <td><?= escapar($contacto['telefono']) ?></td>
                    <td><?= escapar($contacto['correo']) ?></td>
                    <td><?= escapar($contacto['ciudad']) ?></td>
                    <td><?= escapar($contacto['categoria']) ?></td>
                    <td><?= (int) $contacto['favorito'] === 1 ? 'Sí' : 'No' ?></td>
                    <td>
                        <a href="editar_contacto.php?id=<?= (int) $contacto['id'] ?>">Editar</a>
                        <a href="eliminar_contacto.php?id=<?= (int) $contacto['id'] ?>">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if (!$contactos): ?>
            <p><?= $soloFavoritos ? 'No tiene contactos favoritos.' : 'No tiene contactos registrados.' ?></p>
        <?php endif; ?>
    </main>
</body>
</html>
