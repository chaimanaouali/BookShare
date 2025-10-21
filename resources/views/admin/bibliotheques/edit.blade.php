@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Library - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">Edit Library</h4>
        <p class="text-muted">Update library information</p>
      </div>
      <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Library
      </a>
    </div>
  </div>
</div>

<!-- Edit Form -->
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Library Information</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.bibliotheques.update', $bibliotheque->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label for="nom_bibliotheque" class="form-label">Library Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nom_bibliotheque') is-invalid @enderror" 
                   id="nom_bibliotheque" name="nom_bibliotheque" 
                   value="{{ old('nom_bibliotheque', $bibliotheque->nom_bibliotheque) }}" 
                   placeholder="Enter library name" required>
            @error('nom_bibliotheque')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Choose a descriptive name for the library</div>
          </div>
          
          <div class="mb-4">
            <label class="form-label">Library Description</label>
            <textarea class="form-control" name="description" rows="3" 
                      placeholder="Describe what this library contains (optional)">{{ old('description') }}</textarea>
            <div class="form-text">Optional description for the library</div>
          </div>
          
          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-outline-secondary">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> Update Library
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Library Stats -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-stats me-1"></i> Library Stats
        </h5>
      </div>
      <div class="card-body">
        <div class="row text-center mb-3">
          <div class="col-6">
            <div class="h4 text-primary mb-0">{{ $bibliotheque->nb_livres }}</div>
            <small class="text-muted">Books</small>
          </div>
          <div class="col-6">
            <div class="h4 text-info mb-0">{{ $bibliotheque->user->name ?? 'Unknown' }}</div>
            <small class="text-muted">Owner</small>
          </div>
        </div>
        <hr>
        <div class="small text-muted">
          <div class="d-flex justify-content-between mb-1">
            <span>Created:</span>
            <span>{{ $bibliotheque->created_at->format('M d, Y') }}</span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span>Last updated:</span>
            <span>{{ $bibliotheque->updated_at->diffForHumans() }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-cog me-1"></i> Quick Actions
        </h5>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="{{ route('admin.bibliotheques.show', $bibliotheque->id) }}" class="btn btn-outline-primary btn-sm">
            <i class="bx bx-show me-1"></i> View Library
          </a>
          <a href="{{ route('admin.bibliotheques.add-books', $bibliotheque->id) }}" class="btn btn-outline-success btn-sm">
            <i class="bx bx-plus me-1"></i> Add Books
          </a>
          <a href="{{ route('admin.bibliotheques.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-list-ul me-1"></i> All Libraries
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
