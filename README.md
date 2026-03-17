<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Servicio de credenciales

Desarrollo de una API RESTful encargada de administrar el ciclo de vida completo (CRUD) de credenciales de infraestructura. El sistema permite que cualquier aplicación de la red consulte, mediante una clave cifrada, los datos necesarios para establecer conexiones a bases de datos en tiempo real.

Impacto: Mejora la seguridad al centralizar secretos y simplifica la escalabilidad de proyectos que requieren gestionar múltiples orígenes de datos de forma dinámica.

## 🚀 Requisitos previos

- PHP 8.2 o superior
- Composer
- PostgreSQL 17 (o compatible)

## Instrucciones para la instalacion

1. Clona el repositorio:

```bash
git clone https://github.com/TechMaster01/backend-rinku.git
cd backend-rinku
```

2. Instala las dependencias de PHP:

```bash
composer install
```

3. Crea la base de datos en PostgreSQL con el nombre que tu prefieras (desde psql):

```sql
CREATE DATABASE MySec;
```

4. Copia el archivo de entorno:

```bash
cp .env.example .env
```

5. Configura las variables de entorno en el archivo .env:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```

Asegúrate de colocar el nombre de la base de datos, tu usuario y contraseña de postgreSQL.

6. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

7. Genera la clave para la encriptacion
```bash
php -r "echo 'CREDENTIALS_ENCRYPTION_KEY=' . base64_encode(random_bytes(32)) . PHP_EOL;"
```

Remplaza la lo que esta despues de "CREDENTIALS_ENCRYPTION_KEY=base64:" por la llave generada en el archivo .env

8. Ejecuta las migraciones:

```bash
php artisan migrate
```

## 🛠️ Comandos útiles

```bash
php artisan serve
```

– Inicia el servidor de desarrollo en http://127.0.0.1:8000


## 🤝 Contribuciones

Se agradecen contribuciones, reportes de issues y sugerencias. ¡Adelante, todas son bienvenidas!


---

Hecho con ❤️ usando Laravel + PHP

---
