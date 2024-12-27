<?php

// use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Artisan::command('submissions:process-scheduled', function() {
    $this->call('submissions:process-scheduled');
})->purpose('Memproses tugas yang sudah dijadwalkan');
