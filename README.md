# MyTasks

Personal Daily Task Manager built with **Laravel 12**, **Blade**, **Vite**, **Bootstrap 5**, and **MySQL**.

## Features

- Authentication (register, login, logout, password reset, email verification)
- Rate limiting on login/register/password reset
- Full Arabic UI with RTL support (`APP_LOCALE=ar`)
- Profile (avatar, password, theme preference)
- Categories with colors and icons
- Tasks CRUD with complete / reopen / duplicate / trash
- Search, filters, and sorting
- Dashboard statistics and productivity summary
- Calendar (day / week / month)
- In-app notifications (reminder, due today, overdue, completed)
- Light / Dark mode

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure MySQL in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mytasks_db
DB_USERNAME=root
DB_PASSWORD=
```

Then:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
php artisan serve
```

App URL: [http://127.0.0.1:8000](http://127.0.0.1:8000)

### Demo account

After seeding:

- Email: `demo@mytasks.test`
- Password: `password`

Also created by `DatabaseSeeder`:

- Email: `test@example.com`
- Password: `password`

## Development

```bash
npm run dev
php artisan serve
php artisan test
php artisan test --filter=Phase
```

## Project docs

See [docs/DEVELOPMENT_PLAN.md](docs/DEVELOPMENT_PLAN.md) for the phased build plan.
