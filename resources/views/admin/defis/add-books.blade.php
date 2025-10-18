@extends('layouts/contentNavbarLayout')

@section('title', 'Ajouter des livres au défi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Ajouter des livres au défi</h4>
          <p class="text-muted mb-0 mt-1">{{ $defi->titre }}</p>
        </div>
        <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary btn-enhanced px-3 py-2" style="border-radius: 8px;">
          <i class="bx bx-arrow-back me-2"></i>Retour
        </a>
      </div>
    </div>
  </div>

  <!-- Livres disponibles -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Livres disponibles</h5>
    </div>
    
    <form action="{{ route('defis.associate-books', $defi) }}" method="POST" id="add-books-form">
      @csrf
      
      @if($notAssociated->count() > 0)
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 book-events-table">
              <thead>
                <tr>
                  <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                    <input type="checkbox" id="select-all" class="form-check-input">
                    <label for="select-all" class="form-check-label ms-2">SÉLECTIONNER</label>
                  </th>
                  <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                  <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">AUTEUR</th>
                  <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PROPRIÉTAIRE</th>
                  <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">CATÉGORIE</th>
                </tr>
              </thead>
              <tbody>
                @foreach($notAssociated as $livre)
                  <tr class="border-bottom">
                    <td class="py-3 px-4">
                      <input type="checkbox" name="livre_ids[]" value="{{ $livre->id }}" class="form-check-input livre-checkbox" id="livre_{{ $livre->id }}">
                    </td>
                    <td class="py-3 px-4">
                      <div class="d-flex align-items-center">
                        <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                          @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                            <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                          @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                              <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                            </div>
                          @endif
                        </div>
                        <div class="d-flex flex-column">
                          <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $livre->title }}</span>
                          <small class="text-muted" style="font-size: 0.8rem;">{{ $livre->genre ?: 'Genre non spécifié' }}</small>
                        </div>
                      </div>
                    </td>
                    <td class="py-3 px-4">
                      <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->author ?: 'Auteur non spécifié' }}</span>
                    </td>
                    <td class="py-3 px-4">
                      <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->user->name ?? 'Utilisateur inconnu' }}</span>
                    </td>
                    <td class="py-3 px-4">
                      <span class="badge rounded-pill px-3 py-2 badge-enhanced" style="background-color: #e3f2fd; color: #1976d2; font-size: 0.8rem;">
                        {{ $livre->categorie->nom ?? 'Sans catégorie' }}
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        
        <div class="card-footer bg-white border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted" id="selected-count">0 livre(s) sélectionné(s)</span>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;" id="submit-btn" disabled>
                <i class="bx bx-plus me-2"></i>Ajouter les livres sélectionnés
              </button>
            </div>
          </div>
        </div>
      @else
        <div class="card-body p-5 text-center">
          <div class="d-flex flex-column align-items-center">
            <i class="bx bx-book mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
            <h6 class="text-muted mb-2">Aucun livre disponible</h6>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Tous les livres sont déjà associés à des défis</p>
            <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
              <i class="bx bx-arrow-back me-2"></i>Retour au défi
            </a>
          </div>
        </div>
      @endif
      
      @if($alreadyAssociated->count() > 0)
        <div class="card mt-4">
          <div class="card-header">
            <h5 class="mb-0 fw-semibold text-dark">Livres déjà associés à ce défi</h5>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 book-events-table">
                <thead>
                  <tr>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">AUTEUR</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PROPRIÉTAIRE</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($alreadyAssociated as $livre)
                    <tr class="border-bottom">
                      <td class="py-3 px-4">
                        <div class="d-flex align-items-center">
                          <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                            @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                              <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                              <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                              </div>
                            @endif
                          </div>
                          <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $livre->title }}</span>
                            <small class="text-muted" style="font-size: 0.8rem;">{{ $livre->genre ?: 'Genre non spécifié' }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="py-3 px-4">
                        <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->author ?: 'Auteur non spécifié' }}</span>
                      </td>
                      <td class="py-3 px-4">
                        <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->user->name ?? 'Utilisateur inconnu' }}</span>
                      </td>
                      <td class="py-3 px-4">
                        <a href="{{ route('defis.remove-book', [$defi, $livre]) }}" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce livre du défi ?')">
                          <i class="bx bx-trash me-1"></i>Retirer
                        </a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endif
    </form>
  </div>
</div>

<!-- Script inline de fallback -->
<script>
console.log('=== INLINE SCRIPT LOADED ===');

// Vérifier que les éléments existent immédiatement
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== INLINE DOMContentLoaded ===');
    
    const checkboxes = document.querySelectorAll('.livre-checkbox');
    const submitBtn = document.getElementById('submit-btn');
    const countSpan = document.getElementById('selected-count');
    
    console.log('Elements found:', {
        checkboxes: checkboxes.length,
        submitBtn: !!submitBtn,
        countSpan: !!countSpan
    });
    
    // Fonction simple de mise à jour
    function simpleUpdate() {
        const checked = document.querySelectorAll('.livre-checkbox:checked');
        const count = checked.length;
        
        console.log('Simple update - checked:', count);
        
        if (countSpan) {
            countSpan.textContent = count + ' livre(s) sélectionné(s)';
        }
        
        if (submitBtn) {
            submitBtn.disabled = count === 0;
            console.log('Submit button disabled:', count === 0);
        }
    }
    
    // Ajouter les événements
    checkboxes.forEach((cb, i) => {
        cb.addEventListener('change', function() {
            console.log(`Checkbox ${i} changed:`, this.checked);
            simpleUpdate();
        });
    });
    
    // Mise à jour initiale
    simpleUpdate();
});
</script>
@endsection

@section('extra-css')
<style>
  /* Enhanced Défis Table Styling */
  .book-events-table {
    border-collapse: separate;
    border-spacing: 0;
  }
  
  .book-events-table thead th {
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
    color: #6c757d;
    background-color: #f8f9fa;
  }
  
  .book-events-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f4;
  }
  
  .book-events-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  
  .book-events-table tbody tr:last-child {
    border-bottom: none;
  }
  
  /* Enhanced Card Styling */
  .enhanced-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
  }
  
  .enhanced-card .card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
  }
  
  /* Enhanced Button Styling */
  .btn-enhanced {
    transition: all 0.2s ease;
    font-weight: 500;
  }
  
  .btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  /* Enhanced Badge Styling */
  .badge-enhanced {
    font-weight: 500;
    letter-spacing: 0.3px;
  }
  
  /* Checkbox styling */
  .form-check-input:checked {
    background-color: #1976d2;
    border-color: #1976d2;
  }
  
  .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
  }
  
  /* Bouton désactivé */
  #submit-btn:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    background-color: #6c757d !important;
    border-color: #6c757d !important;
  }
  
  #submit-btn:not(:disabled) {
    opacity: 1 !important;
    cursor: pointer !important;
  }
</style>
@endsection

@section('extra-js')
<script>
// Test simple pour vérifier que le JavaScript se charge
console.log('=== JAVASCRIPT LOADED ===');
console.log('Current time:', new Date().toISOString());

// Fonction globale pour mettre à jour le compteur et le bouton
function updateSelectedCount() {
    try {
        console.log('=== UPDATE SELECTED COUNT ===');
        
        const selectedCheckboxes = document.querySelectorAll('.livre-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        const selectedCountSpan = document.getElementById('selected-count');
        const submitBtn = document.getElementById('submit-btn');
        const selectAllCheckbox = document.getElementById('select-all');
        const livreCheckboxes = document.querySelectorAll('.livre-checkbox');
        
        console.log('Selected checkboxes found:', selectedCheckboxes.length);
        console.log('Selected count:', selectedCount);
        console.log('Submit button found:', !!submitBtn);
        console.log('Count span found:', !!selectedCountSpan);
        console.log('Select all checkbox found:', !!selectAllCheckbox);
        console.log('Total checkboxes found:', livreCheckboxes.length);
        
        // Debug: Afficher tous les checkboxes
        console.log('All checkboxes:');
        livreCheckboxes.forEach((cb, i) => {
            console.log(`  Checkbox ${i}:`, {
                id: cb.id,
                value: cb.value,
                checked: cb.checked,
                classList: cb.classList.toString()
            });
        });
        
        // Mettre à jour le compteur
        if (selectedCountSpan) {
            selectedCountSpan.textContent = selectedCount + ' livre(s) sélectionné(s)';
            console.log('Count span updated to:', selectedCountSpan.textContent);
        } else {
            console.error('ERROR: selectedCountSpan not found!');
        }
        
        // Mettre à jour le bouton de soumission
        if (submitBtn) {
            const shouldDisable = selectedCount === 0;
            const oldDisabled = submitBtn.disabled;
            submitBtn.disabled = shouldDisable;
            console.log('Submit button state changed:', {
                oldDisabled: oldDisabled,
                newDisabled: shouldDisable,
                selectedCount: selectedCount
            });
            
            // Changer l'apparence du bouton
            if (shouldDisable) {
                submitBtn.classList.add('disabled');
                submitBtn.style.opacity = '0.6';
                console.log('Button appearance: DISABLED');
            } else {
                submitBtn.classList.remove('disabled');
                submitBtn.style.opacity = '1';
                console.log('Button appearance: ENABLED');
            }
        } else {
            console.error('ERROR: submitBtn not found!');
        }
        
        // Mettre à jour la case "Tout sélectionner"
        if (selectAllCheckbox && livreCheckboxes.length > 0) {
            if (selectedCount === 0) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = false;
                console.log('Select all: unchecked');
            } else if (selectedCount === livreCheckboxes.length) {
                selectAllCheckbox.indeterminate = false;
                selectAllCheckbox.checked = true;
                console.log('Select all: checked');
            } else {
                selectAllCheckbox.indeterminate = true;
                console.log('Select all: indeterminate');
            }
        } else {
            console.log('Select all checkbox not found or no checkboxes');
        }
        
        console.log('=== UPDATE COMPLETE ===');
    } catch (error) {
        console.error('ERROR in updateSelectedCount:', error);
        console.error('Error stack:', error.stack);
    }
}

// Fonction pour initialiser les événements
function initializeCheckboxEvents() {
    try {
        console.log('=== INITIALIZING CHECKBOX EVENTS ===');
        
        const selectAllCheckbox = document.getElementById('select-all');
        const livreCheckboxes = document.querySelectorAll('.livre-checkbox');
        
        console.log('Select all checkbox found:', !!selectAllCheckbox);
        console.log('Individual checkboxes found:', livreCheckboxes.length);
        
        // Événement pour "Tout sélectionner"
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                try {
                    console.log('Select all changed:', this.checked);
                    livreCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateSelectedCount();
                } catch (error) {
                    console.error('ERROR in select all change:', error);
                }
            });
            console.log('Select all event listener added');
        } else {
            console.error('ERROR: Select all checkbox not found!');
        }
        
        // Événements pour les cases individuelles
        livreCheckboxes.forEach((checkbox, index) => {
            try {
                checkbox.addEventListener('change', function() {
                    try {
                        console.log(`Checkbox ${index} (${this.value}) changed:`, this.checked);
                        updateSelectedCount();
                    } catch (error) {
                        console.error(`ERROR in checkbox ${index} change:`, error);
                    }
                });
                console.log(`Event listener added to checkbox ${index}`);
            } catch (error) {
                console.error(`ERROR adding event listener to checkbox ${index}:`, error);
            }
        });
        
        console.log('=== CHECKBOX EVENTS INITIALIZED ===');
    } catch (error) {
        console.error('ERROR in initializeCheckboxEvents:', error);
        console.error('Error stack:', error.stack);
    }
}


// Initialisation principale
document.addEventListener('DOMContentLoaded', function() {
    try {
        console.log('=== STARTING ADD-BOOKS SCRIPT ===');
        
        // Attendre un peu pour s'assurer que le DOM est complètement chargé
        setTimeout(function() {
            try {
                console.log('=== INITIALIZING AFTER DELAY ===');
                
                // Vérifier que tous les éléments existent
                const selectAllCheckbox = document.getElementById('select-all');
                const livreCheckboxes = document.querySelectorAll('.livre-checkbox');
                const selectedCountSpan = document.getElementById('selected-count');
                const submitBtn = document.getElementById('submit-btn');
                
                console.log('DOM Elements check:', {
                    selectAll: !!selectAllCheckbox,
                    checkboxes: livreCheckboxes.length,
                    countSpan: !!selectedCountSpan,
                    submitBtn: !!submitBtn
                });
                
                // Initialiser les événements
                initializeCheckboxEvents();
                
                // Mise à jour initiale
                updateSelectedCount();
                
                console.log('=== SCRIPT INITIALIZATION COMPLETE ===');
            } catch (error) {
                console.error('ERROR in delayed initialization:', error);
                console.error('Error stack:', error.stack);
            }
        }, 100);
    } catch (error) {
        console.error('ERROR in DOMContentLoaded:', error);
        console.error('Error stack:', error.stack);
    }
});

// Événement de délégation pour les cases cochées ajoutées dynamiquement
document.addEventListener('change', function(e) {
    try {
        if (e.target && e.target.classList.contains('livre-checkbox')) {
            console.log('Dynamic checkbox change detected:', e.target.checked);
            updateSelectedCount();
        }
    } catch (error) {
        console.error('ERROR in delegation change event:', error);
    }
});

// Gestionnaire d'erreurs global
window.addEventListener('error', function(e) {
    console.error('GLOBAL ERROR:', e.error);
    console.error('Error message:', e.message);
    console.error('Error filename:', e.filename);
    console.error('Error line:', e.lineno);
    console.error('Error column:', e.colno);
});

// Gestionnaire d'erreurs non capturées
window.addEventListener('unhandledrejection', function(e) {
    console.error('UNHANDLED PROMISE REJECTION:', e.reason);
});
</script>
@endsection
