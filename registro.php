<?php
require 'conexion.php';
require 'sesion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_VALIDATE_EMAIL);
    $clave = $_POST['clave'] ?? '';

    if ($nombre === '' || !$correo || $clave === '') {
        $error = 'Complete los datos correctamente.';
    } else {
        $consulta = conectarBaseDatos()->prepare(
            'SELECT id FROM usuarios WHERE correo = ?'
        );
        $consulta->execute([$correo]);

        if ($consulta->fetch()) {
            $error = 'El correo ya está registrado.';
        } else {
            $guardar = conectarBaseDatos()->prepare(
                'INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)'
            );
            $guardar->execute([
                $nombre,
                $correo,
                password_hash($clave, PASSWORD_DEFAULT),
            ]);

            header('Location: ingreso.php?aviso=Registro realizado. Inicie sesión.');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Registro | Agenda azul</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <main>
        <h1>Agenda azul</h1>
        <h2>Crear cuenta</h2>

        <?php if ($error): ?>
            <p class="error"><?= escapar($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>
                Nombre completo
                <input name="nombre" required>
            </label>

            <label>
                Correo electrónico
                <input type="email" name="correo" required>
            </label>

            <label>
                Contraseña
                <input type="password" name="clave" required>
            </label>

            <button>Registrarme</button>
        </form>

        <p><a href="ingreso.php">Ya tengo una cuenta</a></p>
    </main>
</body>
</html>
