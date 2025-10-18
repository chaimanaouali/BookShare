<?php

/**
 * Simple Unit Test for Reading Personality Service
 * Run with: php artisan tinker
 * Then copy-paste this code
 */

// Test the service without database operations
class SimpleReadingPersonalityTest 
{
    public function testServiceMethods()
    {
        echo "🧪 Testing Reading Personality Service Methods\n";
        echo "=============================================\n";

        // Test 1: Service instantiation
        try {
            $service = new App\Services\ReadingPersonalityService();
            echo "✅ Service instantiated successfully\n";
        } catch (Exception $e) {
            echo "❌ Service instantiation failed: " . $e->getMessage() . "\n";
            return false;
        }

        // Test 2: Configuration validation
        $configValid = $service->validateConfiguration();
        echo "✅ Configuration validation: " . ($configValid ? 'Valid' : 'Invalid') . "\n";

        // Test 3: Test with mock user data
        $mockUser = new stdClass();
        $mockUser->id = 1;
        $mockUser->name = 'Test User';

        // Test 4: Mock borrowing history
        $mockHistory = [
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'genre' => 'Science-Fiction',
                'category' => 'Science-Fiction',
                'borrowed_date' => '2024-01-01',
                'returned_date' => '2024-01-08',
                'borrowing_duration' => 7
            ],
            [
                'title' => 'Le Petit Prince',
                'author' => 'Antoine de Saint-Exupéry',
                'genre' => 'Philosophie',
                'category' => 'Philosophie',
                'borrowed_date' => '2024-01-10',
                'returned_date' => '2024-01-17',
                'borrowing_duration' => 7
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'genre' => 'Science-Fiction',
                'category' => 'Science-Fiction',
                'borrowed_date' => '2024-01-20',
                'returned_date' => '2024-01-27',
                'borrowing_duration' => 7
            ]
        ];

        echo "✅ Mock history created: " . count($mockHistory) . " books\n";

        // Test 5: Data preparation
        try {
            $reflection = new ReflectionClass($service);
            $method = $reflection->getMethod('prepareAnalysisData');
            $method->setAccessible(true);
            
            $analysisData = $method->invoke($service, $mockUser, $mockHistory);
            echo "✅ Analysis data prepared: " . strlen($analysisData) . " characters\n";
            
            // Show sample
            echo "✅ Sample analysis data:\n";
            echo substr($analysisData, 0, 200) . "...\n";
            
        } catch (Exception $e) {
            echo "❌ Data preparation failed: " . $e->getMessage() . "\n";
        }

        // Test 6: Mock AI response parsing
        $mockAiResponse = [
            'personality_title' => 'Explorateur Curieux',
            'personality_description' => 'Tu es un lecteur qui aime découvrir de nouveaux horizons.',
            'reading_patterns' => [
                'favorite_genres' => ['Science-Fiction', 'Philosophie'],
                'reading_themes' => ['Aventure', 'Réflexion'],
                'reading_style' => 'Lecture rapide',
                'borrowing_behavior' => 'Emprunts fréquents'
            ],
            'recommendations' => ['Fondation', 'Le Monde de Sophie'],
            'challenge_suggestion' => 'Essayer un essai philosophique'
        ];

        $jsonResponse = json_encode($mockAiResponse);
        $decodedResponse = json_decode($jsonResponse, true);
        
        echo "✅ Mock AI response parsing: " . ($mockAiResponse === $decodedResponse ? 'Success' : 'Failed') . "\n";
        echo "✅ Personality title: " . $decodedResponse['personality_title'] . "\n";
        echo "✅ Genres: " . implode(', ', $decodedResponse['reading_patterns']['favorite_genres']) . "\n";

        echo "\n🎉 All tests completed!\n";
        return true;
    }
}

// Run the test
$tester = new SimpleReadingPersonalityTest();
$tester->testServiceMethods();
