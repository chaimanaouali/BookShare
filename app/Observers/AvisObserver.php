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
        // Generate recommendations when a user gives a good rating (4+ stars)
        if ($avis->note >= 4) {
            // Resolve the service from the container to avoid constructor injection issues in observers
            app(RecommendationService::class)->generateRecommendationsOnRating($avis);
        }
    }

    /**
     * Handle the Avis "updated" event.
     */
    public function updated(Avis $avis): void
    {
        // Generate recommendations if rating was updated to 4+ stars
        if ($avis->note >= 4 && $avis->wasChanged('note')) {
            app(RecommendationService::class)->generateRecommendationsOnRating($avis);
        }
    }

    /**
     * Handle the Avis "deleted" event.
     */
    public function deleted(Avis $avis): void
    {
        //
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
