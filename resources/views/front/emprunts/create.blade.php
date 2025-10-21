@extends('front.layouts.app')

@section('title', 'Créer un Emprunt')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Créer un <em>Nouvel</em> <span>Emprunt</span></h2>
                            <p>Remplissez le formulaire pour créer un nouvel emprunt de livre.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="create emprunt">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="create-emprunt" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Créer un Nouvel Emprunt</h2>
                    <p>Remplissez le formulaire pour créer un nouvel emprunt</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('emprunts.store') }}" method="POST" id="empruntForm" novalidate>
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="utilisateur_id" class="form-label">Utilisateur *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{{ $currentUser->name }}" readonly>
                                        <input type="hidden" name="utilisateur_id" value="{{ $currentUser->id }}">
                                        <span class="input-group-text">
                                            <i class="bx bx-user-check text-success"></i>
                                        </span>
                                    </div>
                                    <small class="form-text text-muted">Vous êtes automatiquement sélectionné comme utilisateur</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="livre_id" class="form-label">Livre <span class="text-danger">*</span></label>
                                    <select class="form-select @error('livre_id') is-invalid @enderror" 
                                            id="livre_id" 
                                            name="livre_id" 
                                            required
                                            data-validation="required|exists:livres,id">
                                        <option value="">Sélectionner un livre</option>
                                        @foreach($livres as $livre)
                                            <option value="{{ $livre->id }}" {{ old('livre_id') == $livre->id ? 'selected' : '' }}>
                                                {{ $livre->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('livre_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="livre_id_error"></div>
                                    <div class="form-text">Choisissez le livre que vous souhaitez emprunter</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_emprunt" class="form-label">Date d'Emprunt <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('date_emprunt') is-invalid @enderror" 
                                           id="date_emprunt" 
                                           name="date_emprunt" 
                                           value="{{ old('date_emprunt', date('Y-m-d')) }}" 
                                           required
                                           max="{{ date('Y-m-d') }}"
                                           data-validation="required|date|before_or_equal:today">
                                    @error('date_emprunt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="date_emprunt_error"></div>
                                    <div class="form-text">La date d'emprunt ne peut pas être dans le futur</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_retour_prev" class="form-label">Date de Retour Prévue <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('date_retour_prev') is-invalid @enderror" 
                                           id="date_retour_prev" 
                                           name="date_retour_prev" 
                                           value="{{ old('date_retour_prev') }}" 
                                           required
                                           min="{{ date('Y-m-d') }}"
                                           data-validation="required|date|after:date_emprunt|after_or_equal:today">
                                    @error('date_retour_prev')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="date_retour_prev_error"></div>
                                    <div class="form-text">La date de retour doit être après la date d'emprunt (max 30 jours)</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="date_retour_eff" class="form-label">Date de Retour Effective</label>
                                    <input type="date" 
                                           class="form-control @error('date_retour_eff') is-invalid @enderror" 
                                           id="date_retour_eff" 
                                           name="date_retour_eff" 
                                           value="{{ old('date_retour_eff') }}"
                                           data-validation="nullable|date|after_or_equal:date_emprunt">
                                    @error('date_retour_eff')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="date_retour_eff_error"></div>
                                    <div class="form-text">Optionnel - Date effective de retour du livre</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="statut" class="form-label">Statut <span class="text-danger">*</span></label>
                                    <select class="form-select @error('statut') is-invalid @enderror" 
                                            id="statut" 
                                            name="statut" 
                                            required
                                            data-validation="required|in:En cours,Retourné,En retard">
                                        <option value="">Sélectionner un statut</option>
                                        <option value="En cours" {{ old('statut') == 'En cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="Retourné" {{ old('statut') == 'Retourné' ? 'selected' : '' }}>Retourné</option>
                                        <option value="En retard" {{ old('statut') == 'En retard' ? 'selected' : '' }}>En retard</option>
                                    </select>
                                    @error('statut')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="statut_error"></div>
                                    <div class="form-text">Statut actuel de l'emprunt</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="penalite" class="form-label">Pénalité (€)</label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="999.99"
                                           class="form-control @error('penalite') is-invalid @enderror" 
                                           id="penalite" 
                                           name="penalite" 
                                           value="{{ old('penalite', 0) }}"
                                           data-validation="nullable|numeric|min:0|max:999.99">
                                    @error('penalite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="penalite_error"></div>
                                    <div class="form-text">Montant de la pénalité en euros (max 999.99€)</div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="commentaire" class="form-label">Commentaire</label>
                                    <textarea class="form-control @error('commentaire') is-invalid @enderror" 
                                              id="commentaire" 
                                              name="commentaire" 
                                              rows="3"
                                              maxlength="500"
                                              data-validation="nullable|max:500|regex:^[a-zA-Z0-9\s\-_.,!?()\n\r]+$">{{ old('commentaire') }}</textarea>
                                    @error('commentaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="commentaire_error"></div>
                                    <div class="form-text">
                                        Commentaire optionnel sur l'emprunt
                                        <span class="text-muted">(<span id="commentaire_count">0</span>/500 caractères)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('emprunts.index') }}" class="btn btn-secondary me-2">Annuler</a>
                                <button type="submit" class="btn btn-primary" id="submitEmpruntBtn">
                                    <i class="bx bx-plus me-1"></i> Créer l'Emprunt
                                </button>
                            </div>
                        </form>
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
    .form-select.is-invalid { 
        border-color: #dc3545; 
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); 
    }
    .form-select.is-valid { 
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
    const form = document.getElementById('empruntForm');
    const livreSelect = document.getElementById('livre_id');
    const dateEmpruntInput = document.getElementById('date_emprunt');
    const dateRetourPrevInput = document.getElementById('date_retour_prev');
    const dateRetourEffInput = document.getElementById('date_retour_eff');
    const statutSelect = document.getElementById('statut');
    const penaliteInput = document.getElementById('penalite');
    const commentaireInput = document.getElementById('commentaire');
    const commentaireCount = document.getElementById('commentaire_count');
    const submitBtn = document.getElementById('submitEmpruntBtn');
    
    if (form) {
        // Character counter for commentaire
        function updateCharacterCount(input, counter, maxLength) {
            const count = input.value.length;
            counter.textContent = count;
            
            if (count > maxLength * 0.9) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-warning');
            } else if (count > maxLength * 0.8) {
                counter.classList.add('text-warning');
                counter.classList.remove('text-danger');
            } else {
                counter.classList.remove('text-warning', 'text-danger');
            }
        }
        
        // Real-time character counting
        commentaireInput.addEventListener('input', function() {
            updateCharacterCount(this, commentaireCount, 500);
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
            
            if (fieldName === 'livre_id') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'Le livre est obligatoire.';
                }
            } else if (fieldName === 'date_emprunt') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'La date d\'emprunt est obligatoire.';
                } else {
                    const date = new Date(value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (date > today) {
                        isValid = false;
                        errorMessage = 'La date d\'emprunt ne peut pas être dans le futur.';
                    }
                }
            } else if (fieldName === 'date_retour_prev') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'La date de retour prévue est obligatoire.';
                } else {
                    const dateEmprunt = new Date(dateEmpruntInput.value);
                    const dateRetour = new Date(value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (dateRetour < today) {
                        isValid = false;
                        errorMessage = 'La date de retour prévue doit être au moins aujourd\'hui.';
                    } else if (dateEmpruntInput.value && dateRetour <= dateEmprunt) {
                        isValid = false;
                        errorMessage = 'La date de retour prévue doit être après la date d\'emprunt.';
                    } else if (dateEmpruntInput.value) {
                        const diffDays = Math.ceil((dateRetour - dateEmprunt) / (1000 * 60 * 60 * 24));
                        if (diffDays > 30) {
                            isValid = false;
                            errorMessage = 'La durée d\'emprunt ne peut pas dépasser 30 jours.';
                        } else if (diffDays < 1) {
                            isValid = false;
                            errorMessage = 'La durée d\'emprunt doit être d\'au moins 1 jour.';
                        }
                    }
                }
            } else if (fieldName === 'date_retour_eff') {
                if (value) {
                    const dateEmprunt = new Date(dateEmpruntInput.value);
                    const dateRetour = new Date(value);
                    
                    if (dateEmpruntInput.value && dateRetour < dateEmprunt) {
                        isValid = false;
                        errorMessage = 'La date de retour effective ne peut pas être antérieure à la date d\'emprunt.';
                    }
                }
            } else if (fieldName === 'statut') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'Le statut est obligatoire.';
                } else if (!['En cours', 'Retourné', 'En retard'].includes(value)) {
                    isValid = false;
                    errorMessage = 'Le statut sélectionné n\'est pas valide.';
                }
            } else if (fieldName === 'penalite') {
                if (value) {
                    const penalite = parseFloat(value);
                    if (isNaN(penalite) || penalite < 0) {
                        isValid = false;
                        errorMessage = 'La pénalité doit être un nombre positif.';
                    } else if (penalite > 999.99) {
                        isValid = false;
                        errorMessage = 'La pénalité ne peut pas dépasser 999.99€.';
                    }
                }
            } else if (fieldName === 'commentaire') {
                if (value) {
                    if (value.length > 500) {
                        isValid = false;
                        errorMessage = 'Le commentaire ne peut pas dépasser 500 caractères.';
                    } else if (!/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/.test(value)) {
                        isValid = false;
                        errorMessage = 'Le commentaire contient des caractères non autorisés.';
                    } else if (/(.)\1{8,}/.test(value)) {
                        isValid = false;
                        errorMessage = 'Le commentaire ne peut pas contenir de caractères répétés plus de 8 fois.';
                    } else {
                        // Check for meaningful content
                        const meaningfulContent = value.replace(/[\s\p{P}]+/gu, '');
                        if (meaningfulContent.length < 3) {
                            isValid = false;
                            errorMessage = 'Le commentaire doit contenir au moins 3 caractères significatifs.';
                        }
                        
                        // Check for excessive special characters
                        const specialCharCount = (value.match(/[!@#$%^&*()_+=\[\]{}|;:,.<>?]/g) || []).length;
                        if (specialCharCount > value.length * 0.3) {
                            isValid = false;
                            errorMessage = 'Le commentaire contient trop de caractères spéciaux.';
                        }
                        
                        // Check for all caps
                        if (value.length > 10 && value === value.toUpperCase()) {
                            isValid = false;
                            errorMessage = 'Le commentaire ne peut pas être entièrement en majuscules.';
                        }
                    }
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
            isFormValid &= validateField(livreSelect);
            isFormValid &= validateField(dateEmpruntInput);
            isFormValid &= validateField(dateRetourPrevInput);
            isFormValid &= validateField(dateRetourEffInput);
            isFormValid &= validateField(statutSelect);
            isFormValid &= validateField(penaliteInput);
            isFormValid &= validateField(commentaireInput);
            
            if (isFormValid) {
                // Show loading state
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Création...';
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
                showToast('Veuillez corriger les erreurs avant de soumettre.', 'danger');
            }
        });
        
        // Real-time validation on blur
        [livreSelect, dateEmpruntInput, dateRetourPrevInput, dateRetourEffInput, statutSelect, penaliteInput, commentaireInput].forEach(field => {
            if (field) {
                field.addEventListener('blur', function() {
                    validateField(this);
                });
            }
        });
        
        // Initialize character count
        updateCharacterCount(commentaireInput, commentaireCount, 500);
        
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
    }
});
</script>
@endsection
