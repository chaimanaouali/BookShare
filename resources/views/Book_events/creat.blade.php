@extends('layouts/contentNavbarLayout')

@section('title', 'Créer un événement')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Créer un événement</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('book-events.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          @csrf
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="type" class="form-label">Type <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-tag"></i>
                </span>
                <input type="text" 
                       class="form-control @error('type') is-invalid @enderror" 
                       id="type" 
                       name="type" 
                       placeholder="Ex: Conférence, Atelier, Formation..."
                       value="{{ old('type') }}" 
                       required>
                @error('type')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <label for="date_evenement" class="form-label">Date <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-calendar"></i>
                </span>
                <input type="date" 
                       class="form-control @error('date_evenement') is-invalid @enderror" 
                       id="date_evenement" 
                       name="date_evenement" 
                       value="{{ old('date_evenement') }}" 
                       required>
                @error('date_evenement')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="titre" class="form-label">Titre <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-book"></i>
              </span>
              <input type="text" 
                     class="form-control @error('titre') is-invalid @enderror" 
                     id="titre" 
                     name="titre" 
                     placeholder="Titre de l'événement"
                     value="{{ old('titre') }}" 
                     required>
              @error('titre')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <div class="input-group">
              <span class="input-group-text align-items-start">
                <i class="bx bx-text"></i>
              </span>
              <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" 
                        name="description" 
                        rows="4" 
                        placeholder="Description détaillée de l'événement...">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-4">
            <label for="image" class="form-label">Image</label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-image"></i>
              </span>
              <input type="file" 
                     class="form-control @error('image') is-invalid @enderror" 
                     id="image" 
                     name="image" 
                     accept="image/*">
              @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <small class="text-muted">Formats acceptés: JPEG, PNG, JPG, GIF (max 2MB)</small>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bx bx-save me-1"></i> Enregistrer
            </button>
            <a href="{{ route('book-events.index') }}" class="btn btn-outline-secondary">
              <i class="bx bx-x me-1"></i> Annuler
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
// Bootstrap form validation
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>
@endsection
