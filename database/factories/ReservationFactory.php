<?php

namespace Database\Factories;

use App\Models\{
    Event,
    Room
};
use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->time('H:i');
        $end = Carbon::createFromFormat('H:i', $start)->addHour()->format('H:i');

        return [
            'event_id' => Event::factory(),
            'room_id' => Room::factory(),
            'event_date' => Carbon::today()->toDateString(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => ReservationStatus::ACTIVE->value,
        ];
    }
}