@extends('layouts/contentNavbarLayout')
@section('title', 'Book Details')
@section('content')
<div class="container py-4 mt-5">
  <h2 class="mb-4">Book Details</h2>
  <div class="card">
    <div class="card-body">
      <h4>{{ $livreUtilisateur->livre->title ?? 'Untitled' }}</h4>
      <p class="mb-1"><strong>Author:</strong> {{ $livreUtilisateur->livre->author ?? 'Unknown' }}</p>
      <p class="mb-1"><strong>ISBN:</strong> {{ $livreUtilisateur->livre->isbn ?? '-' }}</p>
      <p class="mb-1"><strong>Description:</strong> {{ $livreUtilisateur->livre->description ?? '-' }}</p>
      <p class="mb-1"><strong>Format:</strong> {{ strtoupper($livreUtilisateur->format ?? '-') }}</p>
      <p class="mb-1"><strong>Visibility:</strong> <span class="badge bg-label-{{ $livreUtilisateur->visibilite == 'public' ? 'success' : 'warning' }}">{{ ucfirst($livreUtilisateur->visibilite) }}</span></p>
      <p class="mb-1"><strong>Uploaded:</strong> {{ $livreUtilisateur->created_at->format('Y-m-d H:i') }}</p>
      @if($livreUtilisateur->fichier_livre)
        <p class="mb-1"><strong>File:</strong> <a href="{{ Storage::url($livreUtilisateur->fichier_livre) }}" class="btn btn-sm btn-outline-success" download>Download</a></p>
      @endif
      <p class="mb-1"><strong>Library:</strong> {{ $livreUtilisateur->bibliotheque->nom_bibliotheque ?? '-' }}</p>
      <p class="mb-1"><strong>Instance Description:</strong> {{ $livreUtilisateur->description ?? '-' }}</p>
    </div>
  </div>
</div>
@endsection
