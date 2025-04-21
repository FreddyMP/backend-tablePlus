Este proyecto es una REST API construida con Laravel que incluye autenticación, gestión de tareas, recordatorios, backups y más.

🚀 Requisitos
- PHP >= 8.1
- Composer
- MySQL
- Laravel >= 12
- En el archivo php.init tener activada la extension soap
⚙️ Instalación
- crear la DB
- Modificar el archivo .env asignando la DB
- Modificar el archivo .env configurando el Mail
- Ejecutar en bash: composer install
- Ejecutar en bash: php artisan migrate
⚙️ Arranques
- Iniciar servidor local: php artisan serve
- Iniciar en segundo plano o en una diferente linea de comandos : php artisan schedule:work
⚙️ Url base
- http://127.0.0.1:8000/api/
