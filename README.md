# 🔐 php-auth-system

Sistema de autenticación en PHP puro con login, registro y recuperación de contraseña por email.

## Tecnologías
- PHP 8 · MySQL · HTML · CSS · JavaScript · Axios · PHPMailer

## Requisitos
- PHP 8+
- MySQL / MariaDB
- Puerto usado 3307 cambiar a 3306 segun configuración
- Composer
- Cuenta Gmail con verificación en dos pasos activa

## Instalación

**1. Clona el repositorio**
```bash
git clone https://github.com/tuusuario/php-auth-system.git
cd php-auth-system
```

**2. Instala PHPMailer**
```bash
composer install
```

**3. Crea la base de datos**

Ejecuta el archivo `database.sql` en phpMyAdmin o tu cliente MySQL.

**4. Configura `shared/config.php`**
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'auth_db');
define('DB_USER', 'tu_usuario');
define('DB_PASS', 'tu_contraseña');

define('APP_URL', 'http://localhost:3000');
```

**5. Configura `shared/mailer.php`**

Necesitas una contraseña de aplicación de Gmail (no la contraseña normal):
1. Activa la verificación en dos pasos en tu cuenta Google
2. Ve a [myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)
3. Crea una contraseña y copia los 16 caracteres generados

```php
$mail->Username = 'tucorreo@gmail.com';
$mail->Password = 'xxxxxxxxxxxxxxxx'; // 16 caracteres sin espacios
$mail->setFrom('tucorreo@gmail.com', 'Mi App');
```

**6. Inicia el servidor**
```bash
php -S localhost:3000
```

Abre `http://localhost:3000` en tu navegador.

## Producción

Cambia en `shared/config.php`:
```php
define('APP_URL', 'https://tudominio.com');
```
