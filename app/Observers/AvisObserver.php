<?php

namespace App\Observers;

use App\Models\Avis;
use App\Services\RecommendationService;

class AvisObserver
{

    /**
     * Handle the Avis "created" event.
     */
    public function created(Avis $avis): void
    {
        $recommendationService = app(RecommendationService::class);
        
        // Remove any existing recommendations for this book since user has now reviewed it
        $recommendationService->removeRecommendationsForBook($avis->utilisateur, $avis->livre);
        
        // Generate recommendations when a user gives a good rating (4+ stars)
        if ($avis->note >= 4) {
            $recommendationService->generateRecommendationsOnRating($avis);
        }
    }

    /**
     * Handle the Avis "updated" event.
     */
    public function updated(Avis $avis): void
    {
        $recommendationService = app(RecommendationService::class);
        
        // If the rating was changed, remove existing recommendations for this book
        if ($avis->wasChanged('note')) {
            $recommendationService->removeRecommendationsForBook($avis->utilisateur, $avis->livre);
            
            // Generate recommendations if rating was updated to 4+ stars
            if ($avis->note >= 4) {
                $recommendationService->generateRecommendationsOnRating($avis);
            }
        }
    }

    /**
     * Handle the Avis "deleted" event.
     */
    public function deleted(Avis $avis): void
    {
        // Remove any existing recommendations for this book since the review was deleted
        app(RecommendationService::class)->removeRecommendationsForBook($avis->utilisateur, $avis->livre);
    }

    /**
     * Handle the Avis "restored" event.
     */
    public function restored(Avis $avis): void
    {
        //
    }

    /**
     * Handle the Avis "force deleted" event.
     */
    public function forceDeleted(Avis $avis): void
    {
        //
    }
}
