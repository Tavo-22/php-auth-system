<?php
session_start();

// Si no hay sesión, redirige al login
if (!isset($_SESSION['user_id'])) {
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
</body>
</html>