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
      <form action="{{ route('contributor.livres.update', $livre->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <!-- Book Information -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="text-primary mb-3">
              <i class="bx bx-book me-2"></i>Book Information
            </h5>
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                   value="{{ old('title', $livre->title ?? '') }}" placeholder="Enter book title" required>
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
            <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" 
                   value="{{ old('author', $livre->author ?? '') }}" placeholder="Enter author name" required>
            @error('author')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="categorie_id" class="form-label">Category</label>
            <select name="categorie_id" id="categorie_id" class="form-select @error('categorie_id') is-invalid @enderror">
              <option value="">Select a category (optional)</option>
              @foreach(\App\Models\Categorie::orderBy('nom')->get() as $categorie)
                <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                  {{ $categorie->nom }}
                </option>
              @endforeach
            </select>
            @error('categorie_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror" 
                   value="{{ old('isbn', $livre->isbn ?? '') }}" placeholder="Enter ISBN (optional)">
            @error('isbn')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <!-- File and Settings -->
        <div class="row mb-4">
          <div class="col-12">
            <h5 class="text-primary mb-3">
              <i class="bx bx-cog me-2"></i>File and Settings
            </h5>
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">Format</label>
            <input type="text" name="format" class="form-control" value="{{ old('format', $livre->format) }}" placeholder="Auto-detected from file">
          </div>
          <div class="col-md-6">
            <label class="form-label">Visibility <span class="text-danger">*</span></label>
            <select name="visibilite" class="form-select" required>
              <option value="public" {{ old('visibilite', $livre->visibilite) == 'public' ? 'selected' : '' }}>Public</option>
              <option value="private" {{ old('visibilite', $livre->visibilite) == 'private' ? 'selected' : '' }}>Private</option>
            </select>
          </div>
        </div>
        
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="user_description" class="form-control" rows="3" placeholder="Add a description for this book instance (optional)">{{ old('user_description', $livre->user_description) }}</textarea>
        </div>
        
        <div class="mb-4">
          <label class="form-label">Replace File (optional)</label>
          <input type="file" name="fichier_livre" class="form-control" accept=".pdf,.epub,.mobi,.txt">
          <div class="form-text">Supported formats: PDF, EPUB, MOBI, TXT (Max: 10MB)</div>
        </div>
        
        <div class="d-flex justify-content-between">
          <a href="{{ route('contributor.livres.index', $livre->id) }}" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back me-1"></i>Cancel
          </a>
          <button type="submit" class="btn btn-primary">
            <i class="bx bx-save me-1"></i>Save Changes
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
