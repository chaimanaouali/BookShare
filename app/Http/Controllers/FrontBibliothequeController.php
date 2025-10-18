<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use Illuminate\Support\Facades\Auth;

class FrontBibliothequeController extends Controller
{
    // List all public bibliothèques
    public function index()
    {
        $bibliotheques = BibliothequeVirtuelle::whereHas('livres', function($q) {
            $q->where('visibilite', 'public');
        })->withCount(['livres' => function($q) {
            $q->where('visibilite', 'public');
        }])->with('user')->get();
        return view('front.bibliotheques.index', compact('bibliotheques'));
    }

    // Show a public bibliothèque and its public books
    public function show($id)
    {
        $bibliotheque = BibliothequeVirtuelle::with([
            'user', 
            'livres' => function($q) {
                $q->where('visibilite', 'public');
            },
            'discussions' => function($q) {
                $q->with(['user', 'topLevelComments' => function($commentQuery) {
                    $commentQuery->with(['user', 'votes', 'replies' => function($replyQuery) {
                        $replyQuery->with(['user', 'votes']);
                    }])->orderBy('created_at', 'desc');
                }])->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);
        
        return view('front.bibliotheques.show', compact('bibliotheque'));
    }
}
