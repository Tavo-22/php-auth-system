<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';

requirePost();

$body     = getJsonBody();
$nombre   = trim($body['nombre']   ?? '');
$email    = trim($body['email']    ?? '');
$password = trim($body['password'] ?? '');
$confirm  = trim($body['confirm']  ?? '');

// Validaciones
if (!$nombre || !$email || !$password || !$confirm) {
    jsonResponse(false, 'Completa todos los campos.');
}
if (mb_strlen($nombre) < 2) {
    jsonResponse(false, 'El nombre debe tener al menos 2 caracteres.');
}
if (!validEmail($email)) {
    jsonResponse(false, 'El email no es válido.');
}
if (!validPassword($password)) {
    jsonResponse(false, 'La contraseña debe tener mínimo 8 caracteres.');
}
if ($password !== $confirm) {
    jsonResponse(false, 'Las contraseñas no coinciden.');
}

// Verificar si el email ya existe
$db   = getDB();
$stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    jsonResponse(false, 'Ya existe una cuenta con ese email.');
}

// Insertar usuario
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $db->prepare('INSERT INTO users (nombre, email, password_hash) VALUES (?, ?, ?)');
$stmt->execute([$nombre, $email, $hash]);

jsonResponse(true, 'Cuenta creada. Ya puedes iniciar sesión.');