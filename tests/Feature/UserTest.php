<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /** @test */
    public function it_loads_the_users_list_page()
    {
        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSee('usuarios');
    }
}
