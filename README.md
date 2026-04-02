# Zeno Cars Admin

Zeno Cars is a Laravel and Livewire admin application for managing a car hire operation.

The app is built for internal staff use. After login, users are redirected to the admin area at `/admin`, where they can manage suppliers, vehicles, drivers, customer bookings, and reports.

## What The App Does

- Authenticates staff users.
- Shows an admin dashboard with operational summaries.
- Manages users and identity details such as NIN.
- Manages suppliers with company, compliance, and fleet filters.
- Manages vehicles with structured filters, detail pages, and image galleries.
- Manages drivers and their supplier/vehicle assignments.
- Manages customer bookings with conflict prevention for vehicle and driver assignments.
- Shows reports with charts and CSV exports.

## Main Features

### Authentication

- `/` redirects to `/login`.
- Successful login and registration redirect to `/admin`.
- Password reset, email verification, and profile management are available.

### Admin Dashboard

- Displays totals for users, suppliers, vehicles, drivers, and bookings.
- Shows booking and vehicle status charts.
- Supports date filtering and CSV export for dashboard summaries.

### Users

- Create, edit, view, and delete users.
- Filter users by name and NIN.

### Suppliers

- Create, edit, view, and delete suppliers.
- Track company name, contact person, contact number, CAC No, TIN, city, and address.
- Filter suppliers by company name, contact person, contact number, number of cars, location/address, CAC No, TIN, and status.

### Vehicles

- Create, edit, view, and delete vehicles.
- Track type, make, model, year, color, plate number, fuel type, condition, and status.
- Filter vehicles by type, make, model, condition, plate number, year, fuel type, color, and status.
- Upload multiple images from the vehicle detail page.
- Set a primary image, preview images in a modal, and delete images.

### Drivers

- Create, edit, view, and delete drivers.
- Track supplier, assigned vehicle, phone number, license, languages, and professional experience.

### Customer Bookings

- Create, edit, view, and delete bookings.
- Assign vehicles and drivers.
- Prevent conflicting assignments at save time, not just in the UI.
- Filter bookings by search text, status, and date range.
- Update booking status from the bookings list.

### Reports

- View charts for booking sources, vehicle categories, and supplier tiers.
- Export report data as CSV.

## Technology

- Laravel 12
- Livewire 3
- Livewire Volt
- Blade
- Tailwind CSS
- Chart.js

## Quick Start

1. Install PHP and Node dependencies.
2. Create the `.env` file.
3. Generate the app key.
4. Run migrations.
5. Create the storage symlink.
6. Start the Laravel and Vite servers.

Example:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
composer run dev
```

## Important Setup Notes

- Vehicle image uploads require `php artisan storage:link`.
- New schema updates in this project include:
  - `suppliers.cac_no`
  - `suppliers.tin`
  - `users.nin`
  - `vehicles.fuel_type`
  - `vehicle_images` table

If your local database is older, run:

```bash
php artisan migrate
```

## Testing

Run the full test suite with:

```bash
php artisan test
```

## Documentation

- [Feature Guide](docs/FEATURES.md)
- [Setup Guide](docs/SETUP.md)
