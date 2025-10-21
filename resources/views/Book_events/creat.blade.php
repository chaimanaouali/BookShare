@extends('layouts/contentNavbarLayout')

@section('title', 'Créer un événement')

@section('content')
<link href="{{ asset('css/validation.css') }}" rel="stylesheet">
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Create new event</h5>
      </div>
      <div class="card-body">
        <x-validation-helper formId="eventForm" />
        
        <form action="{{ route('book-events.store') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data" id="eventForm" data-validate>
          @csrf
          
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="defi_id" class="form-label">Défi (optionnel)</label>
              <div class="input-group">
                <span class="input-group-text">
                  <i class="bx bx-flag"></i>
                </span>
                <select id="defi_id" name="defi_id" class="form-select @error('defi_id') is-invalid @enderror">
                  <option value="">— No challenges —</option>
                  @isset($defis)
                    @foreach($defis as $defi)
                      <option value="{{ $defi->id }}" {{ old('defi_id') == $defi->id ? 'selected' : '' }}>{{ $defi->titre }}</option>
                    @endforeach
                  @endisset
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
                <select id="type" 
                        name="type" 
                        class="form-select @error('type') is-invalid @enderror" 
                        required
                        data-min-length="1">
                  <option value="">Sélectionnez un type d'événement</option>
                  <option value="Silent Reading Session" {{ old('type') == 'Silent Reading Session' ? 'selected' : '' }}>
                    Silent Reading Session
                  </option>
                  <option value="Reading Challenge" {{ old('type') == 'Reading Challenge' ? 'selected' : '' }}>
                    Reading Challenge
                  </option>
                  <option value="Book Club Meeting" {{ old('type') == 'Book Club Meeting' ? 'selected' : '' }}>
                    Book Club Meeting
                  </option>
                </select>
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
                       required
                       data-min-date="today">
                @error('date_evenement')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="titre" class="form-label">Title <span class="text-danger">*</span></label>
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
                     required
                     data-min-length="3"
                     data-max-length="255"
                     data-regex="^[a-zA-ZÀ-ÿ0-9\s\-_\.\:\!\?]+$"
                     data-regex-message="Le titre contient des caractères non autorisés">
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
                        placeholder="Description détaillée de l'événement..."
                        data-min-length="10"
                        data-max-length="2000">{{ old('description') }}</textarea>
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
                     accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                     data-max-file-size="2048"
                     data-accepted-types="jpeg,png,jpg,gif,webp">
              @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <small class="text-muted">Formats acceptés: JPEG, PNG, JPG, GIF (max 2MB)</small>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
              <i class="bx bx-save me-1"></i> Save
            </button>
            <a href="{{ route('book-events.index') }}" class="btn btn-outline-secondary">
              <i class="bx bx-x me-1"></i> Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/validation.js') }}"></script>
<script>
// Enhanced event form validation
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('eventForm');
  const typeSelect = document.getElementById('type');
  const dateInput = document.getElementById('date_evenement');
  const titreInput = document.getElementById('titre');
  const descriptionTextarea = document.getElementById('description');
  const imageInput = document.getElementById('image');
  
  // Set minimum date to today
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
  }
  
  // Real-time validation for type selection
  if (typeSelect) {
    typeSelect.addEventListener('change', function() {
      validateSelectField(this);
    });
  }
  
  // Real-time validation for date
  if (dateInput) {
    dateInput.addEventListener('change', function() {
      validateDateField(this);
    });
  }
  
  // Real-time validation for title
  if (titreInput) {
    titreInput.addEventListener('input', function() {
      validateTextField(this);
    });
    
    // Character counter for title
    const titleCounter = document.createElement('small');
    titleCounter.className = 'form-text text-muted';
    titleCounter.style.float = 'right';
    titreInput.parentNode.appendChild(titleCounter);
    
    titreInput.addEventListener('input', function() {
      const length = this.value.length;
      titleCounter.textContent = `${length}/255 caractères`;
      titleCounter.className = length > 255 ? 'form-text text-danger' : 'form-text text-muted';
    });
  }
  
  // Real-time validation for description
  if (descriptionTextarea) {
    descriptionTextarea.addEventListener('input', function() {
      validateTextField(this);
    });
    
    // Character counter for description
    const descCounter = document.createElement('small');
    descCounter.className = 'form-text text-muted';
    descCounter.style.float = 'right';
    descriptionTextarea.parentNode.appendChild(descCounter);
    
    descriptionTextarea.addEventListener('input', function() {
      const length = this.value.length;
      descCounter.textContent = `${length}/2000 caractères`;
      descCounter.className = length > 2000 ? 'form-text text-danger' : 'form-text text-muted';
    });
  }
  
  // Real-time validation for image
  if (imageInput) {
    imageInput.addEventListener('change', function() {
      validateFileField(this);
    });
  }
  
  // Form submission validation
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      let isValid = true;
      
      // Validate all fields
      if (!validateSelectField(typeSelect)) isValid = false;
      if (!validateDateField(dateInput)) isValid = false;
      if (!validateTextField(titreInput)) isValid = false;
      if (descriptionTextarea.value && !validateTextField(descriptionTextarea)) isValid = false;
      if (imageInput.files.length > 0 && !validateFileField(imageInput)) isValid = false;
      
      if (isValid) {
        form.submit();
      } else {
        showValidationSummary();
      }
    });
  }
  
  function validateSelectField(field) {
    if (!field.value) {
      setFieldInvalid(field, 'Veuillez sélectionner une option.');
      return false;
    }
    setFieldValid(field);
    return true;
  }
  
  function validateDateField(field) {
    const value = field.value;
    if (!value) {
      setFieldInvalid(field, 'La date est obligatoire.');
      return false;
    }
    
    const selectedDate = new Date(value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate < today) {
      setFieldInvalid(field, 'La date ne peut pas être dans le passé.');
      return false;
    }
    
    const maxDate = new Date('2030-12-31');
    if (selectedDate > maxDate) {
      setFieldInvalid(field, 'La date ne peut pas être après 2030.');
      return false;
    }
    
    setFieldValid(field);
    return true;
  }
  
  function validateTextField(field) {
    const value = field.value.trim();
    const minLength = parseInt(field.dataset.minLength) || 0;
    const maxLength = parseInt(field.dataset.maxLength) || Infinity;
    const regex = field.dataset.regex;
    const regexMessage = field.dataset.regexMessage;
    
    if (field.hasAttribute('required') && !value) {
      setFieldInvalid(field, 'Ce champ est obligatoire.');
      return false;
    }
    
    if (value && value.length < minLength) {
      setFieldInvalid(field, `Doit contenir au moins ${minLength} caractères.`);
      return false;
    }
    
    if (value && value.length > maxLength) {
      setFieldInvalid(field, `Ne doit pas dépasser ${maxLength} caractères.`);
      return false;
    }
    
    if (value && regex && !new RegExp(regex).test(value)) {
      setFieldInvalid(field, regexMessage || 'Format invalide.');
      return false;
    }
    
    setFieldValid(field);
    return true;
  }
  
  function validateFileField(field) {
    if (field.files.length === 0) return true;
    
    const file = field.files[0];
    const maxSize = parseInt(field.dataset.maxFileSize) * 1024; // Convert KB to bytes
    const acceptedTypes = field.dataset.acceptedTypes.split(',');
    
    if (file.size > maxSize) {
      setFieldInvalid(field, `Le fichier ne doit pas dépasser ${field.dataset.maxFileSize / 1024} MB.`);
      return false;
    }
    
    const fileExtension = file.name.split('.').pop().toLowerCase();
    if (!acceptedTypes.includes(fileExtension)) {
      setFieldInvalid(field, `Format non supporté. Formats acceptés: ${acceptedTypes.join(', ').toUpperCase()}`);
      return false;
    }
    
    setFieldValid(field);
    return true;
  }
  
  function setFieldInvalid(field, message) {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    
    let feedback = field.parentNode.querySelector('.invalid-feedback');
    if (!feedback) {
      feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      field.parentNode.appendChild(feedback);
    }
    feedback.textContent = message;
  }
  
  function setFieldValid(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    const feedback = field.parentNode.querySelector('.invalid-feedback');
    if (feedback) {
      feedback.textContent = '';
    }
  }
  
  function showValidationSummary() {
    const invalidFields = form.querySelectorAll('.is-invalid');
    if (invalidFields.length > 0) {
      const firstInvalid = invalidFields[0];
      firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
      firstInvalid.focus();
      
      // Show client-side validation errors summary
      const errors = {};
      invalidFields.forEach(field => {
        const fieldName = field.name || field.id;
        const feedback = field.parentNode.querySelector('.invalid-feedback');
        if (feedback && feedback.textContent) {
          if (!errors[fieldName]) {
            errors[fieldName] = [];
          }
          errors[fieldName].push(feedback.textContent);
        }
      });
      
      if (typeof showClientValidationErrors === 'function') {
        showClientValidationErrors(errors);
      }
    }
  }
});
</script>
@endsection
