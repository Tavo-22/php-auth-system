// ── Utilidades ───────────────────────────────────────────────

function mostrarAlerta(msg, tipo) {
  const alerta = document.getElementById('alerta');
  alerta.textContent = msg;
  alerta.className = `alerta ${tipo}`;
}

function setLoading(btn, loading) {
  btn.disabled = loading;
  btn.textContent = loading ? 'Cargando...' : btn.dataset.texto;
}

// ── LOGIN ────────────────────────────────────────────────────

const btnLogin = document.getElementById('btn-login');

if (btnLogin) {
  btnLogin.dataset.texto = btnLogin.textContent;

  btnLogin.addEventListener('click', async () => {
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();

    if (!email || !password) {
      mostrarAlerta('Completa todos los campos.', 'error');
      return;
    }

    setLoading(btnLogin, true);

    try {
      const res = await axios.post('api/login.php', { email, password });

      if (res.data.ok) {
        mostrarAlerta(res.data.msg, 'success');
        setTimeout(() => window.location.href = res.data.redirect, 1000);
      } else {
        mostrarAlerta(res.data.msg, 'error');
      }

    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    } finally {
      setLoading(btnLogin, false);
    }
  });
}

// ── REGISTRO ─────────────────────────────────────────────────

const btnRegister = document.getElementById('btn-register');

if (btnRegister) {
  btnRegister.dataset.texto = btnRegister.textContent;

  btnRegister.addEventListener('click', async () => {
    const nombre   = document.getElementById('nombre').value.trim();
    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value.trim();
    const confirm  = document.getElementById('confirm').value.trim();

    if (!nombre || !email || !password || !confirm) {
      mostrarAlerta('Completa todos los campos.', 'error');
      return;
    }

    if (password !== confirm) {
      mostrarAlerta('Las contraseñas no coinciden.', 'error');
      return;
    }

    setLoading(btnRegister, true);

    try {
      const res = await axios.post('api/register.php', { nombre, email, password, confirm });

      if (res.data.ok) {
        mostrarAlerta(res.data.msg, 'success');
        setTimeout(() => window.location.href = 'index.html', 1500);
      } else {
        mostrarAlerta(res.data.msg, 'error');
      }

    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    } finally {
      setLoading(btnRegister, false);
    }
  });
}

// ── CARGA MASIVA CSV ─────────────────────────────────────────

const btnCargar = document.getElementById('btn-cargar');

if (btnCargar) {
  btnCargar.dataset.texto = btnCargar.textContent;

  btnCargar.addEventListener('click', async () => {
    const input = document.getElementById('archivo-csv');
    const resultado = document.getElementById('resultado');

    if (!input.files.length) {
      mostrarAlerta('Selecciona un archivo CSV.', 'error');
      return;
    }

    const formData = new FormData();
    formData.append('archivo', input.files[0]);

    setLoading(btnCargar, true);
    resultado.textContent = '';

    try {
      const res = await axios.post('api/import_users.php', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });

      mostrarAlerta(res.data.msg, res.data.ok ? 'success' : 'error');

      if (res.data.detalle) {
        resultado.textContent = res.data.detalle.join('\n');
      }

    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    } finally {
      setLoading(btnCargar, false);
    }
  });
}

// ── RECOVER ──────────────────────────────────────────────────

/*const btnRecover = document.getElementById('btn-recover');

if (btnRecover) {
  btnRecover.dataset.texto = btnRecover.textContent;

  btnRecover.addEventListener('click', async () => {
    const email = document.getElementById('email').value.trim();

    if (!email) {
      mostrarAlerta('Ingresa tu email.', 'error');
      return;
    }

    setLoading(btnRecover, true);

    try {
      const res = await axios.post('api/recover.php', { email });
      mostrarAlerta(res.data.msg, res.data.ok ? 'success' : 'error');

    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    } finally {
      setLoading(btnRecover, false);
    }
  });
}

// ── RESET ─────────────────────────────────────────────────────

const btnReset = document.getElementById('btn-reset');

if (btnReset) {
  btnReset.dataset.texto = btnReset.textContent;

  // Extraer token de la URL
  const token = new URLSearchParams(window.location.search).get('token');

  if (!token) {
    mostrarAlerta('Enlace inválido. Solicita uno nuevo.', 'error');
    btnReset.disabled = true;
  }

  btnReset.addEventListener('click', async () => {
    const password = document.getElementById('password').value.trim();
    const confirm  = document.getElementById('confirm').value.trim();

    if (!password || !confirm) {
      mostrarAlerta('Completa todos los campos.', 'error');
      return;
    }

    if (password !== confirm) {
      mostrarAlerta('Las contraseñas no coinciden.', 'error');
      return;
    }

    setLoading(btnReset, true);

    try {
      const res = await axios.post('api/reset.php', { token, password, confirm });

      if (res.data.ok) {
        mostrarAlerta(res.data.msg, 'success');
        setTimeout(() => window.location.href = 'index.html', 1500);
      } else {
        mostrarAlerta(res.data.msg, 'error');
      }

    } catch (err) {
      mostrarAlerta('Error de conexión. Intenta de nuevo.', 'error');
    } finally {
      setLoading(btnReset, false);
    }
  });
}*/