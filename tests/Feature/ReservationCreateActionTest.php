<?php

use App\Actions\Reservations\CreateReservationAction;
use App\Models\Event;
use App\Models\Room;
use App\Models\Reservation;
use App\Enums\ReservationStatus;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->event = Event::factory()->create();
    $this->room = Room::factory()->create();

    $this->basePayload = [
        'event_id' => $this->event->id,
        'room_id' => $this->room->id,
        'event_date' => Carbon::today()->toDateString(),
        'start_time' => '10:00',
        'end_time' => '11:00',
    ];

    $this->action = app(CreateReservationAction::class);
});

it('creates a reservation when no conflict exists', function () {
    $reservation = $this->action->execute($this->basePayload);

    expect($reservation)->toBeInstanceOf(Reservation::class)
        ->and($reservation->room_id)->toBe($this->room->id)
        ->and($reservation->event_id)->toBe($this->event->id);
});

it('throws exception if room has overlapping reservation', function () {
    // Create existing reservation in same room and overlapping time
    Reservation::factory()->create([
        'room_id' => $this->room->id,
        'event_id' => Event::factory()->create()->id,
        'event_date' => $this->basePayload['event_date'],
        'start_time' => '10:30',
        'end_time' => '11:30',
        'status' => ReservationStatus::ACTIVE->value,
    ]);

    $this->action->execute($this->basePayload);
})->throws(RuntimeException::class, 'Conflict: an overlapping reservation already exists in this room');

it('throws exception if the event is already scheduled that day', function () {
    // Create reservation for the same event on same day but different room
    Reservation::factory()->create([
        'room_id' => Room::factory()->create()->id,
        'event_id' => $this->event->id,
        'event_date' => $this->basePayload['event_date'],
        'start_time' => '08:00',
        'end_time' => '09:00',
        'status' => ReservationStatus::ACTIVE->value,
    ]);

    $this->action->execute($this->basePayload);
})->throws(RuntimeException::class, 'Conflict: this event is already scheduled on the selected date.');

it('allows reservation in different room if time overlaps but event is different', function () {
    Room::factory()->create(['id' => 999]);
    Reservation::factory()->create([
        'room_id' => 999,
        'event_id' => Event::factory()->create()->id,
        'event_date' => $this->basePayload['event_date'],
        'start_time' => '10:00',
        'end_time' => '11:00',
        'status' => ReservationStatus::ACTIVE->value,
    ]);

    $reservation = $this->action->execute($this->basePayload);

    expect($reservation)->toBeInstanceOf(Reservation::class);
});
