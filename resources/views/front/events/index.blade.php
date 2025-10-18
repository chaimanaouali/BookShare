@extends('front.layouts.app')

@section('content')
<div class="container py-5">
  <!-- Mes défis en cours - Section pour utilisateurs connectés -->
  @auth
    @php
      $userParticipations = \App\Models\ParticipationDefi::where('user_id', Auth::id())
        ->whereIn('status', ['en_cours', 'abandonne'])
        ->with(['defi', 'livre'])
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
    @endphp
    
    @if($userParticipations->count() > 0)
      <div class="row mb-5">
        <div class="col-12">
          <div class="card enhanced-card">
            <div class="card-header">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <h4 class="mb-0 fw-bold text-dark">Mes défis en cours</h4>
                  <p class="text-muted mb-0 mt-1">Continuez votre progression dans vos défis de lecture</p>
                </div>
                <a href="{{ route('participation-defis.my-participations') }}" class="btn btn-outline-primary btn-enhanced">
                  <i class="bx bx-list-ul me-2"></i>Voir toutes mes participations
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="row g-3">
                @foreach($userParticipations as $participation)
                  <div class="col-md-4">
                    <div class="card border h-100">
                      <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                          <div class="me-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                            <div class="d-flex align-items-center justify-content-center h-100">
                              <i class="bx bx-trophy text-white" style="font-size: 20px;"></i>
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="card-title fw-bold text-dark mb-1">{{ $participation->defi->titre }}</h6>
                            <p class="text-muted small mb-1">{{ $participation->livre->title }}</p>
                            <span class="badge bg-primary">{{ $participation->livre->format ?: 'PDF' }}</span>
                          </div>
                        </div>
                        
                        <div class="mb-3">
                          <small class="text-muted d-block">Statut</small>
                          <span class="badge bg-info">En cours</span>
                        </div>
                        
                        <div class="mb-3">
                          <small class="text-muted d-block">Progression</small>
                          <span class="fw-medium">
                            Commencé le {{ $participation->date_debut_lecture ? \Carbon\Carbon::parse($participation->date_debut_lecture)->translatedFormat('d M Y') : 'Non défini' }}
                          </span>
                        </div>
                        
                        <div class="d-flex gap-2">
                          <button class="btn btn-primary btn-sm flex-grow-1" data-participation-id="{{ $participation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                            <i class="bx bx-eye me-1"></i>Voir
                          </button>
                          <button class="btn btn-success btn-sm" data-participation-id="{{ $participation->id }}" onclick="completeChallenge(this.dataset.participationId)">
                            <i class="bx bx-check me-1"></i>Terminer
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endauth

  <div class="row g-4">
    <div class="col-lg-7">
      @php $featured = $events->first(); @endphp
      @if($featured)
        <a href="{{ route('front.events.show', $featured) }}" class="text-decoration-none text-reset">
          <div class="card border-0 shadow-sm overflow-hidden event-card">
            @if($featured->image)
              <img src="/{{ $featured->image }}" alt="{{ $featured->titre }}" class="event-card-img" style="width:100%;height:420px;object-fit:cover;" />
            @endif
            <div class="card-body">
              <div class="d-flex align-items-center gap-3 mb-2">
                <small class="text-muted"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($featured->date_evenement)->translatedFormat('d M Y') }}</small>
                <span class="badge bg-primary">{{ ucfirst($featured->type) }}</span>
              </div>
              <h3 class="card-title mb-2">{{ $featured->titre }}</h3>
              <p class="text-muted mb-0">{{ Str::limit($featured->description, 160) }}</p>
            </div>
          </div>
        </a>
      @endif
    </div>
    <div class="col-lg-5">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0">Derniers échanges</h5>
      </div>
      @php $others = $events->slice(1); @endphp
      @forelse($others as $event)
        <a href="{{ route('front.events.show', $event) }}" class="text-decoration-none text-reset">
          <div class="card border-0 shadow-sm mb-3 event-mini-card">
            <div class="row g-0 align-items-center">
              <div class="col-4">
                @if($event->image)
                  <img src="/{{ $event->image }}" alt="{{ $event->titre }}" class="rounded-start event-mini-img" style="width:100%;height:120px;object-fit:cover;">
                @endif
              </div>
              <div class="col-8">
                <div class="card-body py-3">
                  <small class="text-muted d-block mb-1"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->date_evenement)->translatedFormat('d M Y') }}</small>
                  <h6 class="mb-1">{{ $event->titre }}</h6>
                  <p class="text-muted mb-0">{{ Str::limit($event->description, 90) }}</p>
                </div>
              </div>
            </div>
          </div>
        </a>
      @empty
        <div class="text-muted">Aucun événement.</div>
      @endforelse
    </div>
  </div>

  
</div>

<!-- Participation Modal -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Ma participation au défi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="participationModalBody">
        <!-- Content will be loaded here -->
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

  /* Animated event cards */
  .event-card { border-radius: 16px; transition: transform .25s ease, box-shadow .25s ease; }
  .event-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.12); }
  .event-card-img { transition: transform .35s ease; }
  .event-card:hover .event-card-img { transform: scale(1.04); }

  /* Animated mini cards */
  .event-mini-card { transition: transform .2s ease, box-shadow .2s ease; border-radius: 14px; overflow: hidden; }
  .event-mini-card:hover { transform: translateY(-3px); box-shadow: 0 10px 22px rgba(0,0,0,0.12); }
  .event-mini-img { transition: transform .35s ease, filter .35s ease; }
  .event-mini-card:hover .event-mini-img { transform: scale(1.06); filter: saturate(1.1); }

  /* Subtle fade-in on load */
  .event-card, .event-mini-card { opacity: 0; animation: cardFadeIn .5s ease forwards; }
  .event-mini-card { animation-delay: .05s; }
  @keyframes cardFadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection

@section('extra-js')
<script>
// Show participation modal
function showParticipationModal(participationId) {
  const modal = new bootstrap.Modal(document.getElementById('participationModal'));
  const modalBody = document.getElementById('participationModalBody');
  
  // Show loading
  modalBody.innerHTML = `
    <div class="text-center py-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <p class="mt-2">Chargement de votre participation...</p>
    </div>
  `;
  
  modal.show();
  
  // Load participation content
  fetch(`/participation-defis/${participationId}/modal-content`)
    .then(response => response.text())
    .then(html => {
      modalBody.innerHTML = html;
      bindParticipationModalHandlers();
    })
    .catch(error => {
      console.error('Error loading participation:', error);
      modalBody.innerHTML = `
        <div class="alert alert-danger">
          <i class="bx bx-error-circle me-2"></i>
          Erreur lors du chargement de votre participation.
        </div>
      `;
    });
}

// Complete challenge function
function completeChallenge(participationId) {
  showParticipationModal(participationId);
}

function bindParticipationModalHandlers() {
  const modalBody = document.getElementById('participationModalBody');
  if (!modalBody) return;

  // Open reader
  const readerBtn = modalBody.querySelector('[data-role="open-reader"]');
  if (readerBtn && readerBtn.dataset.fileUrl) {
    readerBtn.addEventListener('click', () => {
      const readerModal = new bootstrap.Modal(document.getElementById('bookReaderModal'));
      readerModal.show();
    });
  }

  // Redirect helper buttons
  modalBody.querySelectorAll('[data-role="go-events"],[data-role="go-participations"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-redirect-url');
      if (url) window.location.href = url;
    });
  });

  // Complete defi sets status and submits via AJAX
  const completeBtn = modalBody.querySelector('[data-role="complete-defi"]');
  const form = modalBody.querySelector('#participation-form');
  if (completeBtn && form) {
    completeBtn.addEventListener('click', () => {
      const statusSelect = form.querySelector('#status');
      if (statusSelect) statusSelect.value = 'termine';
      submitParticipationForm(form, true);
    });
  }

  // Hijack normal submit to AJAX
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      submitParticipationForm(form, false);
    });
  }
}

function submitParticipationForm(form, redirectOnComplete) {
  const formData = new FormData(form);
  const action = form.getAttribute('action');

  fetch(action, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: formData
  })
  .then(async response => {
    if (!response.ok) throw new Error('HTTP ' + response.status);
    // On success, reload modal content to reflect changes
    const participationIdMatch = action.match(/participation-defis\/(\d+)/);
    const participationId = participationIdMatch ? participationIdMatch[1] : null;
    if (participationId) {
      return fetch(`/participation-defis/${participationId}/modal-content`).then(r => r.text());
    }
    return null;
  })
  .then(html => {
    if (!html) return;
    const modalBody = document.getElementById('participationModalBody');
    modalBody.innerHTML = html;
    bindParticipationModalHandlers();

    // Optional redirect after completion
    if (redirectOnComplete) {
      const statusSelect = modalBody.querySelector('#status');
      if (statusSelect && statusSelect.value === 'termine') {
        window.location.href = '/events';
      }
    }

    // Toast feedback
    const success = document.createElement('div');
    success.className = 'alert alert-success mt-3';
    success.textContent = 'Participation mise à jour avec succès !';
    modalBody.prepend(success);
    setTimeout(() => success.remove(), 2000);
  })
  .catch(err => {
    console.error(err);
    const modalBody = document.getElementById('participationModalBody');
    const error = document.createElement('div');
    error.className = 'alert alert-danger mt-3';
    error.textContent = 'Erreur lors de la mise à jour.';
    modalBody.prepend(error);
    setTimeout(() => error.remove(), 3000);
  });
}
</script>
@endsection


