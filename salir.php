<?php
require 'sesion.php';

$_SESSION = [];
session_destroy();

header('Location: ingreso.php?aviso=Sesión cerrada.');
