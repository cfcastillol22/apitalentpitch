<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Video;
use Tymon\JWTAuth\Facades\JWTAuth;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
        $this->artisan('migrate');
    }

    public function testIndexWithValidToken()
    {

        Video::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/videos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => ['id', 'description', 'url', 'logo', 'user_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testIndexWithInvalidToken()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->getJson('/api/videos');

        $response->assertStatus(401);
    }

    public function testIndexWithoutToken()
    {
        $response = $this->getJson('/api/videos');

        $response->assertStatus(401);
    }

    public function testStoreWithValidToken()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/videos', [
            'description' => 'New Video Description',
            'url' => 'https://example.com/video',
            'logo' => 'https://example.com/logo'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'description' => 'New Video Description',
                'url' => 'https://example.com/video',
                'logo' => 'https://example.com/logo',
                'user_id' => $this->user->id
            ]);


        $this->assertDatabaseHas('videos', [
            'description' => 'New Video Description',
            'url' => 'https://example.com/video',
            'logo' => 'https://example.com/logo',
            'user_id' => $this->user->id
        ]);
    }

    public function testStoreWithoutToken()
    {
        $response = $this->postJson('/api/videos', [
            'description' => 'New Video Description',
            'url' => 'https://example.com/video',
            'logo' => 'https://example.com/logo'
        ]);

        $response->assertStatus(401);
    }

    public function testShowWithValidToken()
    {

        $video = Video::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/videos/{$video->id}");

        $response->assertStatus(200)
            ->assertJson([
                'description' => $video->description,
                'url' => $video->url,
                'logo' => $video->logo,
                'user_id' => $video->user_id
            ]);
    }

    public function testShowWithInvalidToken()
    {
        $video = Video::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->getJson("/api/videos/{$video->id}");

        $response->assertStatus(401);
    }

    public function testUpdateWithValidToken()
    {
        $video = Video::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/videos/{$video->id}", [
            'description' => 'Updated Video Description',
            'url' => 'https://example.com/updated-video',
            'logo' => 'https://example.com/updated-logo'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'description' => 'Updated Video Description',
                'url' => 'https://example.com/updated-video',
                'logo' => 'https://example.com/updated-logo'
            ]);

        $this->assertDatabaseHas('videos', [
            'id' => $video->id,
            'description' => 'Updated Video Description',
            'url' => 'https://example.com/updated-video',
            'logo' => 'https://example.com/updated-logo'
        ]);
    }

    public function testDestroyWithValidToken()
    {
        $video = Video::factory()->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/videos/{$video->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('videos', [
            'id' => $video->id
        ]);
    }
}
