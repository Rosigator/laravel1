<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User as User;
use App\Profession as Profession;
use Illuminate\Support\Facades\DB as DB;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Ana',
            'profession_id' => null
        ]);

        factory(User::class)->create([
            'name' => 'Pedro',
            'profession_id' => null
        ]);

        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('Ana')
            ->assertSee('Pedro');
    }

    /** @test */
    public function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    public function it_displays_user_details_with_profession()
    {
        $profession = factory(Profession::class)->create([
            'title' => 'pajillero'
        ]);

        $user = factory(User::class)->create([
            'name' => 'Sir Cums Alot',
            'email' => 'sircum@mail.com',
            'profession_id' => $profession->id
        ]);

        $this->get('usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('Email: ' . $user->email)
            ->assertSee('Profesión: ' . $profession->title);
    }

    /** @test */
    public function it_displays_user_details_without_profession()
    {
        $user = factory(User::class)->create([
            'name' => 'Sir Cums Alot',
            'email' => 'sircum@mail.com'
        ]);

        $this->get('usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('Email: ' . $user->email)
            ->assertSee('Profesión: ');
    }

    /** @test */
    public function it_displays_a_404_error_if_user_is_not_found()
    {
        $this->get('usuarios/1')
            ->assertStatus(404)
            ->assertSee('Lo sentimos, el usuario no ha sido encontrado');
    }

    /** @test */
    public function it_loads_user_creation_page()
    {
        $this->get('usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Creación de nuevo usuario');
    }

    /** @test */
    public function it_creates_a_new_user()
    {

        $profession = factory(Profession::class)->create([
            'title' => 'mongoloide profesional'
        ]);

        $this->post('usuarios/crear', [
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'profession' => $profession->title,
            'password' => 'secret'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Fernando Contreras',
            'email' => 'fernando@mail.com',
            'is_admin' => 0,
            'profession_id' => $profession->id,
            'password' => 'secret'
        ]);
    }

    /** @test */
    public function the_name_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => '',
            'email' => 'floripondio@mail.com',
            'password' => 'secret'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'name' => 'The name field is required.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_email_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => '',
            'password' => 'secret'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email field is required.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_password_field_is_required()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => 'tropofosio@mail.com',
            'password' => ''
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'password' => 'The password field is required.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => 'asdf',
            'password' => 'secret'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email must be a valid email address.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_email_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'cojonero@mail.com'
        ]);

        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => 'cojonero@mail.com',
            'password' => 'secret'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'email' => 'The email has already been taken.'
            ]);

        $this->assertEquals(1, User::count());
    }

    /** @test */
    public function the_password_field_must_be_at_least_6_chars()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => 'tropofosio@mail.com',
            'password' => '123'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'password' => 'The password must be at least 6 characters.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function the_profession_field_value_exists()
    {
        $this->from('usuarios/nuevo')->post('usuarios/crear', [
            'name' => 'Tropofosio Filibusteo',
            'email' => 'tropofosio@mail.com',
            'password' => '123456',
            'profession' => 'asdf'
        ])
            ->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors([
                'profession' => 'The selected profession is invalid.'
            ]);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    public function it_loads_user_edit_page()
    {
        $user = factory(User::class)->create();

        $this->get("usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee("Editando información del usuario $user->id")
            ->assertViewHas('user', function ($viewUser) use ($user) {
                return $viewUser->id === $user->id;
            });
    }

    /** @test */
    public function it_updates_a_user()
    {
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->post('usuarios/crear', [
                'name' => 'Julio Gómez',
                'email' => 'julio@mail.com',
                'password' => '123456'
            ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'Julio Gómez',
            'email' => 'julio@mail.com',
            'is_admin' => 0,
            'password' => '123456'
        ]);
    }
}
