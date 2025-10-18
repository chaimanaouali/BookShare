@extends('layouts/contentNavbarLayout')

@section('title', 'Modifier un événement')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Modifier un événement</h5>
      </div>
      <div class="card-body">
        <form action="{{ route('book-events.update', $bookEvent->id) }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="defi_id" class="form-label">Défi (optionnel)</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-flag"></i>
                </span>
                <select id="defi_id" name="defi_id" class="form-select @error('defi_id') is-invalid @enderror">
                  <option value="">— Aucun défi —</option>
                  @foreach(\App\Models\Defi::orderByDesc('created_at')->get() as $defi)
                    <option value="{{ $defi->id }}" {{ old('defi_id', $bookEvent->defi_id) == $defi->id ? 'selected' : '' }}>{{ $defi->titre }}</option>
                  @endforeach
                </select>
                @error('defi_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
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
                       value="{{ old('type', $bookEvent->type) }}" 
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
                       value="{{ old('date_evenement', $bookEvent->date_evenement) }}" 
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
                     value="{{ old('titre', $bookEvent->titre) }}" 
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
                        placeholder="Description détaillée de l'événement...">{{ old('description', $bookEvent->description) }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="mb-4">
            <label for="image" class="form-label">Image</label>
            @if($bookEvent->image)
              <div class="mb-2">
                <img src="{{ asset($bookEvent->image) }}" alt="Image actuelle" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                <small class="text-muted d-block">Image actuelle</small>
              </div>
            @endif
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
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-save me-1"></i> Mettre à jour
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
