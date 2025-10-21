@extends('layouts/contentNavbarLayout')

@section('title', 'Create Library - Admin')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">Create New Library</h4>
        <p class="text-muted">Create a new virtual library in the system</p>
      </div>
      <a href="{{ route('admin.bibliotheques.index') }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Back to Libraries
      </a>
    </div>
  </div>
</div>

<!-- Create Form -->
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Library Information</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('admin.bibliotheques.store') }}" method="POST">
          @csrf
          
          <div class="mb-3">
            <label for="nom_bibliotheque" class="form-label">Library Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('nom_bibliotheque') is-invalid @enderror" 
                   id="nom_bibliotheque" name="nom_bibliotheque" 
                   value="{{ old('nom_bibliotheque') }}" 
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
            <a href="{{ route('admin.bibliotheques.index') }}" class="btn btn-outline-secondary">
              Cancel
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-plus me-1"></i> Create Library
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <!-- Help Card -->
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-help-circle me-1"></i> Admin Tips
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6 class="text-primary">System Libraries</h6>
          <p class="small text-muted mb-0">As admin, you can create system-wide libraries that all users can access</p>
        </div>
        
        <div class="mb-3">
          <h6 class="text-primary">Organization</h6>
          <p class="small text-muted mb-0">Create libraries for different categories or purposes to help organize the system</p>
        </div>
        
        <div class="mb-0">
          <h6 class="text-primary">Management</h6>
          <p class="small text-muted mb-0">You can manage all libraries and books in the system from the admin panel</p>
        </div>
      </div>
    </div>
    
    <!-- System Stats -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-stats me-1"></i> System Stats
        </h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="h4 text-primary mb-0">{{ \App\Models\BibliothequeVirtuelle::count() }}</div>
            <small class="text-muted">Total Libraries</small>
          </div>
          <div class="col-6">
            <div class="h4 text-success mb-0">{{ \App\Models\Livre::count() }}</div>
            <small class="text-muted">Total Books</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
