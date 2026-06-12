<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit;
}

require_once __DIR__ . '/shared/db.php';

$db   = getDB();
$stmt = $db->prepare(
  'SELECT session_token FROM users 
     WHERE id = ? AND session_token = ? AND session_expira > NOW() 
     LIMIT 1'
);
$stmt->execute([$_SESSION['user_id'], $_SESSION['session_token']]);

if (!$stmt->fetch()) {
  session_destroy();
  header('Location: index.html');
  exit;
}

$nombre = $_SESSION['nombre'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="card" style="max-width: 500px; text-align: center; gap: 20px;">

    <div class="avatar"><?= strtoupper(substr($nombre, 0, 1)) ?></div>

    <div>
      <h2>¡Hola, <?= htmlspecialchars($nombre) ?>! 👋</h2>
      <p style="color: #888; font-size: 14px; margin-top: 8px;">
        Iniciaste sesión correctamente.
      </p>
    </div>

    <div class="info-box">
      <span>✅ Sesión activa</span>
    </div>

    <button id="btn-logout">Cerrar sesión</button>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    document.getElementById('btn-logout').addEventListener('click', async () => {
      await axios.post('api/logout.php');
      window.location.href = 'index.html';
    });
  </script>
  <script>
    // Verificar sesión cada 5 segundos
    setInterval(async () => {
      const res = await axios.post('api/check_session.php');
      if (!res.data.ok) {
        window.location.href = 'index.html';
      }
    }, 5000);
  </script>
</body>

</html>