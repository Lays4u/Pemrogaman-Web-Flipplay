# FlipPlay — REST API & Web App (Laravel 12)

Project utuh **FlipPlay** berbasis **Laravel 12** yang menghubungkan tampilan antarmuka asli FlipPlay (`resources/views/flipplay.blade.php`) langsung dengan endpoint REST API Eloquent (`routes/api.php`).

## Cara Menjalankan

```bash
cd flipplay-demo
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan route:list
php artisan serve
```

- Buka tampilan web FlipPlay di: `http://127.0.0.1:8000`
- Base URL REST API: `http://127.0.0.1:8000/api`
- Import file `postman/FlipPlay_Laravel12_API.postman_collection.json` ke Postman untuk pengujian endpoint satu per satu.
