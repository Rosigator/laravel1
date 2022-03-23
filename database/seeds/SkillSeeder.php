<?php

use App\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $habilidades = ['PHP', 'POO', 'JS', 'TDD', 'CSS', 'JSP', 'MySQL'];

        foreach ($habilidades as $habilidad) {
            factory(Skill::class)->create([
                'name' => $habilidad
            ]);
        }

        factory(Skill::class, 2)->create();
    }
}
