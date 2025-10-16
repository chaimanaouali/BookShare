@extends('layouts/contentNavbarLayout')
@section('title', 'My Books')
@section('content')
<div class="container py-4 mt-5">
  <h2 class="mb-4">My Books</h2>
  <div class="card">
    <div class="card-body">
      @if($livres->count() > 0)
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Library</th>
                <th>Format</th>
                <th>Visibility</th>
                <th>Uploaded</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($livres as $livre)
              <tr>
                <td>{{ $livre->title ?? '-' }}</td>
                <td>{{ $livre->author ?? '-' }}</td>
                <td>{{ $livre->bibliotheque->nom_bibliotheque ?? '-' }}</td>
                <td>{{ strtoupper($livre->format ?? '-') }}</td>
                <td>
                  <span class="badge bg-label-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">{{ ucfirst($livre->visibilite) }}</span>
                </td>
                <td>{{ $livre->created_at->format('Y-m-d H:i') }}</td>
                <td>
                  <a href="{{ route('contributor.livres.show', $livre->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                  <a href="{{ route('contributor.livres.edit', $livre->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                  <form action="{{ route('contributor.livres.destroy', $livre->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <p class="text-muted">You have not uploaded any books yet.</p>
      @endif
    </div>
  </div>
</div>
@endsection
