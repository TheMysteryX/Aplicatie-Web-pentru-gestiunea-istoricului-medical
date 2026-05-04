<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('app:trimite-reminder-programari')->dailyAt('08:00');
Schedule::command('app:trimite-reminder-retete')->dailyAt('08:00');
Schedule::command('app:trimite-reminder-tratamente')->dailyAt('08:00');
Schedule::command('app:trimite-reminder-trimiteri')->dailyAt('08:00');

