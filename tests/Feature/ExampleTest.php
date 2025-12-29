<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_route_redirects_properly()
    {
        $user = \App\Models\User::factory()->create();

        $this->actingAs($user)
            ->withoutMiddleware()
            ->get('/')
            ->assertStatus(302);
    }   

}
