# Setup Guide

This guide explains how to run the project locally.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or another supported Laravel database

## Installation

1. Install backend dependencies.

```bash
composer install
```

2. Install frontend dependencies.

```bash
npm install
```

3. Create the environment file.

```bash
cp .env.example .env
```

4. Generate the application key.

```bash
php artisan key:generate
```

5. Update database credentials in `.env`.

6. Run database migrations.

```bash
php artisan migrate
```

7. Create the public storage symlink for uploaded vehicle images.

```bash
php artisan storage:link
```

## Run The App

Use the combined development command:

```bash
composer run dev
```

This starts:

- Laravel development server
- Queue listener
- Log viewer
- Vite dev server

## Testing

Run all tests:

```bash
php artisan test
```

## Notes

### Login Landing Page

- The root route `/` redirects to `/login`.
- After login, users are redirected to `/admin`.

### Image Uploads

Vehicle gallery images are saved in `storage/app/public/vehicles`.

If images upload but do not display:

- Confirm `php artisan storage:link` has been run.
- Confirm the file exists in `storage/app/public/vehicles`.
- Confirm the browser can access `/storage/...`.

### Recent Schema Additions

Make sure your database includes these newer fields and tables:

- `users.nin`
- `suppliers.cac_no`
- `suppliers.tin`
- `vehicles.fuel_type`
- `vehicle_images`

If not, run:

```bash
php artisan migrate
```
