<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Profession;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Héctor Castro Gómez',
            'email' => 'hector@mail.com',
            'is_admin' => true,
            'profession_id' => 4,
            'password' => bcrypt('secret')
        ]);

        factory(User::class, 20)->create();
    }
}
