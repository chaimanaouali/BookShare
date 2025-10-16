<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avis;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Http\Request;

class AvisController extends Controller
{
    /**
     * Display a listing of all avis (reviews) for admin management.
     */
    public function index()
    {
        $avis = Avis::with(['utilisateur', 'livre'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_reviews' => Avis::count(),
            'total_books' => Livre::count(),
            'total_users' => User::count(),
            'average_rating' => Avis::avg('note') ?? 0,
        ];

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
        $avis->load(['utilisateur', 'livre']);
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