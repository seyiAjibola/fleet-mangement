<?php

use App\Services\Compliance\ComplianceCheckService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('compliance:check', function (ComplianceCheckService $service) {
    $result = $service->run();

    $this->info("Compliance check completed. {$result['updated']} records updated, {$result['notifications']} notifications sent.");
})->purpose('Refresh compliance statuses and process notification windows');

Schedule::command('compliance:check')->dailyAt('01:00')->withoutOverlapping();
