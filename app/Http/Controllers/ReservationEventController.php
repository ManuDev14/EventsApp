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
        $statusFilter = $request->query('status', 'none');

        $query = Reservation::with(['event', 'room'])
            ->orderBy('start_time')
            ->when($statusFilter !== 'none', function ($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });

        $reservations = $query->paginate(10)->through(function ($reservation) {
            return [
                'event_name' => $reservation->event->name,
                'room_name' => $reservation->room->name,
                'start_time' => Carbon::parse($reservation->start_time)->format('h:i A'),
                'end_time' => Carbon::parse($reservation->end_time)->format('h:i A'),
                'event_date_human' => Carbon::parse($reservation->event_date)
                    ->locale('en')
                    ->isoFormat('dddd, MMMM Do YYYY'),
                'status' => $reservation->status,
            ];
        });

        return Inertia::render('reservations/Index', [
            'reservations' => $reservations,
            'events' => Event::select('id', 'name')->get(),
            'rooms' => Room::select('id', 'name')->get(),
            'filters' => [
                'status' => $statusFilter,
            ],
            'statusOptions' => [
                ['label' => 'None', 'value' => 'none'],
                ['label' => 'Active', 'value' => ReservationStatus::ACTIVE->value],
                ['label' => 'Cancelled', 'value' => ReservationStatus::CANCELLED->value],
            ],
        ]);
    }

    public function store(StoreReservationRequest $request, CreateReservationAction $action)
    {
        $action->execute($request->validated());

        return to_route('reservations.index');
    }

    public function cancelByEvent(Request $request)
    {
        $request->validate([
            'event_name' => ['required', 'string', 'exists:events,name'],
        ]);

        $event = Event::where('name', $request->event_name)->first();

        if (!$event) {
            return redirect()->back()->withErrors(['event_name' => 'Event not found.']);
        }

        Reservation::where('event_id', $event->id)
            ->where('status', ReservationStatus::ACTIVE->value)
            ->update(['status' => ReservationStatus::CANCELLED->value]);

        return to_route('reservations.index')->with('success', 'Event reservations cancelled.');
    }
}
