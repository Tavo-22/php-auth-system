<?php
session_start();
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';

if (!isset($_SESSION['user_id'])) {
    jsonResponse(false, 'Sin sesión.');
}

$db   = getDB();
$stmt = $db->prepare(
    'SELECT session_token FROM users 
     WHERE id = ? AND session_token = ? AND session_expira > NOW() 
     LIMIT 1'
);
$stmt->execute([$_SESSION['user_id'], $_SESSION['session_token']]);

if (!$stmt->fetch()) {
    session_destroy();
    jsonResponse(false, 'Sesión inválida.');
}

jsonResponse(true, 'Sesión activa.');