<?php
require 'conexion.php';
require 'sesion.php';

if (!empty($_SESSION['usuario'])) {
    header('Location: inicio.php');
    exit;
}

$error = '';
$aviso = $_GET['aviso'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'] ?? '';
    $usuario = null;

    if ($correo) {
        $consulta = conectarBaseDatos()->prepare(
            'SELECT id, nombre, correo, password FROM usuarios WHERE correo = ?'
        );
        $consulta->execute([$correo]);
        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    }

    if (!empty($usuario) && password_verify($clave, $usuario['password'])) {
        session_regenerate_id(true);
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'correo' => $usuario['correo'],
        ];
        header('Location: inicio.php');
        exit;
    }

    $error = 'Correo o contraseña incorrectos.';
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ingreso | Agenda Argenis</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h1>Agenda Argenis</h1>
        <h2>Iniciar sesión</h2>

        <?php if ($aviso): ?>
            <p class="aviso"><?= escapar($aviso) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= escapar($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>
                Correo electrónico
                <input type="email" name="correo" required>
            </label>

            <label>
                Contraseña
                <input type="password" name="clave" required>
            </label>

            <button>Ingresar</button>
        </form>

        <p><a href="registro.php">Crear una cuenta</a></p>
    </main>
</body>
</html>
