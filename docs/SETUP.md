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
- Admin users are redirected to `/admin`.
- Staff users are redirected to `/admin/suppliers`.

### Roles And Access

The application now uses role-based access:

- `admin`
- `staff`

Admin users can access all admin modules.
Staff users can access operational modules but only see suppliers, vehicles, drivers, and bookings they created.

If you already had users before the role update:

- existing users are backfilled to `admin` by migration
- newly registered users default to `staff`

### Image Uploads

Vehicle gallery images are saved in `storage/app/public/vehicles`.

If images upload but do not display:

- Confirm `php artisan storage:link` has been run.
- Confirm the file exists in `storage/app/public/vehicles`.
- Confirm the browser can access `/storage/...`.

### Recent Schema Additions

Make sure your database includes these newer fields and tables:

- `users.nin`
- `users.role`
- `suppliers.cac_no`
- `suppliers.tin`
- `vehicles.fuel_type`
- `vehicle_images`
- `suppliers.created_by_user_id`
- `vehicles.created_by_user_id`
- `drivers.created_by_user_id`
- `customer_bookings.created_by_user_id`

If not, run:

```bash
php artisan migrate
```

### Supplier Grading

Supplier grading is now automatic.

The application calculates:

- `supplier_score`
- `supplier_tier`

`supplier_score` is calculated from:

- active status
- CAC No
- TIN
- website
- Instagram page
- years in business
- fleet size

`supplier_tier` is then assigned automatically from the calculated score and supplier profile data.

If you pull the latest code into an older local environment, run:

```bash
php artisan migrate
```

and then update supplier or vehicle records normally so the score and tier logic applies going forward.

### Ownership Scoping

Operational records now track ownership through `created_by_user_id`.

This affects:

- suppliers
- vehicles
- drivers
- customer bookings

Staff users only see records within their ownership scope.
Admins still see all records.

## Upgrade Checklist

Use this checklist when updating an older install of the project.

1. Pull the latest code.
2. Run the newest migrations.
3. Ensure the storage symlink exists for vehicle images.
4. Clear Laravel caches if config, routes, or views were cached.
5. Validate access control and supplier grading after deployment.

Recommended commands:

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

Post-upgrade checks:

- admin users can open all admin modules
- staff users are limited to their owned operational records
- supplier score and tier are computed automatically
- reports page loads for admin users
- vehicle image uploads and previews still work
