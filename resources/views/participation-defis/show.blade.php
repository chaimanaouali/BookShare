@extends('layouts/contentNavbarLayout')

@section('title', 'Détails de la participation')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Success Message for Completed Challenge -->
  @if($participation->status === 'termine')
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      <div class="d-flex align-items-center">
        <i class="bx bx-trophy me-3" style="font-size: 2rem; color: #28a745;"></i>
        <div>
          <h5 class="alert-heading mb-1">🎉 Félicitations !</h5>
          <p class="mb-2">Vous avez terminé le défi "{{ $participation->defi->titre }}" avec succès !</p>
          <div class="d-flex gap-2">
            <a href="{{ route('front.events.index') }}" class="btn btn-success btn-sm">
              <i class="bx bx-home me-1"></i>Voir les événements
            </a>
            <a href="{{ route('participation-defis.my-participations') }}" class="btn btn-outline-success btn-sm">
              <i class="bx bx-list-ul me-1"></i>Mes participations
            </a>
          </div>
        </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Ma participation au défi</h4>
          <p class="text-muted mb-0 mt-1">{{ $participation->defi->titre }}</p>
        </div>
        <a href="{{ route('participation-defis.my-participations') }}" class="btn btn-secondary btn-enhanced px-3 py-2" style="border-radius: 8px;">
          <i class="bx bx-arrow-back me-2"></i>Retour
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de début</small>
            <span class="fw-medium">{{ $participation->defi->date_debut ? \Carbon\Carbon::parse($participation->defi->date_debut)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de fin</small>
            <span class="fw-medium">{{ $participation->defi->date_fin ? \Carbon\Carbon::parse($participation->defi->date_fin)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Book Reading Section -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Livre à lire</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-3">
          <div class="text-center">
            <div class="mb-3" style="width: 120px; height: 160px; border-radius: 12px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: 0 auto;">
              @if($participation->livre->cover_image && file_exists(public_path($participation->livre->cover_image)))
                <img src="/{{ $participation->livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
              @else
                <div class="d-flex align-items-center justify-content-center h-100">
                  <i class="bx bx-book text-white" style="font-size: 48px;"></i>
                </div>
              @endif
            </div>
            <h6 class="fw-semibold">{{ $participation->livre->title }}</h6>
            <p class="text-muted small">{{ $participation->livre->author ?: 'Auteur non spécifié' }}</p>
          </div>
        </div>
        <div class="col-md-9">
          <div class="row mb-3">
            <div class="col-md-6">
              <small class="text-muted d-block">Auteur</small>
              <span class="fw-medium">{{ $participation->livre->author ?: 'Non spécifié' }}</span>
            </div>
            <div class="col-md-6">
              <small class="text-muted d-block">ISBN</small>
              <span class="fw-medium">{{ $participation->livre->isbn ?: 'Non spécifié' }}</span>
            </div>
          </div>
          
          @if($participation->livre->user_description)
            <div class="mb-3">
              <small class="text-muted d-block">Description</small>
              <p class="mb-0">{{ $participation->livre->user_description }}</p>
            </div>
          @endif
          
          <div class="row mb-3">
            <div class="col-md-6">
              <small class="text-muted d-block">Format</small>
              <span class="badge bg-primary">{{ strtoupper($participation->livre->format ?: 'PDF') }}</span>
            </div>
            <div class="col-md-6">
              <small class="text-muted d-block">Taille</small>
              <span class="fw-medium">{{ $participation->livre->taille ?: 'Non spécifiée' }}</span>
            </div>
          </div>
          
          @if($participation->livre->fichier_livre)
            <div class="d-grid gap-2 d-md-flex justify-content-md-start">
              <a href="/storage/{{ $participation->livre->fichier_livre }}" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;" target="_blank">
                <i class="bx bx-download me-2"></i>Télécharger le livre
              </a>
              <button class="btn btn-success btn-enhanced px-4 py-2" style="border-radius: 8px;" onclick="openBookReader()">
                <i class="bx bx-book-open me-2"></i>Lire en ligne
              </button>
            </div>
          @else
            <div class="alert alert-warning">
              <i class="bx bx-info-circle me-2"></i>
              Aucun fichier de livre disponible pour le moment.
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Progress Section -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Progression et notes</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('participation-defis.update-status', $participation) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row mb-4">
          <div class="col-md-6">
            <label for="status" class="form-label">Statut de lecture</label>
            <select name="status" id="status" class="form-select">
              <option value="en_cours" {{ $participation->status === 'en_cours' ? 'selected' : '' }}>En cours</option>
              <option value="termine" {{ $participation->status === 'termine' ? 'selected' : '' }}>Terminé</option>
              <option value="abandonne" {{ $participation->status === 'abandonne' ? 'selected' : '' }}>Abandonné</option>
            </select>
          </div>
          <div class="col-md-6">
            <label for="note" class="form-label">Note (1-5 étoiles)</label>
            <select name="note" id="note" class="form-select">
              <option value="">Pas de note</option>
              <option value="1" {{ $participation->note == 1 ? 'selected' : '' }}>⭐ 1 étoile - Très mauvais</option>
              <option value="2" {{ $participation->note == 2 ? 'selected' : '' }}>⭐⭐ 2 étoiles - Mauvais</option>
              <option value="3" {{ $participation->note == 3 ? 'selected' : '' }}>⭐⭐⭐ 3 étoiles - Moyen</option>
              <option value="4" {{ $participation->note == 4 ? 'selected' : '' }}>⭐⭐⭐⭐ 4 étoiles - Bon</option>
              <option value="5" {{ $participation->note == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐ 5 étoiles - Excellent</option>
            </select>
            <div class="form-text">
              <small class="text-muted">Sélectionnez une note pour évaluer ce livre</small>
            </div>
          </div>
        </div>
        
        <div class="mb-4">
          <label for="commentaire" class="form-label">Commentaire sur le livre</label>
          <textarea name="commentaire" id="commentaire" class="form-control" rows="4" placeholder="Partagez votre avis sur ce livre...">{{ $participation->commentaire }}</textarea>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <small class="text-muted">
              Commencé le {{ $participation->date_debut_lecture ? \Carbon\Carbon::parse($participation->date_debut_lecture)->translatedFormat('d M Y à H:i') : 'Non défini' }}
            </small>
            @if($participation->date_fin_lecture)
              <br>
              <small class="text-success">
                Terminé le {{ \Carbon\Carbon::parse($participation->date_fin_lecture)->translatedFormat('d M Y à H:i') }}
              </small>
            @endif
          </div>
          <div class="d-flex gap-2">
            @if($participation->status !== 'termine')
              <button type="submit" class="btn btn-success btn-enhanced px-4 py-2" style="border-radius: 8px;" onclick="setStatusToComplete()">
                <i class="bx bx-trophy me-2"></i>Terminer le défi
              </button>
            @endif
            <button type="submit" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;">
              <i class="bx bx-save me-2"></i>Mettre à jour
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Book Reader Modal -->
<div class="modal fade" id="bookReaderModal" tabindex="-1" aria-labelledby="bookReaderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookReaderModalLabel">{{ $participation->livre->title }}</h5>
        <div class="d-flex gap-2">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="downloadBook()">
            <i class="bx bx-download me-1"></i>Télécharger
          </button>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body p-0">
        @if($participation->livre->fichier_livre)
          @if(strtolower(pathinfo($participation->livre->fichier_livre, PATHINFO_EXTENSION)) === 'pdf')
            <iframe src="/storage/{{ $participation->livre->fichier_livre }}#toolbar=1&navpanes=1&scrollbar=1" 
                    width="100%" 
                    height="600px" 
                    style="border: none;">
            </iframe>
          @else
            <div class="text-center p-5">
              <div class="mb-3">
                <i class="bx bx-file" style="font-size: 4rem; color: #6c757d;"></i>
              </div>
              <h6 class="text-muted">Lecteur en ligne</h6>
              <p class="text-muted">Le format {{ strtoupper($participation->livre->format) }} n'est pas supporté pour la lecture en ligne.</p>
              <a href="/storage/{{ $participation->livre->fichier_livre }}" class="btn btn-primary" target="_blank">
                <i class="bx bx-download me-2"></i>Télécharger le livre
              </a>
            </div>
          @endif
        @else
          <div class="text-center p-5">
            <div class="mb-3">
              <i class="bx bx-error-circle" style="font-size: 4rem; color: #dc3545;"></i>
            </div>
            <h6 class="text-muted">Fichier non disponible</h6>
            <p class="text-muted">Aucun fichier de livre n'est disponible pour le moment.</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-css')
<style>
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
  
  .btn-enhanced {
    transition: all 0.2s ease;
    font-weight: 500;
  }
  
  .btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  /* Rating select styling */
  #note {
    font-size: 1rem;
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s ease;
  }
  
  #note:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
  }
  
  #note option {
    padding: 0.5rem;
    font-size: 1rem;
  }
</style>
@endsection

@section('extra-js')
<script>
// Simple rating system with select dropdown
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== Rating System Initialized ===');
    
    const noteSelect = document.getElementById('note');
    if (noteSelect) {
        console.log('Rating select found:', noteSelect);
        console.log('Current rating value:', noteSelect.value);
        
        // Add change event listener
        noteSelect.addEventListener('change', function() {
            console.log('Rating changed to:', this.value);
        });
    }
});

// Function to set status to complete when clicking "Terminer le défi"
function setStatusToComplete() {
    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.value = 'termine';
        console.log('Status set to: termine');
        
        // Show confirmation
        if (confirm('Êtes-vous sûr de vouloir terminer ce défi ? Vous serez redirigé vers la page des événements.')) {
            // Submit the form
            document.querySelector('form').submit();
        } else {
            // Reset status if user cancels
            statusSelect.value = '{{ $participation->status }}';
        }
    }
}

// Book reader functions
function openBookReader() {
    const modal = new bootstrap.Modal(document.getElementById('bookReaderModal'));
    modal.show();
}

function downloadBook() {
    const bookUrl = '{{ $participation->livre->fichier_livre ? "/storage/" . $participation->livre->fichier_livre : "" }}';
    if (bookUrl) {
        window.open(bookUrl, '_blank');
    }
}

</script>
@endsection
