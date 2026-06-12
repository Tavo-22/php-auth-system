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

// Generar token de sesión único
$token  = generateToken();
$expira = date('Y-m-d H:i:s', strtotime('+8 hours'));

// Guardar token en BD — invalida cualquier sesión anterior
$db->prepare('UPDATE users SET session_token = ?, session_expira = ? WHERE id = ?')
   ->execute([$token, $expira, $user['id']]);

// Iniciar sesión PHP
session_start();
session_regenerate_id(true);
$_SESSION['user_id']       = $user['id'];
$_SESSION['nombre']        = $user['nombre'];
$_SESSION['session_token'] = $token;

jsonResponse(true, '¡Bienvenido!', [
    'nombre'   => $user['nombre'],
    'redirect' => APP_URL . '/dashboard.php',
]);