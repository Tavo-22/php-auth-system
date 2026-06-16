# 🔐 php-auth-system (v2)

Sistema de autenticación en PHP puro con login, registro y carga masiva de usuarios por CSV.

> **Nota de esta versión:** la recuperación de contraseña por email fue deshabilitada (código comentado, no eliminado). Se priorizó un sistema de carga masiva de usuarios vía CSV para facilitar la gestión sin tocar código.

## Tecnologías
- PHP 8 puro · MySQL · HTML · CSS · JavaScript · Axios

## Funcionalidades
- Login con sesión única por usuario (un solo dispositivo activo a la vez)
- Registro de usuarios
- Verificación periódica de sesión (`check_session.php`) — si alguien inicia sesión en otro dispositivo, el primero se cierra automáticamente
- Protección de `dashboard.php` contra acceso directo sin sesión iniciada
- Carga masiva de usuarios mediante archivo CSV, sin duplicar usuarios existentes

## Base de datos

```sql
CREATE DATABASE IF NOT EXISTS auth_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE auth_db;

CREATE TABLE IF NOT EXISTS users (
  id              INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  nombre          VARCHAR(100)  NOT NULL,
  email           VARCHAR(180)  NOT NULL,
  password_hash   VARCHAR(255)  NOT NULL,
  session_token   VARCHAR(64)   NULL,
  session_expira  DATETIME      NULL,
  creado_en       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

| Columna | Tipo | Descripción |
|---|---|---|
| `id` | INT (PK) | Identificador único |
| `nombre` | VARCHAR(100) | Nombre del usuario |
| `email` | VARCHAR(180), UNIQUE | Correo (usado para login) |
| `password_hash` | VARCHAR(255) | Contraseña cifrada con bcrypt |
| `session_token` | VARCHAR(64), NULL | Token de la sesión activa actual |
| `session_expira` | DATETIME, NULL | Vencimiento de esa sesión |
| `creado_en` | DATETIME | Fecha de creación del registro |

## Instalación

**1. Clona el repositorio y entra a la rama v2**
```bash
git clone https://github.com/tuusuario/php-auth-system.git
cd php-auth-system
git checkout v2
```

**2. Crea la base de datos**

Ejecuta el SQL de arriba en phpMyAdmin o tu cliente MySQL.

**3. Configura `shared/config.php`**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');
define('APP_URL', 'http://localhost:3000');
```

**4. Inicia el servidor**
```bash
php -S localhost:3000
```

Abre `http://localhost:3000` en tu navegador.

## Cargar usuarios en lote (CSV)

1. Ve a `http://localhost:3000/cargaUsuarios.html`
2. Prepara un archivo `.csv` con columnas separadas por **punto y coma (`;`)**:

```
nombre;email;password
Juan;juan@demo.com;clave12345
Ana;ana@demo.com;clave67890
```

3. Selecciona el archivo y haz clic en **"Subir y procesar"**
4. El sistema muestra cuántos usuarios se insertaron y cuántos se omitieron

> Si subes el mismo archivo dos veces, los usuarios ya existentes (por email) se omiten automáticamente — no se duplican ni se sobrescriben.

## Crear usuarios iniciales (alternativa)

`seed.php` permite crear usuarios de prueba ejecutando un script desde terminal:

```bash
php seed.php
```

> Ejecútalo solo una vez y elimínalo o renómbralo después de usarlo, ya que no tiene protección de acceso vía navegador.

## Seguridad implementada
- Contraseñas cifradas con **bcrypt**
- Consultas con **PDO prepared statements**
- Sesión única por usuario mediante `session_token` en base de datos
- Verificación periódica de sesión cada 5 segundos desde el dashboard
- Acceso al dashboard bloqueado si no hay sesión iniciada
