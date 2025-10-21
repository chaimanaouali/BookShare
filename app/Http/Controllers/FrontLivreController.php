<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Emprunt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontLivreController extends Controller
{
    /**
     * Display the specified livre.
     */
    public function show($id)
    {
        try {
            $livre = Livre::findOrFail($id);
            // Load relationships
            $livre->load(['bibliotheque.user', 'avis.user', 'categorie']);
            
            return view('front.livres.show', compact('livre'));
        } catch (\Exception $e) {
            return response('Error: ' . $e->getMessage(), 500);
        }
    }
}
