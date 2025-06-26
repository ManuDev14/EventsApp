<?php

namespace App\Http\Controllers;

use App\Actions\Reservations\CreateReservationAction;
use App\Enums\ReservationStatus;
use App\Http\Requests\ReservationEventController\StoreReservationRequest;
use App\Models\{Event, Reservation, Room};
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class ReservationEventController extends Controller
{
    public function index(Request $request)
    {
        $reservations = Reservation::with(['event', 'room'])
            ->where('status', ReservationStatus::ACTIVE->value)
            ->orderBy('start_time')
            ->paginate(10) // puedes ajustar la cantidad por página
            ->through(function ($reservation) {
                return [
                    'event_name' => $reservation->event->name,
                    'room_name' => $reservation->room->name,
                    'start_time' => \Carbon\Carbon::parse($reservation->start_time)->format('h:i A'),
                    'end_time' => \Carbon\Carbon::parse($reservation->end_time)->format('h:i A'),
                    'event_date_human' => \Carbon\Carbon::parse($reservation->event_date)
                        ->locale('en')
                        ->isoFormat('dddd, MMMM Do YYYY'),
                ];
            });

        return Inertia::render('reservations/Index', [
            'reservations' => $reservations,
            'events' => Event::select('id', 'name')->get(),
            'rooms' => Room::select('id', 'name')->get(),
        ]);
    }

    public function store(StoreReservationRequest $request, CreateReservationAction $action)
    {
        $action->execute($request->validated());

        return to_route('reservations.index');
    }
}
