@extends('layouts/contentNavbarLayout')
@section('title', 'Book Details')
@section('content')
<div class="container py-4 mt-5">
  <h2 class="mb-4">Book Details</h2>
  <div class="card">
    <div class="card-body">
      <h4>{{ $livre->title ?? 'Untitled' }}</h4>
      <p class="mb-1"><strong>Author:</strong> {{ $livre->author ?? 'Unknown' }}</p>
      <p class="mb-1"><strong>ISBN:</strong> {{ $livre->isbn ?? '-' }}</p>
      <p class="mb-1"><strong>Description:</strong> {{ $livre->description ?? '-' }}</p>
      <p class="mb-1"><strong>Format:</strong> {{ strtoupper($livre->format ?? '-') }}</p>
      <p class="mb-1"><strong>Visibility:</strong> <span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">{{ ucfirst($livre->visibilite) }}</span></p>
      <p class="mb-1"><strong>Uploaded:</strong> {{ $livre->created_at->format('Y-m-d H:i') }}</p>
      @if($livre->fichier_livre)
        <p class="mb-1"><strong>File:</strong> <a href="{{ Storage::url($livre->fichier_livre) }}" class="btn btn-sm btn-outline-success" download>Download</a></p>
      @endif
      <p class="mb-1"><strong>Library:</strong> {{ $livre->bibliotheque->nom_bibliotheque ?? '-' }}</p>
      <p class="mb-1"><strong>Instance Description:</strong> {{ $livre->user_description ?? '-' }}</p>
    </div>
  </div>
</div>
@endsection
