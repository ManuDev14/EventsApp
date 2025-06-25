<?php

namespace App\Http\Controllers;

use App\Actions\Reservations\CreateReservationAction;
use App\Enums\ReservationStatus;
use App\Http\Requests\ReservationEventController\StoreReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ReservationEventController extends Controller
{
    public function index(Request $request)
    {
        // $date = $request->query('date', Carbon::today()->toDateString());

        // $reservations = Reservation::with(['event', 'room'])
        //     ->where('event_date', $date)
        //     ->where('status', ReservationStatus::ACTIVE->value)
        //     ->orderBy('start_time')
        //     ->get()
        //     ->map(function ($reservation) {
        //         return [
        //             'event_name' => $reservation->event->name,
        //             'room_name' => $reservation->room->name,
        //             'start_time' => $reservation->start_time,
        //             'end_time' => $reservation->end_time,
        //             'event_date_human' => Carbon::parse($reservation->event_date)
        //                 ->locale('en')
        //                 ->isoFormat('dddd, MMMM Do YYYY'),
        //         ];
        //     });

        return Inertia::render('reservations/Index');
    }

    public function store(StoreReservationRequest $request, CreateReservationAction $action)
    {
        $action->execute($request->validated());

        return redirect()
            ->back()
            ->with('success', 'Reservation created successfully.');
    }
}
