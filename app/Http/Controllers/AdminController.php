<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use App\Models\Discussion;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Admin dashboard: list all bibliothèques.
     */
    public function dashboard()
    {
        $bibliotheques = BibliothequeVirtuelle::with(['user'])
            ->withCount(['livres', 'discussions'])
            ->latest()->get();
        $latestDiscussions = Discussion::with(['user', 'bibliotheque'])
            ->latest()->limit(10)->get();
        return view('admin.dashboard', compact('bibliotheques', 'latestDiscussions'));
    }

    /**
     * Delete a discussion (admin only).
     */
    public function deleteDiscussion($discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        $discussion->delete();
        return back()->with('success', 'Discussion deleted');
    }

    /**
     * View a specific bibliothèque and all its uploaded books/files.
     */
    public function bibliothequeShow($id)
    {
        // Load books for this bibliotheque; books live in `livres` (not nested `livre` relation)
        $bibliotheque = BibliothequeVirtuelle::with(['livres', 'user'])->findOrFail($id);
        return view('admin.bibliotheques.show', compact('bibliotheque'));
    }

    /**
     * Admin view: discussions for a specific bibliotheque (read-only + delete).
     */
    public function bibliothequeDiscussions($id)
    {
        $bibliotheque = BibliothequeVirtuelle::findOrFail($id);
        $discussions = Discussion::with(['user'])
            ->where('bibliotheque_id', $id)
            ->withCount(['comments'])
            ->latest()->paginate(15);
        return view('admin.bibliotheques.discussions', compact('bibliotheque', 'discussions'));
    }
}
