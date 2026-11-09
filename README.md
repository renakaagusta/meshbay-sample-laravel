# meshbay-sample-laravel

Laravel 12 on PHP 8.3+, backed by MySQL, for the Meshbay vs Railway side-by-side test. It is the only sample with a
framework migration; the migration runs automatically on the first HTTP request, so no release command is needed.

> The ticket named Laravel 11, but every Laravel 11 release is blocked by Composer's security-advisory policy (Laravel
> 11 is past its security-support window). This is the same skeleton on Laravel 12, the closest supported equivalent.

## Environment

| Variable | Required | Default | Description |
|---|---|---|---|
| `DATABASE_URL` | yes | none | MySQL connection string, e.g. `mysql://user:password@host:3306/laravel` |
| `PORT` | no | `8000` | Port for `php artisan serve` |
| `APP_KEY` | no | sample key in `config/app.php` | Laravel encryption key, set a real `base64:...` value in production |
| `APP_ENV` | no | `production` | `local` turns on debug pages |
| `LOG_CHANNEL` | no | `stderr` | Keeps logs on stdout/stderr so the platform can capture them |

`DATABASE_URL` is fed straight into Laravel's `mysql` connection (`config/database.php` → `url`). `DB_*` variables work
too, but the contract for these samples is `DATABASE_URL`.

## Run locally

```sh
composer install
cp .env.example .env
DATABASE_URL=mysql://user:password@localhost:3306/laravel php artisan serve --host 0.0.0.0 --port 8080
```

The `items` migration is applied on the first request when the `items` table is missing
(`AppServiceProvider::boot`). To apply it by hand:

```sh
php artisan migrate --force
```

## Endpoints

| Method | Path | Description |
|---|---|---|
| GET | `/health` | `200 {"status":"ok","version":"v1"}` |
| GET | `/api/items` | JSON array of items |
| POST | `/api/items` | Body `{"name":"..."}` → `201` with the created item |
| GET | `/up` | Laravel's built-in health endpoint |

```sh
curl localhost:8080/health
curl -X POST localhost:8080/api/items -H 'content-type: application/json' -d '{"name":"first"}'
curl localhost:8080/api/items
```

CORS is open for every origin (`config/cors.php`). CSRF is exempted for `POST api/items` so the plain JSON call works
without a token. `ItemController::CONTRACT_VERSION` is the `version` text in `/health`.
