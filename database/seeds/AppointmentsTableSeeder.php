<?php

use App\Appointment;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::lawyers()->each(function (User $user) {
            //$appointment = factory(Appointment::class)->make();
            // $appointments = collect([]); 
            $faker = \Faker\Factory::create();
            $fromDate = Carbon::now()->hour(random_int(9, 11))->minute('0')->second('0');

            for ($i = 0; $i < random_int(3, 15); $i++) {
                $from = (clone $fromDate)->addDays($i);

                for ($y = 0; $y < random_int(2, 7); $y++) {
                    if ($from->hour > 18) {
                        break;
                    }

                    $from->addHour(random_int(1, 2));
                    $to = (clone $from)->addHour(1);

                    $appointment = new Appointment([
                        'from_time' => $from,
                        'to_time' => $to,
                        'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
                        'user_id' => User::inRandomOrder()->clients()->first()->id,
                        'lawyer_id' => $user->id,
                        'details' => $faker->realText(100)
                    ]);

                    $appointment->save();

                    //$appointments->push($appointment);
                }
            }

            //Appointment::saveMany($appointments); // override user_id
        });
    }
}
