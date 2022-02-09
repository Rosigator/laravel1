<?php

use Illuminate\Database\Seeder;
use App\User;

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
            'password' => bcrypt('secret')
        ]);

        factory(User::class, 40)->create();
    }
}
