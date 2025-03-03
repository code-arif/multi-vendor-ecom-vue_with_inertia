<?php

use App\Models\CheckoutSession;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//call the command for checkout sesstio product deleting
Schedule::call(function () {
    CheckoutSession::where('expires_at', '<', now())->delete();
})->everyMinute();
