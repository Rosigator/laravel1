<?php

namespace Tests\Feature\Admin;

use App\User;
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
        'profession_id' => null,
        'twitter' => 'https://twitter.com/fernando',
        'bio' => 'Soy un tío de puta madre.',
        'role' => 'user'
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

        $this->put("usuarios/{$user->id}", [
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ])->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'password' => 'secret',
        ]);
    }

    /** @test */
    public function the_name_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => '',
                'email' => 'floripondio@mail.com',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'floripondio@mail.com']);
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => '',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertDatabaseMissing('users', ['name' => 'Tropofosio Filibusteo']);
    }

    /** @test */
    public function the_password_field_is_optional()
    {
        $this->handleValidationExceptions();

        $old_password = 'clave_vieja';

        $user = factory(User::class)->create([
            'password' => bcrypt($old_password)
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'tropofosio@mail.com',
                'password' => ''
            ])
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Tropofosio Filibusteo',
            'email' => 'tropofosio@mail.com',
            'password' => $old_password
        ]);
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'asdf',
                'password' => 'secret'
            ])
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
            'email' => 'noexiste@mail.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'existe@mail.com',
                'password' => 'secret'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertDatabaseMissing('users', ['name' => 'Tropofosio Filibusteo']);
    }

    /** @test */
    public function the_profession_field_value_exists()
    {
        $this->handleValidationExceptions();

        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Tropofosio Filibusteo',
                'email' => 'tropofosio@mail.com',
                'password' => '123456',
                'profession' => 'asdf'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors([
                'profession' => 'The selected profession is invalid.'
            ]);

        $this->assertDatabaseMissing('users', ['email' => 'tropofosio@mail.com']);
    }

    /** @test */
    public function the_user_email_can_stay_the_same()
    {
        $this->handleValidationExceptions();

        $oldemail = 'oldemail@mail.com';

        $user = factory(User::class)->create([
            'email' => $oldemail
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("usuarios/{$user->id}", [
                'name' => 'Cocoloco Pérez',
                'email' => $oldemail,
                'password' => ''
            ])->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users', [
            'name' => 'Cocoloco Pérez',
            'email' => $oldemail
        ]);
    }
}
