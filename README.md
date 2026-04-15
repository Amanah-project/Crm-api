# Amanah — Crm-api

Krótki opis
- CRM API (Laravel) — logika biznesowa CRM, integracje i zarządzanie danymi.

Wymagania
- PHP 8.x, Composer
- Baza danych: MySQL / PostgreSQL
- Redis (jeśli używacie kolejek/cacha)

Szybki start
1. `cp .env.example .env` i skonfiguruj zmienne środowiskowe.
2. `composer install`
3. `php artisan key:generate`
4. Ustaw `DB_*` w `.env` i uruchom migracje: `php artisan migrate --seed`.
5. `php artisan storage:link` jeśli potrzebne.
6. `php artisan queue:work` (lub inny driver) dla zadań w tle.

Specyficzne ustawienia CRM
- `CRM_API_KEY`, `CRM_ENDPOINT` — jeśli są używane integracje z zewnętrznymi systemami.
- `QUEUE_CONNECTION` — ustaw na `redis` jeśli chcesz używać kolejki w tle.

Uruchamianie i debug
- Lokalnie: `php artisan serve`.
- W kontenerze: `docker-compose exec crm-api php artisan migrate`.

Uwaga
- Uzupełnij wpisy dotyczące integracji (API keys, webhooki) w dokumentacji wewnętrznej.
