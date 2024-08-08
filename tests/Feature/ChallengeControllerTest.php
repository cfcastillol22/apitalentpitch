<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Challenge;
use Tymon\JWTAuth\Facades\JWTAuth;

class ChallengeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario y generar un token para pruebas
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);

        // Correr migraciones
        $this->artisan('migrate');
    }

    public function testIndexWithValidToken()
    {
        // Crear algunos datos para la prueba
        Challenge::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/challenges');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    '*' => ['id', 'title', 'description', 'difficulty', 'user_id', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function testIndexWithInvalidToken()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->getJson('/api/challenges');

        $response->assertStatus(401);
    }

    public function testIndexWithoutToken()
    {
        $response = $this->getJson('/api/challenges');

        $response->assertStatus(401);
    }

    public function testStoreWithValidToken()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/challenges', [
            'title' => 'New Challenge',
            'description' => 'Challenge description',
            'difficulty' => 3
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'New Challenge',
                'description' => 'Challenge description',
                'difficulty' => 3,
                'user_id' => $this->user->id
            ]);

        // Verificar que los datos se han guardado en la base de datos
        $this->assertDatabaseHas('challenges', [
            'title' => 'New Challenge',
            'description' => 'Challenge description',
            'difficulty' => 3,
            'user_id' => $this->user->id
        ]);
    }

    public function testStoreWithoutToken()
    {
        $response = $this->postJson('/api/challenges', [
            'title' => 'New Challenge',
            'description' => 'Challenge description',
            'difficulty' => 3
        ]);

        $response->assertStatus(401);
    }
}
