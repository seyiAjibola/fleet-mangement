<?php

use App\Livewire\Admin\Bookings\Create as BookingsCreate;
use App\Livewire\Admin\Bookings\Edit as BookingsEdit;
use App\Livewire\Admin\Bookings\Index as BookingsIndex;
use App\Livewire\Admin\Bookings\Show as BookingsShow;
use App\Livewire\Admin\Compliance\Index as ComplianceIndex;
use App\Livewire\Admin\Dashboard\Index as DashboardIndex;
use App\Livewire\Admin\Drivers\Create as DriversCreate;
use App\Livewire\Admin\Drivers\Edit as DriversEdit;
use App\Livewire\Admin\Drivers\Index as DriversIndex;
use App\Livewire\Admin\Drivers\Show as DriversShow;
use App\Livewire\Admin\Notifications\Index as NotificationsIndex;
use App\Livewire\Admin\Reports\Index as ReportsIndex;
use App\Livewire\Admin\Suppliers\Create as SuppliersCreate;
use App\Livewire\Admin\Suppliers\Edit as SuppliersEdit;
use App\Livewire\Admin\Suppliers\Index as SuppliersIndex;
use App\Livewire\Admin\Suppliers\Show as SuppliersShow;
use App\Livewire\Admin\Users\Create as UsersCreate;
use App\Livewire\Admin\Users\Edit as UsersEdit;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Users\Show as UsersShow;
use App\Livewire\Admin\Vehicles\Create as VehiclesCreate;
use App\Livewire\Admin\Vehicles\Edit as VehiclesEdit;
use App\Livewire\Admin\Vehicles\Index as VehiclesIndex;
use App\Livewire\Admin\Vehicles\Show as VehiclesShow;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', DashboardIndex::class)->name('dashboard');

        Route::middleware('admin')->group(function () {
            Route::get('users', UsersIndex::class)->name('users.index');
            Route::get('users/create', UsersCreate::class)->name('users.create');
            Route::get('users/{user}', UsersShow::class)->name('users.show');
            Route::get('users/{user}/edit', UsersEdit::class)->name('users.edit');

            Route::get('reports', ReportsIndex::class)->name('reports.index');
        });

        Route::get('suppliers', SuppliersIndex::class)->name('suppliers.index');
        Route::get('suppliers/create', SuppliersCreate::class)->name('suppliers.create');
        Route::get('suppliers/{supplier}', SuppliersShow::class)->name('suppliers.show');
        Route::get('suppliers/{supplier}/edit', SuppliersEdit::class)->name('suppliers.edit');

        Route::get('vehicles', VehiclesIndex::class)->name('vehicles.index');
        Route::get('vehicles/create', VehiclesCreate::class)->name('vehicles.create');
        Route::get('vehicles/{vehicle}', VehiclesShow::class)->name('vehicles.show');
        Route::get('vehicles/{vehicle}/edit', VehiclesEdit::class)->name('vehicles.edit');

        Route::get('drivers', DriversIndex::class)->name('drivers.index');
        Route::get('drivers/create', DriversCreate::class)->name('drivers.create');
        Route::get('drivers/{driver}', DriversShow::class)->name('drivers.show');
        Route::get('drivers/{driver}/edit', DriversEdit::class)->name('drivers.edit');

        Route::get('compliance', ComplianceIndex::class)->name('compliance.index');
        Route::get('notifications', NotificationsIndex::class)->name('notifications.index');

        // Route::get('customer-bookings', BookingsIndex::class)->name('bookings.index');
        // Route::get('customer-bookings/create', BookingsCreate::class)->name('bookings.create');
        // Route::get('customer-bookings/{booking}', BookingsShow::class)->name('bookings.show');
        // Route::get('customer-bookings/{booking}/edit', BookingsEdit::class)->name('bookings.edit');
    });
