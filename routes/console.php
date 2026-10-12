<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Jadwal Tugas Berkala (Console Scheduler)
|--------------------------------------------------------------------------
| Frekuensi backup otomatis mengikuti paket aktif (silver, gold, platinum)
| sesuai batas Package::limit('backup_frequency').
*/
use App\Support\Package;
use Illuminate\Support\Facades\Schedule;

$backupFrequency = Package::limit('backup_frequency') ?? 'monthly';

$backupSchedule = Schedule::command('backup:run');

match ($backupFrequency) {
    'daily' => $backupSchedule->dailyAt('02:00'),
    'weekly' => $backupSchedule->weeklyOn(1, '02:00'),
    'monthly' => $backupSchedule->monthlyOn(1, '02:00'),
    default => $backupSchedule->monthlyOn(1, '02:00'),
};

Schedule::command('backup:clean')->dailyAt('01:00');

