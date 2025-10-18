@extends('layouts/contentNavbarLayout')

@section('title', 'Participer au défi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Participer au défi</h4>
          <p class="text-muted mb-0 mt-1">{{ $defi->titre }}</p>
        </div>
        <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary btn-enhanced px-3 py-2" style="border-radius: 8px;">
          <i class="bx bx-arrow-back me-2"></i>Retour au défi
        </a>
      </div>
    </div>
    <div class="card-body">
      @if($defi->description)
        <p class="text-muted mb-3">{{ $defi->description }}</p>
      @endif
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de début</small>
            <span class="fw-medium">{{ $defi->date_debut ? \Carbon\Carbon::parse($defi->date_debut)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de fin</small>
            <span class="fw-medium">{{ $defi->date_fin ? \Carbon\Carbon::parse($defi->date_fin)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Participation Form -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Choisir un livre pour le défi</h5>
    </div>
    
    <form action="{{ route('participation-defis.store', $defi) }}" method="POST">
      @csrf
      
      @if($defi->livres->count() > 0)
        <div class="card-body">
          <div class="mb-4">
            <label class="form-label">Sélectionnez le livre que vous voulez lire <span class="text-danger">*</span></label>
            <div class="row">
              @foreach($defi->livres as $livre)
                <div class="col-md-6 col-lg-4 mb-3">
                  <div class="card h-100 border-2 book-card" style="cursor: pointer;" data-livre-id="{{ $livre->id }}">
                    <div class="card-body">
                      <div class="d-flex align-items-center mb-3">
                        <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                          @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                            <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                          @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                              <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                            </div>
                          @endif
                        </div>
                        <div class="flex-grow-1">
                          <h6 class="mb-1 fw-semibold">{{ $livre->title }}</h6>
                          <small class="text-muted">{{ $livre->author ?: 'Auteur non spécifié' }}</small>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="livre_id" value="{{ $livre->id }}" id="livre_{{ $livre->id }}">
                        </div>
                      </div>
                      @if($livre->user_description)
                        <p class="text-muted small mb-2">{{ Str::limit($livre->user_description, 100) }}</p>
                      @endif
                      <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">{{ $livre->user->name ?? 'Utilisateur inconnu' }}</small>
                        @if($livre->categorie)
                          <span class="badge rounded-pill px-2 py-1" style="background-color: #e3f2fd; color: #1976d2; font-size: 0.7rem;">
                            {{ $livre->categorie->nom }}
                          </span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            @error('livre_id')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
            <textarea name="commentaire" id="commentaire" class="form-control" rows="3" placeholder="Pourquoi voulez-vous participer à ce défi ? Que comptez-vous apprendre ?"></textarea>
            @error('commentaire')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="alert alert-info">
            <i class="bx bx-info-circle me-2"></i>
            <strong>Comment ça marche ?</strong>
            <ul class="mb-0 mt-2">
              <li>Vous choisissez un livre parmi ceux proposés pour ce défi</li>
              <li>Vous commencez votre lecture et pouvez suivre votre progression</li>
              <li>Une fois terminé, vous pouvez noter le livre et partager votre avis</li>
              <li>Vous pouvez abandonner le défi à tout moment</li>
            </ul>
          </div>
        </div>
        
        <div class="card-footer bg-white border-0 py-3 px-4">
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">En participant, vous vous engagez à lire le livre sélectionné</small>
            <button type="submit" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;" id="submit-btn">
              <i class="bx bx-play me-2"></i>Commencer le défi
            </button>
          </div>
        </div>
      @else
        <div class="card-body p-5 text-center">
          <div class="d-flex flex-column align-items-center">
            <i class="bx bx-book mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
            <h6 class="text-muted mb-2">Aucun livre disponible</h6>
            <p class="text-muted mb-3" style="font-size: 0.9rem;">Ce défi n'a pas encore de livres associés</p>
            <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
              <i class="bx bx-arrow-back me-2"></i>Retour au défi
            </a>
          </div>
        </div>
      @endif
    </form>
  </div>
</div>
@endsection

@section('extra-css')
<style>
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
  
  /* Book selection styling */
  .card[onclick] {
    transition: all 0.2s ease;
  }
  
  .card[onclick]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-color: #1976d2 !important;
  }
  
  .card[onclick].selected {
    border-color: #1976d2 !important;
    background-color: #f8f9fa;
  }
  
  /* Radio button styling */
  .form-check-input:checked {
    background-color: #1976d2;
    border-color: #1976d2;
  }
  
  .form-check-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(25, 118, 210, 0.25);
  }
</style>
@endsection

@section('extra-js')
<script>
// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== Participation form initialized ===');
    
    // Add click handlers to all book cards
    document.querySelectorAll('.book-card').forEach(card => {
        card.addEventListener('click', function() {
            const livreId = this.getAttribute('data-livre-id');
            console.log('Book card clicked:', livreId);
            
            // Uncheck all radio buttons
            document.querySelectorAll('input[name="livre_id"]').forEach(radio => {
                radio.checked = false;
            });
            
            // Check the selected one
            const radioButton = document.getElementById('livre_' + livreId);
            if (radioButton) {
                radioButton.checked = true;
                console.log('Radio button checked:', radioButton.id);
            }
            
            // Remove selected class from all cards
            document.querySelectorAll('.book-card').forEach(c => c.classList.remove('selected'));
            
            // Add selected class to clicked card
            this.classList.add('selected');
            
            console.log('Book selected successfully');
        });
    });
    
    // Add change handlers to radio buttons
    document.querySelectorAll('input[name="livre_id"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('Radio button changed:', this.id, this.checked);
            
            // Remove selected class from all cards
            document.querySelectorAll('.book-card').forEach(c => c.classList.remove('selected'));
            
            // Add selected class to the card containing this radio button
            const card = this.closest('.book-card');
            if (card) {
                card.classList.add('selected');
            }
        });
    });
});
</script>
@endsection
