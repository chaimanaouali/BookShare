<?php

// Quick test script for Reading Personality
// Run: php test-quick.php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Services\ReadingPersonalityService;

echo "🧪 Quick Reading Personality Test\n";
echo "=================================\n";

// Test 1: Check test user
$testUser = User::where('email', 'test@readingpersonality.com')->first();
if ($testUser) {
    echo "✅ Test user found: {$testUser->name}\n";
} else {
    echo "❌ Test user not found\n";
    exit;
}

// Test 2: Check borrowing history
$borrowings = \App\Models\Emprunt::where('utilisateur_id', $testUser->id)
    ->whereNotNull('date_retour_eff')
    ->count();
echo "✅ Completed borrowings: {$borrowings}\n";

// Test 3: Test service
$service = new ReadingPersonalityService();
$hasEnough = $service->hasEnoughHistory($testUser);
echo "✅ Has enough history: " . ($hasEnough ? 'Yes' : 'No') . "\n";

// Test 4: Check API configuration
$configValid = $service->validateConfiguration();
echo "✅ API config valid: " . ($configValid ? 'Yes' : 'No') . "\n";

// Test 5: Show borrowing details
$borrowings = \App\Models\Emprunt::where('utilisateur_id', $testUser->id)
    ->whereNotNull('date_retour_eff')
    ->with(['livre'])
    ->get();

echo "✅ Borrowing details:\n";
foreach ($borrowings as $borrowing) {
    echo "   - {$borrowing->livre->title} by {$borrowing->livre->author} ({$borrowing->livre->genre})\n";
}

echo "\n🎯 Ready to test!\n";
echo "1. Go to: http://localhost:8000/auth\n";
echo "2. Login with: test@readingpersonality.com / password\n";
echo "3. Navigate to 'Mon Profil IA'\n";
echo "4. Click 'Générer mon profil IA'\n";

if (!$configValid) {
    echo "\n⚠️ Note: Add GEMINI_API_KEY to .env file for real AI generation\n";
}
