<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Programación de tareas (Scheduler)
 */

// Ejecuta la limpieza de las bases de datos de la demo cada hora
Schedule::command('demo:clean')->hourly();

// Si prefieres que sea una vez al día a las 3:00 AM para menos carga:
// Schedule::command('demo:clean')->dailyAt('03:00');