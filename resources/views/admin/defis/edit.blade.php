@extends('layouts/contentNavbarLayout')

@section('title', 'Modifier défi')

@section('content')
<link href="{{ asset('css/validation.css') }}" rel="stylesheet">
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Update challenges</h5>
    </div>
    <div class="card-body">
      <x-validation-helper formId="challengeEditForm" />
      
      <form method="POST" action="{{ route('defis.update', $defi) }}" class="needs-validation" novalidate id="challengeEditForm" data-validate>
        @csrf
        @method('PUT')
        
        <div class="mb-3">
          <label for="titre" class="form-label">Title <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text">
              <i class="bx bx-flag"></i>
            </span>
            <input 
              name="titre" 
              type="text" 
              class="form-control @error('titre') is-invalid @enderror" 
              id="titre"
              value="{{ old('titre', $defi->titre) }}" 
              required
              data-min-length="3"
              data-max-length="255"
              data-regex="^[a-zA-ZÀ-ÿ0-9\s\-_\.\:\!\?]+$"
              data-regex-message="Le titre contient des caractères non autorisés"
              placeholder="Titre du défi">
            @error('titre')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="mb-3">
          <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
          <div class="input-group">
            <span class="input-group-text align-items-start">
              <i class="bx bx-text"></i>
            </span>
            <textarea 
              name="description" 
              class="form-control @error('description') is-invalid @enderror" 
              id="description"
              rows="4" 
              required
              data-min-length="10"
              data-max-length="2000"
              placeholder="Description détaillée du défi...">{{ old('description', $defi->description) }}</textarea>
            @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        
        <div class="row g-3">
          <div class="col-md-6">
            <label for="date_debut" class="form-label">Start Date <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-calendar"></i>
              </span>
              <input 
                name="date_debut" 
                type="date" 
                class="form-control @error('date_debut') is-invalid @enderror" 
                id="date_debut"
                value="{{ old('date_debut', $defi->date_debut) }}"
                required
                data-min-date="today">
              @error('date_debut')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          
          <div class="col-md-6">
            <label for="date_fin" class="form-label">End Date <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">
                <i class="bx bx-calendar"></i>
              </span>
              <input 
                name="date_fin" 
                type="date" 
                class="form-control @error('date_fin') is-invalid @enderror" 
                id="date_fin"
                value="{{ old('date_fin', $defi->date_fin) }}"
                required
                data-min-date="tomorrow"
                data-depends-on="date_debut">
              @error('date_fin')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        
        <div class="mt-4 d-flex gap-2">
          <button class="btn btn-primary" type="submit">
            <i class="bx bx-save me-1"></i> Update
          </button>
          <a href="{{ route('defis.index') }}" class="btn btn-outline-secondary">
            <i class="bx bx-x me-1"></i> Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('js/validation.js') }}"></script>
<script>
// Enhanced challenge edit form validation
document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('challengeEditForm');
  const titreInput = document.getElementById('titre');
  const descriptionTextarea = document.getElementById('description');
  const dateDebutInput = document.getElementById('date_debut');
  const dateFinInput = document.getElementById('date_fin');
  
  // Set minimum date to today for start date
  if (dateDebutInput) {
    const today = new Date().toISOString().split('T')[0];
    dateDebutInput.setAttribute('min', today);
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
  
  // Real-time validation for start date
  if (dateDebutInput) {
    dateDebutInput.addEventListener('change', function() {
      validateDateField(this);
      updateEndDateMin();
    });
  }
  
  // Real-time validation for end date
  if (dateFinInput) {
    dateFinInput.addEventListener('change', function() {
      validateDateField(this);
      validateDateRange();
    });
  }
  
  // Form submission validation
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      
      let isValid = true;
      
      // Validate all fields
      if (!validateTextField(titreInput)) isValid = false;
      if (!validateTextField(descriptionTextarea)) isValid = false;
      if (!validateDateField(dateDebutInput)) isValid = false;
      if (!validateDateField(dateFinInput)) isValid = false;
      if (!validateDateRange()) isValid = false;
      
      if (isValid) {
        form.submit();
      } else {
        showValidationSummary();
      }
    });
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
  
  function validateDateField(field) {
    const value = field.value;
    if (!value) {
      setFieldInvalid(field, 'La date est obligatoire.');
      return false;
    }
    
    const selectedDate = new Date(value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (field.id === 'date_debut' && selectedDate < today) {
      setFieldInvalid(field, 'La date de début ne peut pas être dans le passé.');
      return false;
    }
    
    if (field.id === 'date_fin' && selectedDate <= today) {
      setFieldInvalid(field, 'La date de fin doit être après aujourd\'hui.');
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
  
  function validateDateRange() {
    if (!dateDebutInput.value || !dateFinInput.value) {
      return true; // Let individual field validation handle empty fields
    }
    
    const startDate = new Date(dateDebutInput.value);
    const endDate = new Date(dateFinInput.value);
    
    if (endDate <= startDate) {
      setFieldInvalid(dateFinInput, 'La date de fin doit être après la date de début.');
      return false;
    }
    
    const daysDiff = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    
    if (daysDiff > 365) {
      setFieldInvalid(dateFinInput, 'La durée du défi ne peut pas dépasser 365 jours.');
      return false;
    }
    
    if (daysDiff < 1) {
      setFieldInvalid(dateFinInput, 'La durée du défi doit être d\'au moins 1 jour.');
      return false;
    }
    
    setFieldValid(dateFinInput);
    return true;
  }
  
  function updateEndDateMin() {
    if (dateDebutInput.value) {
      const startDate = new Date(dateDebutInput.value);
      const nextDay = new Date(startDate);
      nextDay.setDate(nextDay.getDate() + 1);
      dateFinInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
    }
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