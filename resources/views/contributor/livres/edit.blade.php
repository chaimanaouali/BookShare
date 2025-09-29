@extends('layouts/contentNavbarLayout')
@section('title', 'Edit Book')
@section('content')
<div class="container py-4 mt-5">
  <h2 class="mb-4">Edit Book</h2>
  <div class="card">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form action="{{ route('contributor.livres.update', $livreUtilisateur->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="form-label">Title</label>
          <input type="text" class="form-control" value="{{ $livreUtilisateur->livre->title ?? '-' }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Author</label>
          <input type="text" class="form-control" value="{{ $livreUtilisateur->livre->author ?? '-' }}" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Format</label>
          <input type="text" name="format" class="form-control" value="{{ old('format', $livreUtilisateur->format) }}">
        </div>
        <div class="mb-3">
          <label class="form-label">Visibility</label>
          <select name="visibilite" class="form-select">
            <option value="public" {{ old('visibilite', $livreUtilisateur->visibilite) == 'public' ? 'selected' : '' }}>Public</option>
            <option value="private" {{ old('visibilite', $livreUtilisateur->visibilite) == 'private' ? 'selected' : '' }}>Private</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3">{{ old('description', $livreUtilisateur->description) }}</textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Replace File (optional)</label>
          <input type="file" name="fichier_livre" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </form>
    </div>
  </div>
</div>
@endsection
