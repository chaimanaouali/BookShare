<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\ReadingPersonality;
use App\Models\Categorie;
use App\Services\ReadingPersonalityService;
use Carbon\Carbon;

class ReadingPersonalityTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $testUser;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->testUser = User::factory()->create([
            'email' => 'test@readingpersonality.com',
            'name' => 'Test Reader',
            'role' => 'user'
        ]);

        // Create test categories
        $categories = [
            ['nom' => 'Science-Fiction', 'description' => 'Livres de science-fiction'],
            ['nom' => 'Voyage', 'description' => 'Livres de voyage'],
            ['nom' => 'Philosophie', 'description' => 'Essais philosophiques'],
        ];

        foreach ($categories as $categoryData) {
            Categorie::create($categoryData);
        }

        // Create test books
        $books = [
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'genre' => 'Science-Fiction',
                'description' => 'Une épopée spatiale',
                'categorie_id' => Categorie::where('nom', 'Science-Fiction')->first()->id,
                'user_id' => $this->testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'genre' => 'Philosophie',
                'description' => 'Un conte philosophique',
                'categorie_id' => Categorie::where('nom', 'Philosophie')->first()->id,
                'user_id' => $this->testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
            [
                'title' => 'Voyage au centre de la Terre',
                'author' => 'Jules Verne',
                'genre' => 'Voyage',
                'description' => 'Une aventure extraordinaire',
                'categorie_id' => Categorie::where('nom', 'Voyage')->first()->id,
                'user_id' => $this->testUser->id,
                'disponibilite' => true,
                'visibilite' => 'public'
            ],
        ];

        foreach ($books as $bookData) {
            Livre::create($bookData);
        }

        // Create borrowing history
        $books = Livre::where('user_id', $this->testUser->id)->get();
        $borrowingDates = [
            Carbon::now()->subDays(30),
            Carbon::now()->subDays(20),
            Carbon::now()->subDays(10),
        ];

        foreach ($borrowingDates as $index => $borrowDate) {
            if ($index < $books->count()) {
                $book = $books[$index];
                $returnDate = $borrowDate->copy()->addDays(7);
                
                Emprunt::create([
                    'utilisateur_id' => $this->testUser->id,
                    'livre_id' => $book->id,
                    'date_emprunt' => $borrowDate,
                    'date_retour_prev' => $borrowDate->copy()->addDays(14),
                    'date_retour_eff' => $returnDate,
                    'statut' => 'retourne',
                    'penalite' => 0,
                    'commentaire' => 'Test borrowing'
                ]);
            }
        }

        $this->service = new ReadingPersonalityService();
    }

    /** @test */
    public function test_user_has_enough_borrowing_history()
    {
        $hasEnough = $this->service->hasEnoughHistory($this->testUser);
        $this->assertTrue($hasEnough);
    }

    /** @test */
    public function test_reading_personality_model_creation()
    {
        $personality = ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'Test Personality',
            'personality_description' => 'Test description',
            'reading_patterns' => [
                'favorite_genres' => ['Science-Fiction'],
                'reading_themes' => ['Aventure'],
                'reading_style' => 'Rapide',
                'borrowing_behavior' => 'Fréquent'
            ],
            'recommendations' => ['Book 1', 'Book 2'],
            'challenge_suggestion' => 'Try philosophy',
            'books_analyzed' => 3,
            'last_updated' => now()
        ]);

        $this->assertDatabaseHas('reading_personalities', [
            'user_id' => $this->testUser->id,
            'personality_title' => 'Test Personality'
        ]);

        $this->assertEquals('Test Personality', $personality->personality_title);
        $this->assertIsArray($personality->reading_patterns);
        $this->assertIsArray($personality->recommendations);
    }

    /** @test */
    public function test_user_relationship_with_reading_personality()
    {
        $personality = ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'Test Personality',
            'personality_description' => 'Test description',
            'reading_patterns' => [],
            'recommendations' => [],
            'challenge_suggestion' => 'Test challenge',
            'books_analyzed' => 3,
            'last_updated' => now()
        ]);

        $this->assertEquals($this->testUser->id, $personality->user->id);
        $this->assertCount(1, $this->testUser->readingPersonalities);
    }

    /** @test */
    public function test_reading_personality_show_page_loads()
    {
        $response = $this->actingAs($this->testUser)
            ->get('/reading-personality');

        $response->assertStatus(200);
        $response->assertViewIs('content.reading-personality.show');
    }

    /** @test */
    public function test_reading_personality_data_endpoint()
    {
        $response = $this->actingAs($this->testUser)
            ->get('/reading-personality/data');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'personality',
            'has_enough_history',
            'needs_update'
        ]);

        $data = $response->json();
        $this->assertTrue($data['has_enough_history']);
    }

    /** @test */
    public function test_reading_personality_generate_endpoint_without_api_key()
    {
        // Mock the service to avoid actual API call
        $mockService = $this->createMock(ReadingPersonalityService::class);
        $mockService->method('validateConfiguration')->willReturn(false);
        
        $this->app->instance(ReadingPersonalityService::class, $mockService);

        $response = $this->actingAs($this->testUser)
            ->post('/reading-personality/generate');

        $response->assertStatus(500);
        $response->assertJson([
            'success' => false,
            'error' => 'Configuration API manquante. Veuillez contacter l\'administrateur.'
        ]);
    }

    /** @test */
    public function test_reading_personality_generate_endpoint_with_insufficient_history()
    {
        // Create a user with no borrowing history
        $newUser = User::factory()->create();
        
        $response = $this->actingAs($newUser)
            ->post('/reading-personality/generate');

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'error' => 'Pas assez d\'historique d\'emprunts pour générer un profil. Empruntez au moins 3 livres d\'abord!'
        ]);
    }

    /** @test */
    public function test_borrowing_history_analysis()
    {
        $reflection = new \ReflectionClass($this->service);
        $method = $reflection->getMethod('getUserBorrowingHistory');
        $method->setAccessible(true);
        
        $history = $method->invoke($this->service, $this->testUser);
        
        $this->assertIsArray($history);
        $this->assertGreaterThanOrEqual(3, count($history));
        
        // Check structure of history data
        if (!empty($history)) {
            $firstBook = $history[0];
            $this->assertArrayHasKey('title', $firstBook);
            $this->assertArrayHasKey('author', $firstBook);
            $this->assertArrayHasKey('genre', $firstBook);
            $this->assertArrayHasKey('borrowed_date', $firstBook);
        }
    }

    /** @test */
    public function test_analysis_data_preparation()
    {
        $reflection = new \ReflectionClass($this->service);
        $historyMethod = $reflection->getMethod('getUserBorrowingHistory');
        $historyMethod->setAccessible(true);
        
        $prepareMethod = $reflection->getMethod('prepareAnalysisData');
        $prepareMethod->setAccessible(true);
        
        $history = $historyMethod->invoke($this->service, $this->testUser);
        $analysisData = $prepareMethod->invoke($this->service, $this->testUser, $history);
        
        $this->assertIsString($analysisData);
        $this->assertStringContainsString($this->testUser->name, $analysisData);
        $this->assertStringContainsString('Historique d\'emprunts', $analysisData);
    }

    /** @test */
    public function test_reading_personality_needs_update()
    {
        $personality = ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'Test Personality',
            'personality_description' => 'Test description',
            'reading_patterns' => [],
            'recommendations' => [],
            'challenge_suggestion' => 'Test challenge',
            'books_analyzed' => 3,
            'last_updated' => now()->subDays(35) // 35 days ago
        ]);

        $this->assertTrue($personality->needsUpdate());
    }

    /** @test */
    public function test_reading_personality_does_not_need_update()
    {
        $personality = ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'Test Personality',
            'personality_description' => 'Test description',
            'reading_patterns' => [],
            'recommendations' => [],
            'challenge_suggestion' => 'Test challenge',
            'books_analyzed' => 3,
            'last_updated' => now()->subDays(10) // 10 days ago
        ]);

        $this->assertFalse($personality->needsUpdate());
    }

    /** @test */
    public function test_latest_personality_scope()
    {
        // Create multiple personalities for the same user
        ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'Old Personality',
            'personality_description' => 'Old description',
            'reading_patterns' => [],
            'recommendations' => [],
            'challenge_suggestion' => 'Old challenge',
            'books_analyzed' => 3,
            'last_updated' => now()->subDays(10)
        ]);

        ReadingPersonality::create([
            'user_id' => $this->testUser->id,
            'personality_title' => 'New Personality',
            'personality_description' => 'New description',
            'reading_patterns' => [],
            'recommendations' => [],
            'challenge_suggestion' => 'New challenge',
            'books_analyzed' => 3,
            'last_updated' => now()
        ]);

        $latestPersonality = ReadingPersonality::latestForUser($this->testUser->id)->first();
        
        $this->assertEquals('New Personality', $latestPersonality->personality_title);
    }
}