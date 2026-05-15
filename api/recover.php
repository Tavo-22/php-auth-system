<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';
require_once __DIR__ . '/../shared/mailer.php';

requirePost();

$body  = getJsonBody();
$email = trim($body['email'] ?? '');

// Validación
if (!$email || !validEmail($email)) {
    jsonResponse(false, 'Ingresa un email válido.');
}

$db   = getDB();
$stmt = $db->prepare('SELECT id, nombre FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$user = $stmt->fetch();

// Mismo mensaje exista o no el email (seguridad)
if (!$user) {
    jsonResponse(true, 'Si el email está registrado recibirás un enlace en breve.');
}

// Invalidar tokens anteriores
$db->prepare('UPDATE password_resets SET usado = 1 WHERE email = ?')
   ->execute([$email]);

// Crear nuevo token
$token  = generateToken();
$expira = date('Y-m-d H:i:s', strtotime('+60 minutes'));

$db->prepare('INSERT INTO password_resets (email, token, expira_en) VALUES (?, ?, ?)')
   ->execute([$email, $token, $expira]);

// Enviar email
$link    = 'http://localhost:3000/reset.html?token=' . $token;
$enviado = sendResetEmail($email, $user['nombre'], $link);

if (!$enviado) {
    jsonResponse(false, 'No pudimos enviar el correo. Intenta más tarde.');
}

jsonResponse(true, 'Si el email está registrado recibirás un enlace en breve.');