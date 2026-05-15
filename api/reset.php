<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';

requirePost();

$body     = getJsonBody();
$token    = trim($body['token']    ?? '');
$password = trim($body['password'] ?? '');
$confirm  = trim($body['confirm']  ?? '');

// Validaciones
if (!$token || !$password || !$confirm) {
    jsonResponse(false, 'Faltan datos requeridos.');
}
if (!validPassword($password)) {
    jsonResponse(false, 'La contraseña debe tener mínimo 8 caracteres.');
}
if ($password !== $confirm) {
    jsonResponse(false, 'Las contraseñas no coinciden.');
}

// Verificar token válido y no expirado
$db   = getDB();
$stmt = $db->prepare(
    'SELECT email FROM password_resets
     WHERE token = ? AND usado = 0 AND expira_en > NOW()
     LIMIT 1'
);
$stmt->execute([$token]);
$reset = $stmt->fetch();

if (!$reset) {
    jsonResponse(false, 'El enlace es inválido o ha expirado.');
}

// Actualizar contraseña
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
$db->prepare('UPDATE users SET password_hash = ? WHERE email = ?')
   ->execute([$hash, $reset['email']]);

// Marcar token como usado
$db->prepare('UPDATE password_resets SET usado = 1 WHERE token = ?')
   ->execute([$token]);

jsonResponse(true, 'Contraseña actualizada. Ya puedes iniciar sesión.');