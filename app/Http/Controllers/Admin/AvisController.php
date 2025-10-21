<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisController extends Controller
{
    /**
     * Display a listing of all avis (reviews) for admin management.
     * Contributors see only reviews for their own books.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Admin sees all reviews, contributor sees only reviews for their books
        if ($user->role === 'admin') {
            $avis = Avis::with(['utilisateur', 'livre'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            $stats = [
                'total_reviews' => Avis::count(),
                'total_books' => Livre::count(),
                'total_users' => User::count(),
                'average_rating' => Avis::avg('note') ?? 0,
            ];
        } else {
            // Contributor: only reviews for their books
            $avis = Avis::with(['utilisateur', 'livre'])
                ->whereHas('livre', function($query) use ($user) {
                    $query->where('user_id', $user->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
            
            $userBookIds = $user->livres()->pluck('id');
            $stats = [
                'total_reviews' => Avis::whereIn('livre_id', $userBookIds)->count(),
                'total_books' => $user->livres()->count(),
                'total_users' => User::count(),
                'average_rating' => Avis::whereIn('livre_id', $userBookIds)->avg('note') ?? 0,
            ];
        }

        return view('admin.avis.index', compact('avis', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     * DISABLED - Admin can only read avis
     */
    public function create()
    {
        abort(403, 'Access denied. Admin can only view reviews.');
    }

    /**
     * Store a newly created resource in storage.
     * DISABLED - Admin can only read avis
     */
    public function store(Request $request)
    {
        abort(403, 'Access denied. Admin can only view reviews.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Avis $avis)
    {
        $user = Auth::user();
        $avis->load(['utilisateur', 'livre']);
        
        // Contributors can only view reviews for their own books
        if ($user->role !== 'admin' && $avis->livre->user_id !== $user->id) {
            abort(403, 'You can only view reviews for your own books.');
        }
        
        return view('admin.avis.show', compact('avis'));
    }

    /**
     * Show the form for editing the specified resource.
     * DISABLED - Admin can only read avis
     */
    public function edit(Avis $avis)
    {
        abort(403, 'Access denied. Admin can only view reviews.');
    }

    /**
     * Update the specified resource in storage.
     * DISABLED - Admin can only read avis
     */
    public function update(Request $request, Avis $avis)
    {
        abort(403, 'Access denied. Admin can only view reviews.');
    }

    /**
     * Remove the specified resource from storage.
     * DISABLED - Admin can only read avis
     */
    public function destroy(Avis $avis)
    {
        abort(403, 'Access denied. Admin can only view reviews.');
    }
}