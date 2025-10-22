<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ReadingPersonality;
use App\Services\ReadingPersonalityService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReadingPersonalityController extends Controller
{
    protected $readingPersonalityService;

    public function __construct(ReadingPersonalityService $readingPersonalityService)
    {
        $this->readingPersonalityService = $readingPersonalityService;
    }

    /**
     * Display the reading personality for the authenticated user
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get existing personality
        $personality = $this->readingPersonalityService->getReadingPersonality($user);
        
        // Check if user has enough borrowing history
        $hasEnoughHistory = $this->readingPersonalityService->hasEnoughHistory($user);
        
        return view('content.reading-personality.show', compact('personality', 'hasEnoughHistory'));
    }

    /**
     * Generate a new reading personality for the authenticated user
     */
    public function getPersonalityData($userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    $personality = $user->readingPersonality;
    return response()->json([
        'success' => $personality ? true : false,
        'personality' => $personality
    ]);
}

public function generate($userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    $personality = $this->aiService->generateProfile($user);
    return response()->json(['success' => true, 'personality' => $personality]);
}

public function update($userId)
{
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not found']);
    }

    // logic to refresh or modify personality
}

}