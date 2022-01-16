<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User as User;
use App\Profession as Profession;

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
    public function it_displays_user_details()
    {
        $profession = factory(Profession::class)->create([
            'title' => 'pajillero'
        ]);

        $user = factory(User::class)->create([
            'name' => 'Sir Cums Alot',
            'email' => 'sircum@mail.com',
            'profession_id' => 1
        ]);

        $this->get('usuarios/' . $user->id)
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee('Email: ' . $user->email)
            ->assertSee('Profesión: ' . $profession->title);
    }

    /** @test */
    public function it_displays_a_404_error_if_user_is_not_found()
    {
        $this->get('usuarios/1')
            ->assertStatus(404)
            ->assertSee('Lo sentimos, el usuario no ha sido encontrado');
    }

    /** @test */
    public function it_loads_user_edit_page()
    {
        $this->get('usuarios/7/editar')
            ->assertStatus(200)
            ->assertSee('Editando información del usuario 7');
    }

    /** @test */
    public function it_loads_user_creation_page()
    {
        $this->get('usuarios/crear')
            ->assertStatus(200)
            ->assertSee('Creando un usuario');
    }
}
