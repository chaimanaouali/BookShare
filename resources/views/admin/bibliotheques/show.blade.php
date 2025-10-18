@extends('layouts/contentNavbarLayout')

@section('title', 'Bibliothèque Details')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bx bx-library me-2"></i>{{ $bibliotheque->nom_bibliotheque }}</h4>
        <a href="{{ route('admin.bibliotheques.discussions', $bibliotheque->id) }}" class="btn btn-sm btn-outline-secondary">
          <i class="bx bx-conversation"></i> Discussions
        </a>
      </div>
      <div class="card-body">
        <p class="mb-1"><strong>Owner:</strong> {{ $bibliotheque->user->name ?? 'Unknown' }}</p>
        <p class="mb-0 text-muted">ID: {{ $bibliotheque->id }}</p>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Books in this Library</h5>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>Created</th>
            </tr>
          </thead>
          <tbody>
            @forelse($bibliotheque->livres as $livre)
            <tr>
              <td>{{ $livre->title ?? 'Untitled' }}</td>
              <td>{{ $livre->author ?? 'Unknown' }}</td>
              <td>{{ $livre->created_at?->diffForHumans() }}</td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-muted">No books in this library.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@extends('layouts/contentNavbarLayout')

@section('title', 'Review Bibliothèque')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0">{{ $bibliotheque->nom_bibliotheque }}</h4>
          <small class="text-muted">Owner: {{ $bibliotheque->user->name ?? 'Unknown' }}</small>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>Title</th>
              <th>Author</th>
              <th>File</th>
              <th>Visibility</th>
              <th>Uploaded</th>
            </tr>
          </thead>
          <tbody>
            @foreach($bibliotheque->livres as $livre)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-sm me-2">
                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-book"></i></span>
                  </div>
                  <span>{{ $livre->title ?? 'Untitled' }}</span>
                </div>
              </td>
              <td>{{ $livre->author ?? 'Unknown' }}</td>
              <td><span class="badge bg-label-secondary">{{ $livre->fichier_livre }}</span></td>
              <td><span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'danger' }}">{{ ucfirst($livre->visibilite) }}</span></td>
              <td>{{ $livre->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
