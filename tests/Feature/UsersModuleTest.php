<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /** @test */
    public function it_shows_the_users_list()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('Ana')
            ->assertSee('Pedro');
    }

    /** @test */
    public function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('usuarios?empty')
            ->assertStatus(200)
            ->assertSee('Lista de Usuarios')
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    public function it_loads_user_details()
    {
        $this->get('usuarios/7')
            ->assertStatus(200)
            ->assertSee('Mostrando los detalles del usuario 7');
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
