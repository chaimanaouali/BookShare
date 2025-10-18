@extends('front.layouts.app')

@section('title', 'Mes Participations')

@section('content')
<!-- Page Header -->
<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <span class="breadcrumb"><a href="{{ route('front.home') }}">Accueil</a> / Mes Participations</span>
        <h3>Mes Participations aux Défis</h3>
        <p class="text-muted">Suivez votre progression dans les défis de lecture</p>
      </div>
    </div>
  </div>
</div>

<!-- Success Message -->
@if(session('success'))
  <div class="container mt-4">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
@endif

<!-- My Participations Section -->
<div class="container py-5">
  <div class="row">
    <div class="col-12">
      <div class="card enhanced-card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h4 class="mb-0 fw-bold text-dark">Mes Participations</h4>
              <p class="text-muted mb-0 mt-1">Suivez votre progression dans les défis de lecture</p>
            </div>
            <a href="{{ route('front.events.index') }}" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;">
              <i class="bx bx-plus me-2"></i>Découvrir les défis
            </a>
          </div>
        </div>
        
        @if($participations->count() > 0)
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover mb-0 book-events-table">
                <thead>
                  <tr>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">DÉFI</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">STATUT</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PROGRESSION</th>
                    <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">ACTIONS</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($participations as $participation)
                    <tr class="border-bottom">
                      <td class="py-3 px-4">
                        <div class="d-flex align-items-center">
                          <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%);">
                            <div class="d-flex align-items-center justify-content-center h-100">
                              <i class="bx bx-trophy text-white" style="font-size: 20px;"></i>
                            </div>
                          </div>
                          <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $participation->defi->titre }}</span>
                            <small class="text-muted" style="font-size: 0.8rem;">
                              {{ \Carbon\Carbon::parse($participation->defi->date_debut)->translatedFormat('d M Y') }} → 
                              {{ \Carbon\Carbon::parse($participation->defi->date_fin)->translatedFormat('d M Y') }}
                            </small>
                          </div>
                        </div>
                      </td>
                      <td class="py-3 px-4">
                        <div class="d-flex align-items-center">
                          <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            @if($participation->livre->cover_image && file_exists(public_path($participation->livre->cover_image)))
                              <img src="/{{ $participation->livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                              <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                              </div>
                            @endif
                          </div>
                          <div class="d-flex flex-column">
                            <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $participation->livre->title }}</span>
                            <small class="text-muted" style="font-size: 0.8rem;">{{ $participation->livre->author }}</small>
                          </div>
                        </div>
                      </td>
                      <td class="py-3 px-4">
                        @if($participation->status === 'en_cours')
                          <span class="badge bg-info">En cours</span>
                        @elseif($participation->status === 'termine')
                          <span class="badge bg-success">Terminé</span>
                        @elseif($participation->status === 'abandonne')
                          <span class="badge bg-danger">Abandonné</span>
                        @endif
                      </td>
                      <td class="py-3 px-4">
                        @if($participation->status === 'termine')
                          <span class="fw-medium text-success">
                            Terminé le {{ \Carbon\Carbon::parse($participation->date_fin_lecture)->translatedFormat('d M Y') }}
                          </span>
                          @if($participation->note)
                            <div class="mt-1">
                              @for($i = 1; $i <= 5; $i++)
                                <i class="bx bx-star {{ $i <= $participation->note ? 'text-warning' : 'text-muted' }}"></i>
                              @endfor
                            </div>
                          @endif
                        @else
                          <span class="fw-medium text-info">
                            Commencé le {{ \Carbon\Carbon::parse($participation->date_debut_lecture)->translatedFormat('d M Y') }}
                          </span>
                        @endif
                      </td>
                      <td class="py-3 px-4">
                        <div class="d-flex gap-2">
                          <button class="btn btn-primary btn-sm" data-participation-id="{{ $participation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                            <i class="bx bx-eye me-1"></i>Voir
                          </button>
                          @if($participation->status === 'en_cours')
                            <button class="btn btn-success btn-sm" data-participation-id="{{ $participation->id }}" onclick="completeChallenge(this.dataset.participationId)">
                              <i class="bx bx-check me-1"></i>Terminer
                            </button>
                          @endif
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        @else
          <div class="card-body p-5 text-center">
            <div class="d-flex flex-column align-items-center">
              <i class="bx bx-book mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
              <h6 class="text-muted mb-2">Aucune participation</h6>
              <p class="text-muted mb-3" style="font-size: 0.9rem;">Commencez par participer à un défi de lecture</p>
              <a href="{{ route('front.events.index') }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                <i class="bx bx-plus me-2"></i>Découvrir les défis
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Participation Modal -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Détails de la participation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="participationModalBody">
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Quiz Modal -->
<div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quizModalLabel">Quiz du livre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Quiz content will be loaded here -->
        <div id="quizStepInput">
          <div class="mb-3">
            <label for="quizText" class="form-label">Texte du livre (optionnel)</label>
            <textarea class="form-control" id="quizText" rows="4" placeholder="Collez ici le texte du livre pour générer un quiz personnalisé..."></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label for="quizNum" class="form-label">Nombre de questions</label>
              <select class="form-select" id="quizNum">
                <option value="4">4 questions</option>
                <option value="6">6 questions</option>
                <option value="8">8 questions</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="quizDifficulty" class="form-label">Difficulté</label>
              <select class="form-select" id="quizDifficulty">
                <option value="easy">Facile</option>
                <option value="medium" selected>Moyen</option>
                <option value="hard">Difficile</option>
              </select>
            </div>
          </div>
        </div>
        <div id="quizStepRender" style="display: none;">
          <div id="quizContainer"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="btnBackToInput" style="display: none;">Modifier le texte</button>
        <button type="button" class="btn btn-primary" id="btnGenerateQuiz">Générer le quiz</button>
        <button type="button" class="btn btn-success" id="btnCheckAnswers" style="display: none;">Vérifier</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('extra-css')
<style>
  /* Enhanced Table Styling */
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
</style>
@endsection

@section('extra-js')
<script>
// Participation Modal Functions
function showParticipationModal(participationId) {
  const modal = new bootstrap.Modal(document.getElementById('participationModal'));
  const modalBody = document.getElementById('participationModalBody');
  
  modalBody.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div></div>';
  
  fetch(`/participation-defis/${participationId}/modal-content`)
    .then(response => response.text())
    .then(html => {
      modalBody.innerHTML = html;
      modal.show();
      bindQuizHandlers();
    })
    .catch(error => {
      console.error('Error:', error);
      modalBody.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement des détails</div>';
    });
}

function completeChallenge(participationId) {
  if (confirm('Êtes-vous sûr de vouloir terminer ce défi ?')) {
    fetch(`/participation-defis/${participationId}/update-status`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        status: 'termine'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        location.reload();
      } else {
        alert('Erreur lors de la mise à jour');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Erreur lors de la mise à jour');
    });
  }
}

// Quiz Functions
function bindQuizHandlers() {
  const generateBtn = document.getElementById('btnGenerateQuiz');
  const checkBtn = document.getElementById('btnCheckAnswers');
  const backBtn = document.getElementById('btnBackToInput');
  
  if (generateBtn) {
    generateBtn.addEventListener('click', async function() {
      const text = document.getElementById('quizText').value.trim();
      const num = parseInt(document.getElementById('quizNum').value);
      const difficulty = document.getElementById('quizDifficulty').value;
      
      if (text.length < 50) {
        alert('Veuillez fournir au moins 50 caractères.');
        return;
      }
      
      await generateQuiz(text, num, difficulty);
    });
  }
  
  if (checkBtn) {
    checkBtn.addEventListener('click', checkAnswers);
  }
  
  if (backBtn) {
    backBtn.addEventListener('click', function() {
      toggleQuizSteps('input');
    });
  }
}

async function generateQuiz(text, num, difficulty) {
  try {
    const response = await fetch('/ai/quiz', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({ text, num, difficulty })
    });
    
    const quiz = await response.json();
    renderQuiz(quiz);
  } catch (error) {
    console.error('Error:', error);
    alert('Erreur lors de la génération du quiz');
  }
}

function renderQuiz(quiz) {
  const container = document.getElementById('quizContainer');
  container.innerHTML = '';
  
  quiz.questions.forEach((q, idx) => {
    const block = document.createElement('div');
    block.className = 'mb-4 p-3 border rounded';
    block.innerHTML = `
      <h6>${q.question}</h6>
      ${q.options.map((opt, i) => `
        <div class="form-check">
          <input class="form-check-input" type="radio" name="q_${idx}" value="${opt}" id="q_${idx}_${i}">
          <label class="form-check-label" for="q_${idx}_${i}">${opt}</label>
        </div>
      `).join('')}
      <input type="hidden" data-role="answer_${idx}" value="${q.answer}">
    `;
    container.appendChild(block);
  });
  
  toggleQuizSteps('render');
}

function toggleQuizSteps(step) {
  const input = document.getElementById('quizStepInput');
  const render = document.getElementById('quizStepRender');
  const generateBtn = document.getElementById('btnGenerateQuiz');
  const checkBtn = document.getElementById('btnCheckAnswers');
  const backBtn = document.getElementById('btnBackToInput');
  
  if (step === 'input') {
    input.style.display = 'block';
    render.style.display = 'none';
    generateBtn.style.display = 'inline-block';
    checkBtn.style.display = 'none';
    backBtn.style.display = 'none';
  } else {
    input.style.display = 'none';
    render.style.display = 'block';
    generateBtn.style.display = 'none';
    checkBtn.style.display = 'inline-block';
    backBtn.style.display = 'inline-block';
  }
}

function checkAnswers() {
  const blocks = document.querySelectorAll('#quizContainer > div');
  let score = 0;
  
  blocks.forEach((block, idx) => {
    const correct = block.querySelector(`[data-role="answer_${idx}"]`).value;
    const selected = block.querySelector(`input[name="q_${idx}"]:checked`);
    const user = selected ? selected.value : '';
    
    if (user.toLowerCase() === correct.toLowerCase()) {
      score++;
      block.classList.add('border-success');
    } else {
      block.classList.add('border-danger');
    }
  });
  
  alert(`Votre score: ${score}/${blocks.length}`);
}
</script>
@endsection


