<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'title' => Role::LAWYER,
        ]);

        Role::create([
            'title' => Role::CLIENT,
        ]);
    }
}
