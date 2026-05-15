<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';

requirePost();

$body     = getJsonBody();
$email    = trim($body['email']    ?? '');
$password = trim($body['password'] ?? '');

// Validaciones
if (!$email || !$password) {
    jsonResponse(false, 'Completa todos los campos.');
}
if (!validEmail($email)) {
    jsonResponse(false, 'El email no es válido.');
}

// Buscar usuario
$db   = getDB();
$stmt = $db->prepare('SELECT id, nombre, password_hash FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verificar contraseña
if (!$user || !password_verify($password, $user['password_hash'])) {
    jsonResponse(false, 'Credenciales incorrectas.');
}

// Iniciar sesión
session_start();
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['nombre']  = $user['nombre'];

jsonResponse(true, '¡Bienvenido!', [
    'nombre'   => $user['nombre'],
    'redirect' => APP_URL . '/dashboard.php',
]);