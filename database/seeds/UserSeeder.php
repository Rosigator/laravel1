<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Profession;
use App\UserProfile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prof_id = Profession::where('title', 'Desarrollador Back-End')->value('id');

        $me = factory(User::class)->create([
            'name' => 'Héctor Castro Gómez',
            'email' => 'hector@mail.com',
            'role' => 'admin',
            'password' => bcrypt('secret')
        ]);

        $me->profile()->create([
            'profession_id' => $prof_id,
            'twitter' => 'https://twitter.com/hector',
            'bio' => 'Soy la hostia en vinagre.'
        ]);

        factory(User::class, 40)->create()->each(function ($user) {
            $user->profile()->create(
                factory(UserProfile::class)->raw()
            );
        });
    }
}
