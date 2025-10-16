<?php

namespace App\Http\Controllers;

use App\Models\Recommendation;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display a listing of recommendations for the authenticated user
     */
    public function index()
    {
        $user = Auth::user();
        $recommendations = $this->recommendationService->getUserRecommendations($user, 20);
        
        return view('recommendations.index', compact('recommendations'));
    }

    /**
     * Generate new recommendations for the authenticated user
     */
    public function generate()
    {
        $user = Auth::user();
        
        // Generate AI recommendations
        $aiRecommendations = $this->recommendationService->generateAiRecommendations($user, 5);
        $this->recommendationService->saveRecommendations($aiRecommendations);
        
        // Generate collaborative recommendations
        $collaborativeRecommendations = $this->recommendationService->generateCollaborativeRecommendations($user, 3);
        $this->recommendationService->saveRecommendations($collaborativeRecommendations);
        
        // If asked, stay on home page (front) and flash a message
        if (request()->get('redirect') === 'home') {
            return redirect('/')->with('success', 'New recommendations have been generated!');
        }

        // Otherwise go to the backoffice recommendations index
        return redirect()->route('recommendations.index')
            ->with('success', 'New recommendations have been generated!');
    }

    /**
     * Mark a recommendation as viewed
     */
    public function markAsViewed(Recommendation $recommendation)
    {
        $recommendation->update(['is_viewed' => true]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Get recommendations by source (AI, collaborative, manual)
     */
    public function bySource($source)
    {
        $user = Auth::user();
        $recommendations = Recommendation::where('user_id', $user->id)
            ->where('source', $source)
            ->with(['livre.categorie', 'avis'])
            ->orderBy('score', 'desc')
            ->get();
        
        return view('recommendations.by-source', compact('recommendations', 'source'));
    }

    /**
     * Generate recommendations via AJAX and return JSON
     */
    public function generateAjax(): JsonResponse
    {
        $user = Auth::user();
        $ai = $this->recommendationService->generateAiRecommendations($user, 5);
        $this->recommendationService->saveRecommendations($ai);
        $collab = $this->recommendationService->generateCollaborativeRecommendations($user, 3);
        $this->recommendationService->saveRecommendations($collab);

        return response()->json(['success' => true]);
    }

    /**
     * List recommendations for the authenticated user as JSON
     */
    public function listForUser(): JsonResponse
    {
        $user = Auth::user();
        $items = Recommendation::where('user_id', $user->id)
            ->with(['livre:id,title,author,categorie_id', 'livre.categorie:id,nom'])
            ->orderBy('score', 'desc')
            ->limit(20)
            ->get();

        // Enrich payload with a flat category_name for easy front consumption
        $items->each(function ($rec) {
            $rec->category_name = optional(optional($rec->livre)->categorie)->nom;
        });

        return response()->json(['success' => true, 'data' => $items]);
    }
    /**
     * Get unviewed recommendations count for AJAX
     */
    public function unviewedCount()
    {
        $user = Auth::user();
        $count = Recommendation::where('user_id', $user->id)
            ->where('is_viewed', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Delete a recommendation
     */
    public function destroy(Recommendation $recommendation)
    {
        $recommendation->delete();
        
        return redirect()->route('recommendations.index')
            ->with('success', 'Recommendation removed successfully.');
    }
}
