@extends('layouts/contentNavbarLayout')

@section('title', 'Create Category')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Create New Category</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm" data-validate>
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nom" class="form-label">Category Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom') }}" 
                                           placeholder="Enter category name" required
                                           data-min-length="2" data-max-length="255">
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Enter category description (optional)"
                                              data-min-length="10" data-max-length="1000">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="bx bx-info-circle me-1"></i> Category Information
                                        </h6>
                                        <p class="small text-muted mb-2">
                                            Categories help organize books by genre, subject, or type.
                                        </p>
                                        <ul class="small text-muted mb-0">
                                            <li>Choose a clear, descriptive name</li>
                                            <li>Add a description to help users understand the category</li>
                                            <li>Categories can be edited later</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i> Back to Categories
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script src="{{ asset('js/validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form validation
    const validator = new FormValidator('#categoryForm');
    
    // Add custom validation for category name
    const nomField = document.getElementById('nom');
    if (nomField) {
        nomField.addEventListener('input', function() {
            // Remove special characters except allowed ones
            this.value = this.value.replace(/[^a-zA-ZÀ-ÿ0-9\s\-_\.]/g, '');
        });
    }
    
    // Add character counter for description
    const descriptionField = document.getElementById('description');
    if (descriptionField) {
        const counter = document.createElement('small');
        counter.className = 'text-muted';
        counter.style.display = 'block';
        counter.style.marginTop = '5px';
        descriptionField.parentNode.appendChild(counter);
        
        function updateCounter() {
            const length = descriptionField.value.length;
            counter.textContent = `${length}/1000 caractères`;
            counter.style.color = length > 1000 ? '#dc3545' : '#6c757d';
        }
        
        descriptionField.addEventListener('input', updateCounter);
        updateCounter();
    }
});
</script>
@endsection
