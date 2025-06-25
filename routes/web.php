<?php

use App\Http\Controllers\ReservationEventController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [ReservationEventController::class, 'index']);

Route::resource('/reservations', ReservationEventController::class)
    ->only(['index', 'store']);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';