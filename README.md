Este proyecto es una REST API construida con Laravel que incluye autenticación, gestión de tareas, recordatorios, backups y más.

# 🚀 Requisitos
- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 12
- En el archivo php.init tener activada la extension soap

# ⚙️ Instalación
## Desde google drive

- crear la DB
- Ejecutar en bash: php artisan migrate 

## Desde github
- crear la DB
- Ejecutar en bash: composer install
- Ejecutar en bash: cp .env.example .

- Modificar el archivo .env asignando la  y agregar 
    -DB_CONNECTION=mysql
   - DB_HOST=127.0.0.1
   - DB_PORT=3306
   - DB_DATABASE=nameDb
   - DB_USERNAME=root
   - DB_PASSWORD=

- Modificar el archivo .env configurando el Mail
    - MAIL_MAILER=smtp
    - MAIL_SCHEME=null
    - =smtp.gmail.com
    - MAIL_PORT=587
    - MAIL_USERNAME=freddypimpns@gmail.com
    - MAIL_PASSWORD=jqtuabrszkycvfti
    - MAIL_FROM_ADDRESS=freddypimpns@gmail.com
    - MAIL_FROM_NAME="Freddy"

- Ejecutar en bash: php artisan migrate 

# ⚙️ Arranques
- Iniciar servidor local: php artisan serve
- Iniciar en segundo plano o en una diferente linea de comandos : php artisan schedule:work

# ⚙️ Url base
- http://127.0.0.1:8000/api/

# ⚙️ Primer paso registrar usuarios

- 127.0.0.1:8000/api/register
