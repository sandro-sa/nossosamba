<?php
namespace Tests\Feature\Api\MusicApp;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function teste_create_note(): void
    {
        $response = $this->post('/');

        $response->assertStatus(200);
    }


}
