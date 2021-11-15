<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /** @test */
    public function it_loads_the_users_list_page()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('Estás viendo los usuarios');
    }

    /** @test */
    public function it_loads_user_details()
    {
        $this->get('usuarios/7')
            ->assertStatus(200)
            ->assertSee('Mostrando detalles del usuario 7');
    }
}
