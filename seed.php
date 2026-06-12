<?php
require_once __DIR__ . '/shared/db.php';

$usuarios = [
    ['nombre' => 'Carlos López',    'email' => 'carlos@demo.com',   'password' => 'password01'],
    ['nombre' => 'Ana Martínez',    'email' => 'ana@demo.com',      'password' => 'password02'],
    ['nombre' => 'Luis García',     'email' => 'luis@demo.com',     'password' => 'password03'],
    ['nombre' => 'María Torres',    'email' => 'maria@demo.com',    'password' => 'password04'],
    ['nombre' => 'Jorge Ramírez',   'email' => 'jorge@demo.com',    'password' => 'password05'],
    ['nombre' => 'Laura Sánchez',   'email' => 'laura@demo.com',    'password' => 'password06'],
    ['nombre' => 'Pedro Gómez',     'email' => 'pedro@demo.com',    'password' => 'password07'],
    ['nombre' => 'Sofía Díaz',      'email' => 'sofia@demo.com',    'password' => 'password08'],
    ['nombre' => 'Andrés Herrera',  'email' => 'andres@demo.com',   'password' => 'password09'],
    ['nombre' => 'Valentina Cruz',  'email' => 'valentina@demo.com','password' => 'password10'],
];

$db   = getDB();
$stmt = $db->prepare('INSERT INTO users (nombre, email, password_hash) VALUES (?, ?, ?)');

foreach ($usuarios as $u) {
    $hash = password_hash($u['password'], PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt->execute([$u['nombre'], $u['email'], $hash]);
    echo "✅ Creado: {$u['email']}\n";
}

echo "\nListo. 10 usuarios insertados.\n";