<?php

use Illuminate\Database\Seeder;
use App\Profession;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Profession::class)->create([
            'title' => 'Desarrollador Back-End'
        ]);

        factory(Profession::class)->create([
            'title' => 'Desarrollador Front-End'
        ]);

        factory(Profession::class)->create([
            'title' => 'Diseñador Web'
        ]);

        factory(Profession::class)->create([
            'title' => 'Dev-Ops Manager'
        ]);

        factory(Profession::class, 21)->create();
    }
}
