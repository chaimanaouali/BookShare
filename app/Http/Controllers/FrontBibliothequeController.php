<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use Illuminate\Support\Facades\Auth;

class FrontBibliothequeController extends Controller
{
    // List all public bibliothèques
    public function index()
    {
        $bibliotheques = BibliothequeVirtuelle::whereHas('livreUtilisateurs', function($q) {
            $q->where('visibilite', 'public');
        })->withCount(['livreUtilisateurs' => function($q) {
            $q->where('visibilite', 'public');
        }])->with('user')->get();
        return view('front.bibliotheques.index', compact('bibliotheques'));
    }

    // Show a public bibliothèque and its public books
    public function show($id)
    {
        $bibliotheque = BibliothequeVirtuelle::with(['user', 'livreUtilisateurs' => function($q) {
            $q->where('visibilite', 'public')->with('livre');
        }])->findOrFail($id);
        return view('front.bibliotheques.show', compact('bibliotheque'));
    }
}
