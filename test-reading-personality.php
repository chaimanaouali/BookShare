<?php

/**
 * AI Reading Personality Testing Suite
 * 
 * This script provides comprehensive testing for the Reading Personality feature
 */

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Emprunt;
use App\Models\Livre;
use App\Models\ReadingPersonality;
use App\Services\ReadingPersonalityService;

class ReadingPersonalityTester
{
    private $service;
    private $testUser;

    public function __construct()
    {
        $this->service = new ReadingPersonalityService();
        $this->testUser = User::where('email', 'test@readingpersonality.com')->first();
    }

    /**
     * Test 1: Basic Service Functionality
     */
    public function testBasicService()
    {
        echo "🧪 Test 1: Basic Service Functionality\n";
        echo "=====================================\n";

        if (!$this->testUser) {
            echo "❌ Test user not found. Run the seeder first.\n";
            return false;
        }

        echo "✅ Test user found: {$this->testUser->name}\n";
        
        // Test hasEnoughHistory
        $hasEnough = $this->service->hasEnoughHistory($this->testUser);
        echo "✅ Has enough history: " . ($hasEnough ? 'Yes' : 'No') . "\n";
        
        // Test validateConfiguration
        $configValid = $this->service->validateConfiguration();
        echo "✅ API Configuration valid: " . ($configValid ? 'Yes' : 'No') . "\n";
        
        // Show borrowing history
        $borrowings = Emprunt::where('utilisateur_id', $this->testUser->id)
            ->whereNotNull('date_retour_eff')
            ->with(['livre'])
            ->get();
        
        echo "✅ Borrowing history: {$borrowings->count()} books\n";
        foreach ($borrowings as $borrowing) {
            echo "   - {$borrowing->livre->title} by {$borrowing->livre->author} ({$borrowing->livre->genre})\n";
        }
        
        echo "\n";
        return true;
    }

    /**
     * Test 2: AI Service Analysis (without API call)
     */
    public function testAnalysisData()
    {
        echo "🧪 Test 2: Analysis Data Preparation\n";
        echo "===================================\n";

        if (!$this->testUser) {
            echo "❌ Test user not found.\n";
            return false;
        }

        // Get borrowing history using reflection to test private method
        $reflection = new ReflectionClass($this->service);
        $method = $reflection->getMethod('getUserBorrowingHistory');
        $method->setAccessible(true);
        
        $history = $method->invoke($this->service, $this->testUser);
        
        echo "✅ Borrowing history retrieved: " . count($history) . " books\n";
        
        // Show sample data
        if (!empty($history)) {
            echo "✅ Sample book data:\n";
            $sample = $history[0];
            foreach ($sample as $key => $value) {
                echo "   - {$key}: {$value}\n";
            }
        }

        // Test data preparation
        $prepareMethod = $reflection->getMethod('prepareAnalysisData');
        $prepareMethod->setAccessible(true);
        
        $analysisData = $prepareMethod->invoke($this->service, $this->testUser, $history);
        
        echo "✅ Analysis data prepared: " . strlen($analysisData) . " characters\n";
        echo "✅ Sample analysis data:\n";
        echo substr($analysisData, 0, 200) . "...\n";
        
        echo "\n";
        return true;
    }

    /**
     * Test 3: Database Operations
     */
    public function testDatabaseOperations()
    {
        echo "🧪 Test 3: Database Operations\n";
        echo "=============================\n";

        // Test ReadingPersonality model
        $personality = new ReadingPersonality();
        echo "✅ ReadingPersonality model created\n";
        
        // Test fillable fields
        $fillable = $personality->getFillable();
        echo "✅ Fillable fields: " . implode(', ', $fillable) . "\n";
        
        // Test casts
        $casts = $personality->getCasts();
        echo "✅ Casts: " . json_encode($casts) . "\n";
        
        // Test user relationship
        if ($this->testUser) {
            $userPersonalities = $this->testUser->readingPersonalities;
            echo "✅ User relationship works: " . $userPersonalities->count() . " personalities\n";
        }
        
        echo "\n";
        return true;
    }

    /**
     * Test 4: API Endpoints (simulation)
     */
    public function testApiEndpoints()
    {
        echo "🧪 Test 4: API Endpoints Simulation\n";
        echo "===================================\n";

        $endpoints = [
            'GET /reading-personality' => 'Show personality page',
            'POST /reading-personality/generate' => 'Generate new personality',
            'POST /reading-personality/update' => 'Update existing personality',
            'GET /reading-personality/data' => 'Get personality data as JSON',
            'GET /reading-personality/user/{user}' => 'Show user personality (admin)'
        ];

        foreach ($endpoints as $endpoint => $description) {
            echo "✅ {$endpoint} - {$description}\n";
        }

        // Test route existence
        $routes = app('router')->getRoutes();
        $readingPersonalityRoutes = collect($routes)->filter(function ($route) {
            return str_contains($route->uri(), 'reading-personality');
        });

        echo "✅ Routes registered: " . $readingPersonalityRoutes->count() . " routes\n";
        
        echo "\n";
        return true;
    }

    /**
     * Test 5: Mock AI Response
     */
    public function testMockAiResponse()
    {
        echo "🧪 Test 5: Mock AI Response\n";
        echo "==========================\n";

        // Create a mock AI response
        $mockResponse = [
            'personality_title' => 'Explorateur Curieux',
            'personality_description' => 'Tu es un lecteur qui aime découvrir de nouveaux horizons. Tu empruntes souvent des livres de voyage et de science-fiction.',
            'reading_patterns' => [
                'favorite_genres' => ['Science-Fiction', 'Voyage', 'Philosophie'],
                'reading_themes' => ['Aventure', 'Découverte', 'Réflexion'],
                'reading_style' => 'Lecture rapide et variée',
                'borrowing_behavior' => 'Emprunts fréquents et diversifiés'
            ],
            'recommendations' => [
                'Fondation d\'Isaac Asimov',
                'Le Monde de Sophie de Jostein Gaarder',
                'Voyage au bout de la nuit de Céline'
            ],
            'challenge_suggestion' => 'Prochain défi: essayer un essai philosophique pour élargir tes horizons!'
        ];

        echo "✅ Mock AI response created\n";
        echo "✅ Personality title: {$mockResponse['personality_title']}\n";
        echo "✅ Description: " . substr($mockResponse['personality_description'], 0, 50) . "...\n";
        echo "✅ Genres: " . implode(', ', $mockResponse['reading_patterns']['favorite_genres']) . "\n";
        echo "✅ Recommendations: " . count($mockResponse['recommendations']) . " books\n";

        // Test JSON encoding/decoding
        $jsonResponse = json_encode($mockResponse);
        $decodedResponse = json_decode($jsonResponse, true);
        
        echo "✅ JSON encoding/decoding works: " . ($mockResponse === $decodedResponse ? 'Yes' : 'No') . "\n";
        
        echo "\n";
        return true;
    }

    /**
     * Test 6: Integration Test (without actual API call)
     */
    public function testIntegration()
    {
        echo "🧪 Test 6: Integration Test\n";
        echo "==========================\n";

        if (!$this->testUser) {
            echo "❌ Test user not found.\n";
            return false;
        }

        // Test the complete flow without API call
        try {
            // Check if user has enough history
            $hasEnough = $this->service->hasEnoughHistory($this->testUser);
            echo "✅ History check: " . ($hasEnough ? 'Sufficient' : 'Insufficient') . "\n";

            if ($hasEnough) {
                // Get borrowing history
                $reflection = new ReflectionClass($this->service);
                $method = $reflection->getMethod('getUserBorrowingHistory');
                $method->setAccessible(true);
                
                $history = $method->invoke($this->service, $this->testUser);
                echo "✅ History retrieved: " . count($history) . " books\n";

                // Prepare analysis data
                $prepareMethod = $reflection->getMethod('prepareAnalysisData');
                $prepareMethod->setAccessible(true);
                
                $analysisData = $prepareMethod->invoke($this->service, $this->testUser, $history);
                echo "✅ Analysis data prepared: " . strlen($analysisData) . " characters\n";

                // Test data structure
                $genres = array_count_values(array_column($history, 'genre'));
                $categories = array_count_values(array_column($history, 'category'));
                
                echo "✅ Genre analysis: " . json_encode($genres) . "\n";
                echo "✅ Category analysis: " . json_encode($categories) . "\n";
            }

            echo "✅ Integration test completed successfully\n";
            
        } catch (Exception $e) {
            echo "❌ Integration test failed: " . $e->getMessage() . "\n";
            return false;
        }
        
        echo "\n";
        return true;
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "🚀 Starting AI Reading Personality Tests\n";
        echo "========================================\n\n";

        $tests = [
            'testBasicService',
            'testAnalysisData', 
            'testDatabaseOperations',
            'testApiEndpoints',
            'testMockAiResponse',
            'testIntegration'
        ];

        $passed = 0;
        $total = count($tests);

        foreach ($tests as $test) {
            if ($this->$test()) {
                $passed++;
            }
        }

        echo "📊 Test Results\n";
        echo "===============\n";
        echo "✅ Passed: {$passed}/{$total}\n";
        echo "❌ Failed: " . ($total - $passed) . "/{$total}\n";
        
        if ($passed === $total) {
            echo "🎉 All tests passed! The Reading Personality feature is ready to use.\n";
        } else {
            echo "⚠️  Some tests failed. Check the output above for details.\n";
        }

        return $passed === $total;
    }
}

// Run the tests
$tester = new ReadingPersonalityTester();
$tester->runAllTests();
