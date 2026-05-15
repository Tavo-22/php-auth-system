<?php
require_once __DIR__ . '/config.php';

/** Devuelve JSON y termina la ejecución */
function jsonResponse(bool $ok, string $msg, array $data = []): void
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['ok' => $ok, 'msg' => $msg], $data));
    exit;
}

/** Verifica que la petición sea POST */
function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        jsonResponse(false, 'Método no permitido.');
    }
}

/** Lee el body JSON que envía Axios */
function getJsonBody(): array
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

/** Valida formato de email */
function validEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/** Contraseña mínimo 8 caracteres */
function validPassword(string $pass): bool
{
    return mb_strlen($pass) >= 8;
}

/** Genera token seguro de 64 caracteres */
function generateToken(): string
{
    return bin2hex(random_bytes(32));
}