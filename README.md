# Zenocar Admin

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
- Enter a manual supplier score used for supplier grading.
- Automatically assign supplier tier based on supplier score, years in business, compliance details, active status, and fleet size.
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
- View supplier-specific and staff-specific operational reports.

## Supplier Grading

The project currently treats `supplier_score` and `supplier_tier` differently:

- `supplier_score` is manual.
  Staff or admins enter it directly in the supplier form.
  There is no automatic score calculation in the current codebase.
- `supplier_tier` is automatic.
  The application calculates it from supplier data and fleet size.

### Supplier Score

`supplier_score` is now calculated automatically by the application.

The current scoring rule is capped at `100` and is based on fields already present in this project:

- `+20` if supplier status is `active`
- `+15` if `CAC No` is present
- `+15` if `TIN` is present
- `+10` if `website` is present
- `+5` if `instagram_page` is present
- experience score:
  `+20` for `10+` years in business
  `+15` for `5+` years
  `+10` for `2+` years
  `+5` for `1+` year
- fleet size score:
  `+15` for `10+` vehicles
  `+10` for `5+` vehicles
  `+8` for `3+` vehicles
  `+5` for `1+` vehicle

Practical implication:

- Staff and admins no longer type supplier score manually.
- The score updates automatically when supplier details or fleet size change.

### Supplier Tier Logic

The application currently assigns supplier tiers with this rule:

- `gold`
  Supplier is `active`, `supplier_score >= 80`, `years_in_business >= 5`, both `CAC No` and `TIN` are present, and the supplier has at least `3` vehicles.
- `silver`
  Supplier is `active`, `supplier_score >= 50`, `years_in_business >= 2`, and at least one compliance document exists: `CAC No` or `TIN`.
- `bronze`
  Any supplier that does not meet the `gold` or `silver` rule.

### When Tier Recalculates

Supplier tier is recalculated when:

- a supplier is created
- a supplier is edited
- a vehicle is added to a supplier
- a vehicle is moved to a different supplier
- a vehicle is deleted

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

## Upgrade Checklist

If you are updating an existing local or deployed copy of the project:

1. Pull the latest code.
2. Run database migrations.
3. Recreate the storage symlink if needed.
4. Clear cached Laravel state if the environment was previously cached.
5. Verify role-based access and supplier grading behavior.

Recommended commands:

```bash
php artisan migrate
php artisan storage:link
php artisan optimize:clear
```

After upgrade, verify:

- existing users still sign in correctly
- admin users can access `Users` and `Reports`
- staff users land on `/admin/suppliers`
- supplier score and supplier tier display automatically
- vehicle images still load from `/storage/...`

## Testing

Run the full test suite with:

```bash
php artisan test
```

## Documentation

- [Feature Guide](docs/FEATURES.md)
- [Setup Guide](docs/SETUP.md)
