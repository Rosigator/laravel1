<?php

use Illuminate\Database\Seeder;
use App\UserProfile as UserProfile;
use app\User as User;

class UserProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_number = User::count();

        factory(UserProfile::class)->create([
            'user_id' => 1,
            'profession_id' => 1,
            'twitter' => 'https://twitter.com/Code_And_Pray',
            'bio' => 'Soy un jodido desastre'
        ]);

        for ($i = 1; $i < $user_number; $i++) {
            factory(UserProfile::class)->create([
                'user_id' => $i + 1
            ]);
        }
    }
}
