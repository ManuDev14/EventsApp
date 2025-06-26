<?php

use App\Http\Controllers\ReservationEventController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ReservationEventController::class, 'index']);

Route::resource('/reservations', ReservationEventController::class)
    ->only(['index', 'store']);

Route::delete('/reservations/cancel-by-event', [ReservationEventController::class, 'cancelByEvent'])
    ->name('reservations.cancel-by-event');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
