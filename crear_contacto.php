<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';

exigirIngreso();

$conexion = conectarBaseDatos();
$categorias = $conexion->query('SELECT id, nombre FROM categorias ORDER BY nombre')->fetchAll(PDO::FETCH_ASSOC);
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_VALIDATE_EMAIL);
    $ciudad = trim($_POST['ciudad'] ?? '');
    $idCategoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);
    $favorito = isset($_POST['favorito']) ? 1 : 0;

    $categoriaExiste = false;
    if ($idCategoria) {
        $consultaCategoria = $conexion->prepare('SELECT id FROM categorias WHERE id = ?');
        $consultaCategoria->execute([$idCategoria]);
        $categoriaExiste = (bool) $consultaCategoria->fetch();
    }

    if ($nombre === '' || $telefono === '' || !$correo || $ciudad === '' || !$categoriaExiste) {
        $error = 'Complete todos los campos y seleccione una categoria valida.';
    } else {
        $guardar = $conexion->prepare(
            'INSERT INTO contactos (id_usuario, id_categoria, nombre, telefono, correo, ciudad, favorito)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $guardar->execute([
            $_SESSION['usuario']['id'],
            $idCategoria,
            $nombre,
            $telefono,
            $correo,
            $ciudad,
            $favorito,
        ]);
        header('Location: inicio.php?aviso=Contacto creado.');
        exit;
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nuevo contacto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h1>Nuevo contacto</h1>

        <?php if ($error): ?>
            <p class="error"><?= escapar($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Nombre<input name="nombre" required></label>
            <label>Teléfono<input name="telefono" required></label>
            <label>Correo electrónico<input type="email" name="correo" required></label>
            <label>Ciudad<input name="ciudad" required></label>
            <label>Categoría
                <select name="id_categoria" required>
                    <option value="">Seleccione una categoría</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= (int) $categoria['id'] ?>">
                            <?= escapar($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="campo-favorito">
                <input type="checkbox" name="favorito" value="1"> Marcar como favorito
            </label>
            <button>Guardar</button>
        </form>

        <p><a href="inicio.php">Volver</a></p>
    </main>
</body>
</html>
