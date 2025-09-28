@extends('front.layouts.app')
@section('title','Explore Public Libraries')
@include('front.partials.header')
<div class="container py-4"style="margin-top:100px;">
  <h2 class="mb-2 fw-bold text-primary">{{ $bibliotheque->nom_bibliotheque }}</h2>
  <p class="text-muted">Owner: {{ $bibliotheque->user->name ?? 'Unknown' }}</p>
  <div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Public Books</h5>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>File</th>
            <th>Uploaded</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bibliotheque->livreUtilisateurs as $lu)
            <tr>
              <td><i class="bx bx-book me-1 text-warning"></i> {{ $lu->livre->titre ?? 'Untitled' }}</td>
              <td>{{ $lu->livre->auteur ?? 'Unknown' }}</td>
              <td><span class="badge bg-label-secondary">{{ $lu->fichier_livre }}</span></td>
              <td>{{ $lu->created_at->diffForHumans() }}</td>
            </tr>
          @empty
            <tr><td colspan="4" class="text-muted">No public books in this library.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
