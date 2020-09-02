<?php

use App\Role;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Faker\Generator as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::each(function (Role $role) {
            $user = factory(User::class);
            $users = collect();
            $faker = \Faker\Factory::create();
            $password = Hash::make('password');

            for ($i = 0; $i < 50; $i++) {
                $user = User::make([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'role_id' => $role,
                    'email_verified_at' => now(),
                    'password' => $password,
                    'remember_token' => Str::random(10),
                ]);

                $users->push($user);
            }

            $role->users()->saveMany($users);
        });
    }
}
