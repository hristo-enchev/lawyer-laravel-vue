<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Appointment;
use App\User;
use Faker\Generator as Faker;
use Carbon\Carbon;

$factory->define(Appointment::class, function (Faker $faker) {
    $from = Carbon::now();
    $from->hour = '09';
    $from->minute = 0;
    $from->second = 0;

    $to = Carbon::instance($from)->addHour(1);
    
    return [
        'from_time' => $from,
        'to_time' => $to,
        'status' => $faker->randomElement(['pending', 'accepted', 'rejected']),
        'lawyer_id' => User::inRandomOrder()->lawyers()->first()->id,
        'user_id' => User::inRandomOrder()->clients()->first()->id,
        'details' => $faker->realText(100)
    ];
});
