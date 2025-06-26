<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $events = Room::factory()->count(10)->create();

        $rooms = Event::factory()->count(20)->create();

        Reservation::factory()
            ->count(50)
            ->make()
            ->each(function ($reservation) use ($events, $rooms) {
                $reservation->event_id = $events->random()->id;
                $reservation->room_id = $rooms->random()->id;
                $reservation->event_date = Carbon::today()->addDays(rand(0, 3))->toDateString();
                $reservation->save();
            });
    }
}
