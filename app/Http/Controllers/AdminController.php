<?php

namespace App\Http\Controllers;

use App\Models\BibliothequeVirtuelle;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Admin dashboard: list all bibliothèques.
     */
    public function dashboard()
    {
        $bibliotheques = BibliothequeVirtuelle::with(['user'])->withCount('livreUtilisateurs')->latest()->get();
        return view('admin.dashboard', compact('bibliotheques'));
    }

    /**
     * View a specific bibliothèque and all its uploaded books/files.
     */
    public function bibliothequeShow($id)
    {
        $bibliotheque = BibliothequeVirtuelle::with(['livreUtilisateurs', 'user'])->findOrFail($id);
        return view('admin.bibliotheques.show', compact('bibliotheque'));
    }
}
