<?php

namespace Tests\Feature\Admin;

use App\{User, Profession, Skill, UserProfile};
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Fernando Contreras',
        'email' => 'fernando@mail.com',
        'password' => 'secret',
        'role' => 'user',
        'profession_id' => null,
        'twitter' => 'https://twitter.com/fernando',
        'bio' => 'Soy un tío de puta madre.'
    ];

    /** @test */
    public function it_loads_user_edit_page()
    {
        $user = factory(User::class)->create();

        $user->profile()->create(['bio' => 'asdf', 'profession_id' => null, 'twitter' => null]);

        $this->get("usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee("Editando información del usuario #{$user->id}")
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function it_updates_a_user()
    {
        $user = factory(User::class)->create();

        $old_profession = factory(Profession::class)->create();
        $new_profession = factory(Profession::class)->create();

        $old_skill1 = factory(Skill::class)->create();
        $old_skill2 = factory(Skill::class)->create();
        $new_skill1 = factory(Skill::class)->create();
        $new_skill2 = factory(Skill::class)->create();

        $user->profile()->save(factory(UserProfile::class)->make([
            'profession_id' => $old_profession->id
        ]));

        $user->skills()->attach([$old_skill1->id, $old_skill2->id]);

        $this->put("usuarios/{$user->id}", $this->withData([
            'role' => 'admin',
            'skills' => [$new_skill1->id, $new_skill2->id],
            'profession_id' => $new_profession->id
        ]))->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
            'role' => 'admin'
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => $new_profession->id,
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.',
        ]);

        $this->assertDatabaseCount('user_skill', 2);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $new_skill1->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $new_skill2->id
        ]);

        $this->assertDatabaseMissing('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $old_skill1->id
        ]);

        $this->assertDatabaseMissing('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $old_skill2->id
        ]);
    }

    // /** @test */
    // public function the_user_is_redirected_to_previous_page_when_validation_fails()
    // {
    //     $this->handleValidationExceptions();

    //     $profession = factory(Profession::class)->create;
    //     $skillA = factory(Skill::class)->create();
    //     $skillB = factory(Skill::class)->create();

    //     $user = factory(User::class)->create([
    //         'name' => 'Fernando Contreras',
    //         'email' => 'fernando@mail.com',
    //         'password' => 'secret',
    //         'role' => 'user'
    //     ]);

    //     $user->profile()->create([
    //         'profession_id' => $profession->id,
    //         'twitter' => 'https://twitter.com/fernando',
    //         'bio' => 'Soy un tío de puta madre.'
    //     ]);

    //     $user->

    //     $this->from("usuarios/{$user->id}/editar")->put("usuarios/{$user->id}", [])
    //         ->assertRedirect("usuarios/{$user->id}/editar");

    //     $this->assertDatabaseHas('users');
    // }

    //NAME VALIDATION

    /** @test */
    public function the_name_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'name' => ''
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'floripondio@mail.com']);
    }

    //EMAIL VALIDATION

    /** @test */
    public function the_email_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'email' => ''
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['name' => 'Tropofosio Filibusteo']);
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'email' => 'asdf'
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'asdf']);
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'existe@mail.com'
        ]);

        $user = factory(User::class)->create([
            'name' => 'Manolo',
            'email' => 'noexiste@mail.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'email' => 'existe@mail.com',
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Manolo',
            'email' => 'existe@mail.com'
        ]);
    }

    /** @test */
    public function the_users_email_can_stay_the_same()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create([
            'email' => 'suemail@mail.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'email' => 'suemail@mail.com',
            ]))
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'suemail@mail.com'
        ]);
    }

    // PASSWORD VALIDATION

    /** @test */
    public function the_password_field_is_optional()
    {
        $this->handleValidationExceptions();

        $old_password = 'clave_vieja';

        $user = factory(User::class)->create([
            'password' => bcrypt($old_password)
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'password' => ''
            ]))
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'email' => 'fernando@mail.com',
            'password' => $old_password
        ]);
    }

    /** @test */
    public function the_password_field_must_be_at_least_6_chars()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create([
            'email' => 'fernando@mail.com',
            'password' => bcrypt('oldpassword')
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'password' => '123'
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'password' => 'The password must be at least 6 characters.'
            ]);

        $this->assertCredentials([
            'email' => 'fernando@mail.com',
            'password' => 'oldpassword'
        ]);
    }

    //ROLE VALIDATION

    /** @test */
    public function the_role_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create([
            'role' => 'user'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'role' => null
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'role' => 'The role field is required.'
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'user'
        ]);
    }

    //PROFESSION VALIDATION

    /** @test */
    public function the_profession_id_field_is_optional()
    {
        $this->handleValidationExceptions();

        $profession = factory(Profession::class)->create();

        $user = factory(User::class)->create();
        $user->profile()->create([
            'bio' => 'asdf',
            'profession_id' => $profession->id
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'profession_id' => null
            ]))->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => null
        ]);
    }

    /** @test */
    public function the_profession_id_field_must_be_present()
    {
        $this->handleValidationExceptions();

        $profession = factory(Profession::class)->create();

        $user = factory(User::class)->create();
        $user->profile()->create([
            'bio' => 'asdf',
            'profession_id' => $profession->id
        ]);

        $data = $this->withData();
        unset($data['profession_id']);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $data)
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'profession_id' => 'The profession field must be present.',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => $profession->id
        ]);
    }

    /** @test */
    public function the_profession_id_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $profession = factory(Profession::class)->create();

        $user = factory(User::class)->create();
        $user->profile()->create([
            'bio' => 'asdf',
            'profession_id' => $profession->id
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'profession_id' => 999
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'profession_id' => 'The selected profession is not valid.'
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => $profession->id
        ]);
    }

    /** @test */
    public function only_selectable_professions_are_valid()
    {
        $this->handleValidationExceptions();

        $nonDeletedProfession = factory(Profession::class)->create();
        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d')
        ]);

        $user = factory(User::class)->create();
        $user->profile()->create([
            'bio' => 'asdf',
            'profession_id' => $nonDeletedProfession->id
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'profession_id' => $deletedProfession->id
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'profession_id' => $nonDeletedProfession->id
        ]);
    }

    //SKILLS VALIDATION

    /** @test */
    public function it_detaches_all_the_skills_if_none_are_selected()
    {
        $this->handleValidationExceptions();

        $old_skill1 = factory(Skill::class)->create();
        $old_skill2 = factory(Skill::class)->create();

        $user = factory(User::class)->create();
        $user->skills()->attach([$old_skill1->id, $old_skill2->id]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData())
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users', [
            'id' => $user->id
        ]);

        $this->assertDatabaseEmpty('user_skill');
    }

    /** @test */
    public function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $old_skill1 = factory(Skill::class)->create();
        $old_skill2 = factory(Skill::class)->create();

        $user = factory(User::class)->create();
        $user->skills()->attach([$old_skill1->id, $old_skill2->id]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'skills' => 'PHP, POO'
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'skills' => 'The skills must be an array.'
            ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $old_skill1->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $old_skill2->id
        ]);
    }

    /** @test */
    public function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $new_skill = factory(Skill::class)->create();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'skills' => [$new_skill->id + 1]
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'skills' => 'The chosen skill is not valid.'
            ]);

        $this->assertDatabaseEmpty('user_skill');
    }

    //BIO VALIDATION

    /** @test */
    public function the_bio_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();
        $user->profile()->create([
            'bio' => 'asdf',
            'profession_id' => null
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", $this->withData([
                'bio' => null
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'bio' => 'The bio field is required.',
            ]);

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'bio' => 'asdf'
        ]);
    }
}
