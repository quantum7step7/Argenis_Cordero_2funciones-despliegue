<?php
function cargarConfiguracionEntorno(): array
{
    $valores = [];
    $archivo = __DIR__ . '/.env';
    if (is_readable($archivo)) {
        foreach (file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
            $linea = trim($linea);
            if ($linea === '' || str_starts_with($linea, '#') || !str_contains($linea, '=')) {
                continue;
            }
            [$clave, $valor] = explode('=', $linea, 2);
            $valores[trim($clave)] = trim($valor);
        }
    }
    return $valores;
}

function conectarBaseDatos(): PDO
{
    $configuracion = cargarConfiguracionEntorno();
    $servidor = $configuracion['DB_HOST'] ?? 'localhost';
    $puerto = $configuracion['DB_PORT'] ?? '3306';
    $baseDatos = $configuracion['DB_NAME'] ?? 'agenda_uno';
    $usuario = $configuracion['DB_USER'] ?? 'root';
    $clave = $configuracion['DB_PASSWORD'] ?? '';

    $cadenaConexion = 'mysql:host=' . $servidor
        . ';port=' . $puerto
        . ';dbname=' . $baseDatos
        . ';charset=utf8mb4';

    return new PDO($cadenaConexion, $usuario, $clave, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
}
