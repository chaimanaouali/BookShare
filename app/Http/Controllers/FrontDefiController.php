<?php

namespace App\Http\Controllers;

use App\Models\Defi;
use App\Models\ParticipationDefi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontDefiController extends Controller
{
    /**
     * Display a listing of available défis for front-end users
     */
    public function index()
    {
        $defis = Defi::withCount('livres')
            ->whereHas('livres') // Only show défis that have books
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('front.defis.index', compact('defis'));
    }

    /**
     * Display the specified défi with participation option
     */
    public function show(Defi $defi)
    {
        $defi->load('livres');
        
        // Check if user is already participating in this défi
        $userParticipation = null;
        if (Auth::check()) {
            $userParticipation = ParticipationDefi::where('user_id', Auth::id())
                ->where('defi_id', $defi->id)
                ->first();
        }

        return view('front.defis.show', compact('defi', 'userParticipation'));
    }
}



