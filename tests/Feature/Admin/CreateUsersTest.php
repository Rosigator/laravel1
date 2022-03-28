<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Skill;
use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData = [
        'name' => 'Fernando Contreras',
        'email' => 'fernando@mail.com',
        'password' => 'secret',
        'profession_id' => null,
        'twitter' => 'https://twitter.com/fernando',
        'bio' => 'Soy un tío de puta madre.',
        'role' => 'user'
    ];

    /** @test */
    public function it_loads_user_creation_page()
    {

        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertViewIs('users.create')
            ->assertViewHas('professions', function ($professions) use ($profession) {
                return $professions->contains($profession);
            })
            ->assertViewHas('skills', function ($skills) use ($skillA, $skillB) {
                return $skills->contains($skillA) && $skills->contains($skillB);
            });
    }

    /** @test */
    public function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create([
            'name' => 'skillA'
        ]);
        $skillB = factory(Skill::class)->create([
            'name' => 'skillB'
        ]);
        $skillC = factory(Skill::class)->create([       //skill que no voy a seleccionar
            'name' => 'skillC'
        ]);

        $this->post('usuarios/crear', $this->withData([
            'profession_id' => $profession->id,
            'skills' => [
                $skillA->id => $skillA->id,
                $skillB->id => $skillB->id,
            ]
        ]))
            ->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
            'role' => 'user'
        ]);

        $user = User::findByEmail('fernando@mail.com');

        $this->assertDatabaseHas('user_profiles', [
            'profession_id' => $profession->id,
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.',
            'user_id' => $user->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillA->id
        ]);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $skillB->id
        ]);

        $this->assertDatabaseMissing('user_skill', [   //skill que no voy a seleccionar
            'user_id' => $user->id,
            'skill_id' => $skillC->id
        ]);
    }

    /** @test */
    public function the_user_is_redirected_to_previous_page_when_validation_fails()
    {
        $this->handleValidationExceptions();

        $this->from('usuarios/nuevo')->post('usuarios/crear', [])
            ->assertRedirect('usuarios/nuevo');

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_name_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'name' => '',
        ]))
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);
        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_password_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'password' => null
        ]))
            ->assertSessionHasErrors([
                'password' => 'The password field is required.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_password_field_must_be_at_least_6_chars()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'password' => '123'
        ]))
            ->assertSessionHasErrors([
                'password' => 'The password must be at least 6 characters.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'email' => null
        ]))
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'email' => 'asdf'
        ]))
            ->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();

        factory(User::class)->create([
            'email' => 'oldemail@mail.com',
        ]);

        $this->post('usuarios/crear', $this->withData([
            'email' => 'oldemail@mail.com'
        ]))
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertEquals(1, User::count());
    }

    //PROFESSION VALIDATION

    /** @test */
    public function the_profession_id_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'profession_id' => null
        ]));

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'profession_id' => null,
            'twitter' => 'https://twitter.com/fernando',
            'bio' => 'Soy un tío de puta madre.'
        ]);
    }

    /** @test */
    public function the_profession_id_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'profession_id' => 300
        ]))
            ->assertSessionHasErrors([
                'profession_id' => 'The selected profession is not valid.'
            ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function only_selectable_professions_are_valid()
    {
        $this->handleValidationExceptions();

        $deletedProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d')
        ]);

        $this->post('usuarios/crear', $this->withData([
            'profession_id' => $deletedProfession->id
        ]))
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
    }

    //SKILL VALIDATION

    /** @test */
    public function the_skills_are_optional()
    {
        $this->handleValidationExceptions();

        $this->from('/usuarios/nuevo')->post('usuarios/crear', $this->withData([
            'skills' => []
        ]))->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'fernando@mail.com',
        ]);

        $this->assertDatabaseEmpty('user_skill');
    }

    /** @test */
    public function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'skills' => 'PHP, POO'
        ]))
            ->assertSessionHasErrors([
                'skills' => 'The skills must be an array.'
            ]);

        $this->assertDatabaseEmpty('users');
        $this->assertDatabaseEmpty('user_skill');
    }

    /** @test */
    public function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();

        $skillA = factory(Skill::class)->create([
            'name' => 'skillA'
        ]);
        $skillB = factory(Skill::class)->create([
            'name' => 'skillB'
        ]);

        $this->post('usuarios/crear', $this->withData([
            'skills' => [
                $skillA->id => $skillA->id,
                $skillB->id + 99 => $skillB->id + 99
            ]
        ]))
            ->assertSessionHasErrors([
                'skills' => 'The selected skills are not valid.'
            ]);

        $this->assertDatabaseEmpty('users');
        $this->assertDatabaseEmpty('user_skill');
    }

    //ROLE VALIDATION

    /** @test */
    public function the_role_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->from('/usuarios/nuevo')->post('usuarios/crear', $this->withData([
            'role' => null
        ]))->assertRedirect('usuarios');

        $this->assertDatabaseHas('users', [
            'email' => 'fernando@mail.com',
            'role' => 'user'
        ]);
    }

    /** @test */
    public function the_role_field_must_be_valid()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'role' => 'invalid-role'
        ]))
            ->assertSessionHasErrors('role');

        $this->assertDatabaseEmpty('users');
    }

    //BIO VALIDATION

    /** @test */
    public function the_bio_field_is_required()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'bio' => '',
        ]))
            ->assertSessionHasErrors([
                'bio' => 'The bio field is required.'
            ]);
        $this->assertDatabaseEmpty('users');
    }

    //TWITTER VALIDATION

    /** @test */
    public function the_twitter_field_is_optional()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'twitter' => ''
        ]));

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'twitter' => null,
            'bio' => 'Soy un tío de puta madre.',
            'user_id' => User::findByEmail('fernando@mail.com')->id
        ]);
    }

    /** @test */
    public function the_twitter_field_must_be_an_url()
    {
        $this->handleValidationExceptions();

        $this->post('usuarios/crear', $this->withData([
            'twitter' => 'asdf'
        ]))->assertSessionHasErrors([
            'twitter' => 'The twitter field must be an url'
        ]);

        $this->assertDatabaseEmpty('users');
    }

    /** @test */
    public function the_twitter_field_must_be_present()
    {
        $this->handleValidationExceptions();

        $data = $this->withData();
        unset($data['twitter']);

        $this->post('usuarios/crear', $data)
            ->assertSessionHasErrors([
                'twitter' => 'The twitter field must be present.'
            ]);

        $this->assertDatabaseEmpty('users');
    }
}
