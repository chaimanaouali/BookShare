@extends('layouts/contentNavbarLayout')

@section('title', 'Create Library - BookShare')

@section('content')
<!-- Page Header -->
<div class="row mb-4">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center">
      <div>
        <h4 class="mb-1">Create New Library</h4>
        <p class="text-muted">Create a new virtual library to organize your digital books</p>
      </div>
      <a href="{{ route('contributor.bibliotheques.index') }}" class="btn btn-outline-secondary">
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
        <form action="{{ route('contributor.bibliotheques.store') }}" method="POST" id="bibliothequeForm" novalidate>
          @csrf
          
          <div class="mb-3">
            <label for="nom_bibliotheque" class="form-label">Library Name <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('nom_bibliotheque') is-invalid @enderror" 
                   id="nom_bibliotheque" 
                   name="nom_bibliotheque" 
                   value="{{ old('nom_bibliotheque') }}" 
                   placeholder="Enter library name" 
                   required
                   minlength="3"
                   maxlength="255"
                   pattern="[a-zA-Z0-9\s\-_.,!?()]+"
                   data-validation="required|min:3|max:255|regex:^[a-zA-Z0-9\s\-_.,!?()]+$">
            @error('nom_bibliotheque')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="nom_bibliotheque_error"></div>
            <div class="form-text">
              Choose a descriptive name for your library (e.g., "My Fiction Collection", "Technical Books")
              <span class="text-muted">(<span id="nom_bibliotheque_count">0</span>/255 characters)</span>
            </div>
          </div>
          
          <div class="mb-4">
            <label for="description" class="form-label">Library Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" 
                      name="description" 
                      id="description"
                      rows="3" 
                      placeholder="Describe what this library contains (optional)"
                      maxlength="1000"
                      data-validation="max:1000|regex:^[a-zA-Z0-9\s\-_.,!?()\n\r]+$">{{ old('description') }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="description_error"></div>
            <div class="form-text">
              Optional description to help you organize your libraries
              <span class="text-muted">(<span id="description_count">0</span>/1000 characters)</span>
            </div>
          </div>
          
          <div class="d-flex justify-content-between">
            <a href="{{ route('contributor.bibliotheques.index') }}" class="btn btn-outline-secondary">
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
          <i class="bx bx-help-circle me-1"></i> Tips
        </h5>
      </div>
      <div class="card-body">
        <div class="mb-3">
          <h6 class="text-primary">Library Organization</h6>
          <p class="small text-muted mb-0">Create separate libraries for different types of books (fiction, technical, academic, etc.)</p>
        </div>
        
        <div class="mb-3">
          <h6 class="text-primary">Naming Convention</h6>
          <p class="small text-muted mb-0">Use descriptive names that help you quickly identify the library's purpose</p>
        </div>
        
        <div class="mb-0">
          <h6 class="text-primary">Privacy</h6>
          <p class="small text-muted mb-0">You can set individual book visibility (public/private) when uploading books to this library</p>
        </div>
      </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="card mt-3">
      <div class="card-header">
        <h5 class="card-title mb-0">
          <i class="bx bx-stats me-1"></i> Your Stats
        </h5>
      </div>
      <div class="card-body">
        <div class="row text-center">
          <div class="col-6">
            <div class="h4 text-primary mb-0">{{ auth()->user()->bibliotheques()->count() }}</div>
            <small class="text-muted">Libraries</small>
          </div>
          <div class="col-6">
            <div class="h4 text-success mb-0">{{ auth()->user()->livres()->count() }}</div>
            <small class="text-muted">Books</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Validation Helper Component -->
<x-validation-helper />

@endsection

@section('extra-css')
<style>
  .form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
  }
  
  .form-control.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
  }
  
  .character-count {
    font-size: 0.875rem;
    font-weight: 500;
  }
  
  .character-count.warning {
    color: #ffc107;
  }
  
  .character-count.danger {
    color: #dc3545;
  }
  
  .validation-message {
    font-size: 0.875rem;
    margin-top: 0.25rem;
  }
  
  .validation-message.error {
    color: #dc3545;
  }
  
  .validation-message.success {
    color: #28a745;
  }
</style>
@endsection

@section('extra-js')
<script src="{{ asset('js/validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('bibliothequeForm');
    const nomBibliothequeInput = document.getElementById('nom_bibliotheque');
    const descriptionInput = document.getElementById('description');
    const nomBibliothequeCount = document.getElementById('nom_bibliotheque_count');
    const descriptionCount = document.getElementById('description_count');
    
    // Character counters
    function updateCharacterCount(input, counter, maxLength) {
        const count = input.value.length;
        counter.textContent = count;
        
        if (count > maxLength * 0.9) {
            counter.classList.add('danger');
            counter.classList.remove('warning');
        } else if (count > maxLength * 0.8) {
            counter.classList.add('warning');
            counter.classList.remove('danger');
        } else {
            counter.classList.remove('warning', 'danger');
        }
    }
    
    // Real-time character counting
    nomBibliothequeInput.addEventListener('input', function() {
        updateCharacterCount(this, nomBibliothequeCount, 255);
        validateField(this);
    });
    
    descriptionInput.addEventListener('input', function() {
        updateCharacterCount(this, descriptionCount, 1000);
        validateField(this);
    });
    
    // Field validation
    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let isValid = true;
        let errorMessage = '';
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        if (fieldName === 'nom_bibliotheque') {
            if (!value) {
                isValid = false;
                errorMessage = 'Le nom de la bibliothèque est obligatoire.';
            } else if (value.length < 3) {
                isValid = false;
                errorMessage = 'Le nom de la bibliothèque doit contenir au moins 3 caractères.';
            } else if (value.length > 255) {
                isValid = false;
                errorMessage = 'Le nom de la bibliothèque ne peut pas dépasser 255 caractères.';
            } else if (!/^[a-zA-Z0-9\s\-_.,!?()]+$/.test(value)) {
                isValid = false;
                errorMessage = 'Le nom de la bibliothèque contient des caractères non autorisés.';
            } else if (/(.)\1{4,}/.test(value)) {
                isValid = false;
                errorMessage = 'Le nom de la bibliothèque ne peut pas contenir de caractères répétés plus de 4 fois.';
            }
        } else if (fieldName === 'description') {
            if (value.length > 1000) {
                isValid = false;
                errorMessage = 'La description ne peut pas dépasser 1000 caractères.';
            } else if (value && !/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/.test(value)) {
                isValid = false;
                errorMessage = 'La description contient des caractères non autorisés.';
            }
        }
        
        // Apply validation classes and messages
        if (isValid) {
            field.classList.add('is-valid');
        } else {
            field.classList.add('is-invalid');
        }
        
        // Update error message
        const errorElement = document.getElementById(fieldName + '_error');
        if (errorElement) {
            errorElement.textContent = errorMessage;
        }
        
        return isValid;
    }
    
    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let isFormValid = true;
        
        // Validate all fields
        isFormValid &= validateField(nomBibliothequeInput);
        isFormValid &= validateField(descriptionInput);
        
        if (isFormValid) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Creating...';
            submitBtn.disabled = true;
            
            // Submit form
            form.submit();
        } else {
            // Focus on first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
            
            // Show error message
            showToast('Please correct the errors before submitting.', 'danger');
        }
    });
    
    // Real-time validation on blur
    nomBibliothequeInput.addEventListener('blur', function() {
        validateField(this);
    });
    
    descriptionInput.addEventListener('blur', function() {
        validateField(this);
    });
    
    // Initialize character counts
    updateCharacterCount(nomBibliothequeInput, nomBibliothequeCount, 255);
    updateCharacterCount(descriptionInput, descriptionCount, 1000);
    
    // Toast notification function
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;

        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.style.position = 'fixed';
            toastContainer.style.top = '20px';
            toastContainer.style.right = '20px';
            toastContainer.style.zIndex = '1050';
            document.body.appendChild(toastContainer);
        }
        toastContainer.appendChild(toast);

        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        toast.addEventListener('hidden.bs.toast', function () {
            toast.remove();
        });
    }
});
</script>
@endsection
