<?php
session_start();

function exigirIngreso(): void
{
    if (empty($_SESSION['usuario'])) {
        header('Location: ingreso.php');
        exit;
    }
}

function escapar(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
