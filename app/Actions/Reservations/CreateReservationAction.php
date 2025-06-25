<?php

namespace App\Actions\Reservations;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class CreateReservationAction
{
  /**
   * Store a reservation after validating time conflicts
   *
   * @param array $data
   * @return Reservation
   *
   * @throws \RuntimeException if a time conflict is found
   */
  public function execute(array $data): Reservation
  {
    return DB::transaction(function () use ($data) {

      $conflict = Reservation::where('room_id', $data['room_id'])
        ->where('event_date', $data['event_date'])
        ->where('status', ReservationStatus::ACTIVE->value)
        ->where(function ($query) use ($data) {
          $query
            // Case 1: new start_time is between existing reservation
            ->whereBetween('start_time', [$data['start_time'], $data['end_time']])
            // Case 2: new end_time is between existing reservation
            ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
            // Case 3: new reservation fully surrounds an existing one, or is fully surrounded
            ->orWhere(function ($q) use ($data) {
              $q->where('start_time', '<=', $data['start_time'])
                ->where('end_time', '>=', $data['end_time']);
            });
        })
        ->exists();

      if ($conflict) {
        throw new \RuntimeException('Conflict: an overlapping reservation already exists in this room for the selected date and time.');
      }

      return Reservation::create([
        'event_id' => $data['event_id'],
        'room_id' => $data['room_id'],
        'event_date' => $data['event_date'],
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time'],
        'status' => ReservationStatus::ACTIVE->value,
      ]);
    });
  }
}
