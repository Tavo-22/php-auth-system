<?php
require_once __DIR__ . '/../shared/helpers.php';
require_once __DIR__ . '/../shared/db.php';

requirePost();

if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] !== UPLOAD_ERR_OK) {
    jsonResponse(false, 'No se recibió ningún archivo válido.');
}

$tmpPath = $_FILES['archivo']['tmp_name'];
$handle  = fopen($tmpPath, 'r');

if (!$handle) {
    jsonResponse(false, 'No se pudo leer el archivo.');
}

$db = getDB();

// Saltar cabecera
fgetcsv($handle, 0, ";");

$insertados = 0;
$omitidos   = 0;
$detalle    = [];

$stmtCheck  = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmtInsert = $db->prepare('INSERT INTO users (nombre, email, password_hash) VALUES (?, ?, ?)');

while (($fila = fgetcsv($handle, 0, ";")) !== false) {
    if (count($fila) < 3) {
        continue;
    }

    [$nombre, $email, $password] = $fila;

    $nombre   = trim($nombre);
    $email    = trim($email);
    $password = trim($password);

    if (!$nombre || !$email || !$password) {
        $omitidos++;
        $detalle[] = "⚠️ Fila incompleta, omitida";
        continue;
    }

    if (!validEmail($email)) {
        $omitidos++;
        $detalle[] = "⚠️ Email inválido: $email";
        continue;
    }

    // Verificar si ya existe (evita duplicados)
    $stmtCheck->execute([$email]);
    if ($stmtCheck->fetch()) {
        $omitidos++;
        $detalle[] = "⏭️ Ya existe, omitido: $email";
        continue;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmtInsert->execute([$nombre, $email, $hash]);

    $insertados++;
    $detalle[] = "✅ Insertado: $email";
}

fclose($handle);

jsonResponse(true, "Proceso terminado. Insertados: $insertados | Omitidos: $omitidos", [
    'detalle' => $detalle,
]);