<?php
require_once __DIR__ . '/conexion.php';
require_once __DIR__ . '/sesion.php';

exigirIngreso();

$idContacto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$conexion = conectarBaseDatos();

$buscar = $conexion->prepare(
    'SELECT * FROM contactos WHERE id = ? AND id_usuario = ?'
);
$buscar->execute([$idContacto, $_SESSION['usuario']['id']]);
$contacto = $buscar->fetch(PDO::FETCH_ASSOC);

if (!$contacto) {
    header('Location: inicio.php?aviso=Contacto no disponible.');
    exit;
}

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
        $actualizar = $conexion->prepare(
            'UPDATE contactos
             SET id_categoria = ?, nombre = ?, telefono = ?, correo = ?, ciudad = ?, favorito = ?
             WHERE id = ? AND id_usuario = ?'
        );
        $actualizar->execute([
            $idCategoria,
            $nombre,
            $telefono,
            $correo,
            $ciudad,
            $favorito,
            $idContacto,
            $_SESSION['usuario']['id'],
        ]);
        header('Location: inicio.php?aviso=Contacto actualizado.');
        exit;
    }

    // Si hay error, se conservan los datos que el usuario intento guardar.
    $contacto = [
        'id_categoria' => $idCategoria,
        'nombre' => $nombre,
        'telefono' => $telefono,
        'correo' => $_POST['correo'] ?? '',
        'ciudad' => $ciudad,
        'favorito' => $favorito,
    ];
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Editar contacto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h1>Editar contacto</h1>

        <?php if ($error): ?>
            <p class="error"><?= escapar($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Nombre<input name="nombre" value="<?= escapar($contacto['nombre']) ?>" required></label>
            <label>Teléfono<input name="telefono" value="<?= escapar($contacto['telefono']) ?>" required></label>
            <label>Correo electrónico<input type="email" name="correo" value="<?= escapar($contacto['correo']) ?>" required></label>
            <label>Ciudad<input name="ciudad" value="<?= escapar($contacto['ciudad']) ?>" required></label>
            <label>Categoría
                <select name="id_categoria" required>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= (int) $categoria['id'] ?>" <?= (int) $contacto['id_categoria'] === (int) $categoria['id'] ? 'selected' : '' ?>>
                            <?= escapar($categoria['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label class="campo-favorito">
                <input type="checkbox" name="favorito" value="1" <?= (int) $contacto['favorito'] === 1 ? 'checked' : '' ?>>
                Marcar como favorito
            </label>
            <button>Guardar cambios</button>
        </form>

        <p><a href="inicio.php">Cancelar</a></p>
    </main>
</body>
</html>
