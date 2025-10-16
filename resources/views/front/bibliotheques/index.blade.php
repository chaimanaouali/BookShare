@extends('front.layouts.app')

@section('title','Explore Public Libraries')

@include('front.partials.header')
<div class="container py-4" style="margin-top:100px;">
  <h2 class="mb-4 fw-bold text-primary">Explore Public Libraries</h2>
  <div class="row">
    @forelse($bibliotheques as $b)
      <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title text-primary"><i class="bx bx-library me-1"></i> {{ $b->nom_bibliotheque }}</h5>
            <p class="card-text text-muted mb-2">Owner: {{ $b->user->name ?? 'Unknown' }}</p>
            <span class="badge bg-label-info mb-2">{{ $b->livre_utilisateurs_count }} Public Books</span>
            <a href="{{ route('front.bibliotheques.show', $b->id) }}" class="btn btn-outline-primary btn-sm mt-2">
              <i class="bx bx-search-alt"></i> View Library
            </a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><p class="text-muted">No public libraries found.</p></div>
    @endforelse
  </div>
</div>
