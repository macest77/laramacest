<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_lastdraw_route_set()
    {
        $response = $this->get('/api/getlastdraw');
        $response->assertStatus(200);
    }

    public function test_draw_route_set()
    {
        $response = $this->get('/api/getdraw/20220719');
        $response->assertStatus(200);
    }

    public function test_laststanding_route_set()
    {
        $response = $this->get('/api/getlaststanding');
        $response->assertStatus(200);
    }

    public function test_standing_route_set()
    {
        $response = $this->get('/api/getstanding/8');
        $response->assertStatus(200);
    }
}
