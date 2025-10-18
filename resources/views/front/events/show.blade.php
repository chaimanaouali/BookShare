@extends('front.layouts.app')

@section('content')
<div class="container py-4">
  <div class="row">
    <div class="col-12">
      <div class="mb-3">
        <a href="{{ route('front.events.index') }}" class="text-decoration-none"><i class="bx bx-left-arrow-alt"></i> Retour aux événements</a>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      @if($event->image)
        <img src="/{{ $event->image }}" alt="{{ $event->titre }}" class="img-fluid rounded mb-3" style="width:100%;max-height:420px;object-fit:cover;">
      @endif
      <h2 class="mb-2">{{ $event->titre }}</h2>
      <div class="d-flex align-items-center gap-3 mb-3">
        <span class="badge bg-primary">{{ ucfirst($event->type) }}</span>
        <small class="text-muted"><i class="bx bx-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->date_evenement)->translatedFormat('d M Y') }} • {{ \Carbon\Carbon::parse($event->date_evenement)->diffForHumans() }}</small>
      </div>
      @if($event->description)
        <p class="lead">{{ $event->description }}</p>
      @endif
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Informations</h5>
          <div class="mb-2">
            <small class="text-muted d-block">Date</small>
            <span>{{ \Carbon\Carbon::parse($event->date_evenement)->translatedFormat('l d F Y') }}</span>
          </div>
          <div class="mb-2">
            @php $isPast = \Carbon\Carbon::parse($event->date_evenement)->isPast(); @endphp
            <small class="text-muted d-block">Statut</small>
            <span class="badge {{ $isPast ? 'bg-secondary' : 'bg-success' }}">{{ $isPast ? 'Terminé' : 'À venir' }}</span>
          </div>
          <div class="d-grid mt-3">
            @if($event->defi && $event->defi->livres->count() > 0)
              @auth
                @php
                  $userParticipation = \App\Models\ParticipationDefi::where('user_id', Auth::id())
                    ->where('defi_id', $event->defi->id)
                    ->first();
                @endphp
                
                @if($userParticipation)
                  @if($userParticipation->status === 'termine')
                    <div class="alert alert-success mb-3">
                      <i class="bx bx-trophy me-2"></i>
                      <strong>Félicitations ! Vous avez terminé ce défi</strong>
                      <div class="mt-2">
                        <button class="btn btn-success btn-sm" data-participation-id="{{ $userParticipation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                          <i class="bx bx-eye me-1"></i>Voir ma participation
                        </button>
                      </div>
                    </div>
                  @else
                    <div class="alert alert-info mb-3">
                      <i class="bx bx-check-circle me-2"></i>
                      <strong>Vous participez déjà à ce défi</strong>
                      <div class="mt-2">
                        <button class="btn btn-primary btn-sm" data-participation-id="{{ $userParticipation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                          <i class="bx bx-eye me-1"></i>Voir ma participation
                        </button>
                      </div>
                    </div>
                  @endif
                @else
                  <button class="btn btn-primary btn-enhanced" onclick="toggleParticipationForm()">
                    <i class="bx bx-play me-2"></i>Participer au défi
                  </button>
                @endif
              @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-enhanced">
                  <i class="bx bx-log-in me-2"></i>Se connecter pour participer
                </a>
              @endauth
            @elseif($event->defi)
              <button class="btn btn-secondary" disabled>
                <i class="bx bx-info-circle me-2"></i>Aucun livre disponible
              </button>
            @else
              <button class="btn btn-secondary" disabled>
                <i class="bx bx-info-circle me-2"></i>Pas de défi associé
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Participation Form (Hidden by default) -->
  @if($event->defi && $event->defi->livres->count() > 0 && Auth::check())
    @php
      $userParticipation = \App\Models\ParticipationDefi::where('user_id', Auth::id())
        ->where('defi_id', $event->defi->id)
        ->first();
    @endphp
    
    @if(!$userParticipation || $userParticipation->status !== 'termine')
      <div id="participation-form" class="row mt-4" style="display: none;">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Participer au défi</h5>
            </div>
            <div class="card-body">
              <form id="participation-form-ajax" method="POST">
                @csrf
                
                <!-- Book Selection -->
                <div class="mb-4">
                  <h6 class="fw-bold mb-3">Choisir un livre pour le défi</h6>
                  <p class="text-muted mb-3">Sélectionnez le livre que vous voulez lire *</p>
                  
                  <div class="row g-3">
                    @foreach($event->defi->livres as $livre)
                      <div class="col-md-6">
                        <div class="card border book-card" data-livre-id="{{ $livre->id }}" style="cursor: pointer; transition: all 0.2s ease;">
                          <div class="card-body">
                            <div class="d-flex align-items-start">
                              <div class="me-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                                  <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                  <div class="d-flex align-items-center justify-content-center h-100">
                                    <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                                  </div>
                                @endif
                              </div>
                              <div class="flex-grow-1">
                                <h6 class="card-title fw-bold mb-1">{{ $livre->title }}</h6>
                                <p class="text-muted small mb-1">{{ $livre->author }}</p>
                                <span class="badge bg-primary">{{ strtoupper($livre->format ?: 'PDF') }}</span>
                                <div class="form-check mt-2">
                                  <input class="form-check-input" type="radio" name="livre_id" value="{{ $livre->id }}" id="livre_{{ $livre->id }}" required>
                                  <label class="form-check-label" for="livre_{{ $livre->id }}">
                                    Sélectionner ce livre
                                  </label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <!-- Comment -->
                <div class="mb-4">
                  <label for="commentaire" class="form-label">Commentaire (optionnel)</label>
                  <textarea name="commentaire" id="commentaire" class="form-control" rows="3" placeholder="Pourquoi voulez-vous participer à ce défi ? Que comptez-vous apprendre ?"></textarea>
                </div>

                <!-- How it works -->
                <div class="alert alert-info mb-4">
                  <div class="d-flex align-items-start">
                    <i class="bx bx-info-circle me-2 mt-1"></i>
                    <div>
                      <h6 class="alert-heading mb-2">Comment ça marche ?</h6>
                      <ul class="mb-0 small">
                        <li>Vous choisissez un livre parmi ceux proposés pour ce défi</li>
                        <li>Vous commencez votre lecture et pouvez suivre votre progression</li>
                        <li>Une fois terminé, vous pouvez noter le livre et partager votre avis</li>
                        <li>Vous pouvez abandonner le défi à tout moment</li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-between align-items-center">
                  <small class="text-muted">En participant, vous vous engagez à lire le livre sélectionné</small>
                  <div>
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="toggleParticipationForm()">
                      Annuler
                    </button>
                    <button type="button" class="btn btn-primary" id="submit-participation-btn">
                      <i class="bx bx-play me-2"></i>Commencer le défi
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endif

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
</div>
@endsection

@section('extra-css')
<style>
  .btn-enhanced {
    transition: all 0.2s ease;
    font-weight: 500;
  }
  
  .btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }

  .book-card {
    transition: all 0.2s ease;
  }

  .book-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }

  .book-card.selected {
    border-color: #007bff !important;
    background-color: #f8f9ff;
  }
</style>
@endsection

@section('extra-js')
<script>
// Toggle participation form
function toggleParticipationForm() {
  const form = document.getElementById('participation-form');
  const button = document.getElementById('participate-btn');
  
  if (form.style.display === 'none') {
    form.style.display = 'block';
    if (button) button.style.display = 'none';
  } else {
    form.style.display = 'none';
    if (button) button.style.display = 'block';
  }
}

// AJAX participation submission
document.addEventListener('DOMContentLoaded', function() {
  const submitBtn = document.getElementById('submit-participation-btn');
  const form = document.getElementById('participation-form-ajax');
  
  if (submitBtn && form) {
    submitBtn.addEventListener('click', function() {
      // Validate form
      const selectedBook = form.querySelector('input[name="livre_id"]:checked');
      if (!selectedBook) {
        alert('Veuillez sélectionner un livre pour participer au défi.');
        return;
      }
      
      // Show loading state
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-2"></i>Participation en cours...';
      submitBtn.disabled = true;
      
      // Prepare form data
      const formData = new FormData(form);
      
      // Submit via AJAX
      fetch('{{ route("participation-defis.store", $event->defi) }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'application/json'
        }
      })
      .then(response => {
        if (response.ok) {
          return response.json();
        }
        throw new Error('Erreur lors de la participation');
      })
      .then(data => {
        if (data.success) {
          // Success - show success message and hide form
          showSuccessMessage(data.message);
          
          // Hide participation form and show participation details
          document.getElementById('participation-form').style.display = 'none';
          const participateBtn = document.getElementById('participate-btn');
          if (participateBtn) participateBtn.style.display = 'none';
          
          // Reload the page to show updated participation status
          setTimeout(() => {
            location.reload();
          }, 2000);
        } else {
          throw new Error(data.message || 'Erreur lors de la participation');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la participation au défi. Veuillez réessayer.');
        
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
      });
    });
  }
});

// Show success message
function showSuccessMessage(message) {
  // Create success alert
  const alertDiv = document.createElement('div');
  alertDiv.className = 'alert alert-success alert-dismissible fade show';
  alertDiv.innerHTML = `
    <i class="bx bx-check-circle me-2"></i>${message}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  `;
  
  // Insert at the top of the content
  const content = document.querySelector('.container');
  if (content) {
    content.insertBefore(alertDiv, content.firstChild);
  }
  
  // Auto-dismiss after 5 seconds
  setTimeout(() => {
    if (alertDiv.parentNode) {
      alertDiv.remove();
    }
  }, 5000);
}

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

// Book selection functionality
document.addEventListener('DOMContentLoaded', function() {
  // Add click handlers to all book cards
  document.querySelectorAll('.book-card').forEach(card => {
    card.addEventListener('click', function() {
      const livreId = this.getAttribute('data-livre-id');
      
      // Uncheck all radio buttons
      document.querySelectorAll('input[name="livre_id"]').forEach(radio => {
        radio.checked = false;
      });
      
      // Check the selected one
      const radioButton = document.getElementById('livre_' + livreId);
      if (radioButton) {
        radioButton.checked = true;
      }
      
      // Remove selected class from all cards
      document.querySelectorAll('.book-card').forEach(c => c.classList.remove('selected'));
      
      // Add selected class to clicked card
      this.classList.add('selected');
    });
  });
  
  // Add change handlers to radio buttons
  document.querySelectorAll('input[name="livre_id"]').forEach(radio => {
    radio.addEventListener('change', function() {
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

// Bind handlers inside the loaded participation modal content
function bindParticipationModalHandlers() {
  // Open reader
  document.querySelector('[data-role="open-reader"]')?.addEventListener('click', function() {
    const modal = document.getElementById('bookReaderModal');
    if (modal) new bootstrap.Modal(modal).show();
  });

  // Open quiz and auto-generate 4 questions
  document.addEventListener('click', async function(e) {
    const trigger = e.target.closest('[data-role="open-quiz"]');
    if (!trigger) return;
    const title = trigger.getAttribute('data-title') || '';
    const author = trigger.getAttribute('data-author') || '';
    const description = trigger.getAttribute('data-description') || '';
    const participationId = document.getElementById('participationMeta')?.getAttribute('data-participation-id') || trigger.getAttribute('data-participation-id') || '';
    const qm = document.getElementById('quizModal');
    if (qm) new bootstrap.Modal(qm).show();
    // Try server-side extraction for relevance
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      const res = await fetch(`/ai/quiz/from-participation/${participationId}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', ...(token ? { 'X-CSRF-TOKEN': token } : {}) },
        body: JSON.stringify({ num_questions: 4, difficulty: 'medium' })
      });
      if (res.ok) { const quiz = await res.json(); renderQuiz(quiz); return; }
    } catch (e) { /* fallback below */ }
    let text = `${title} — ${author}. ${description}`.trim();
    if (text.length < 50) text = (text + ' ').repeat(10);
    await generateQuiz(text, 4, 'medium');
  });

  // Manual generate button (if user switches to edit mode)
  document.getElementById('btnGenerateQuiz')?.addEventListener('click', async function() {
    const text = (document.getElementById('quizText')?.value || '').trim();
    const num = parseInt(document.getElementById('quizNum')?.value || '4', 10);
    const difficulty = document.getElementById('quizDifficulty')?.value || '';
    if (text.length < 50) { alert('Veuillez fournir au moins 50 caractères.'); return; }
    await generateQuiz(text, num, difficulty || undefined);
  });

  document.getElementById('btnBackToInput')?.addEventListener('click', function() {
    toggleQuizSteps('input');
  });

  document.getElementById('btnCheckAnswers')?.addEventListener('click', function() {
    checkAnswers();
  });
}

function toggleQuizSteps(step) {
  const input = document.getElementById('quizStepInput');
  const render = document.getElementById('quizStepRender');
  if (!input || !render) return;
  if (step === 'render') { input.classList.add('d-none'); render.classList.remove('d-none'); }
  else { render.classList.add('d-none'); input.classList.remove('d-none'); }
}

async function generateQuiz(text, numQuestions, difficulty) {
  const container = document.getElementById('quizContainer');
  if (container) {
    container.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p class=\"mt-2 mb-0\">Génération du quiz...</p></div>`;
  }
  const token = document.querySelector('meta[name="csrf-token"]')?.content;
  const res = await fetch('/ai/quiz', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', ...(token ? { 'X-CSRF-TOKEN': token } : {}) },
    body: JSON.stringify({ text, num_questions: numQuestions, difficulty })
  });
  if (!res.ok) { const body = await res.text(); alert('Erreur IA: ' + body); return; }
  const quiz = await res.json();
  renderQuiz(quiz);
}

function renderQuiz(quiz) {
  const container = document.getElementById('quizContainer');
  if (!container) return;
  container.innerHTML = '';
  const title = document.createElement('h5');
  title.textContent = quiz.title || 'Quiz';
  container.appendChild(title);
  (quiz.questions || []).forEach((q, idx) => {
    const block = document.createElement('div');
    block.className = 'mb-3 p-3 border rounded';
    const label = document.createElement('div');
    label.className = 'fw-semibold mb-2';
    label.textContent = (idx + 1) + '. ' + (q.question || '');
    block.appendChild(label);
    if (q.type === 'mcq' && Array.isArray(q.choices)) {
      q.choices.forEach((choice, cidx) => {
        const id = `q_${idx}_c_${cidx}`;
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `<input class=\"form-check-input\" type=\"radio\" name=\"q_${idx}\" id=\"${id}\" value=\"${choice}\">`+
                        `<label class=\"form-check-label\" for=\"${id}\">${choice}</label>`;
        block.appendChild(div);
      });
    } else if (q.type === 'true_false') {
      ['true','false'].forEach((val, cidx) => {
        const id = `q_${idx}_tf_${cidx}`;
        const labelTxt = val === 'true' ? 'Vrai' : 'Faux';
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `<input class=\"form-check-input\" type=\"radio\" name=\"q_${idx}\" id=\"${id}\" value=\"${val}\">`+
                        `<label class=\"form-check-label\" for=\"${id}\">${labelTxt}</label>`;
        block.appendChild(div);
      });
    } else {
      const input = document.createElement('input');
      input.className = 'form-control';
      input.type = 'text';
      input.name = `q_${idx}`;
      block.appendChild(input);
    }
    const ans = document.createElement('input');
    ans.type = 'hidden';
    ans.value = (q.answer !== undefined && q.answer !== null) ? String(q.answer) : '';
    ans.setAttribute('data-role', `answer_${idx}`);
    block.appendChild(ans);
    if (q.explanation) {
      const exp = document.createElement('div');
      exp.className = 'small text-muted mt-2';
      exp.textContent = 'Indice: ' + q.explanation;
      block.appendChild(exp);
    }
    container.appendChild(block);
  });
  toggleQuizSteps('render');
}

async function checkAnswers() {
  console.log('checkAnswers called');
  const blocks = document.querySelectorAll('#quizContainer > div');
  console.log('Found blocks:', blocks.length);
  let score = 0;
  blocks.forEach((block, idx) => {
    const correct = block.querySelector(`[data-role="answer_${idx}"]`)?.value || '';
    let user;
    const selected = block.querySelector(`input[name="q_${idx}"]:checked`);
    if (selected) user = selected.value; else {
      const text = block.querySelector(`input[name="q_${idx}"]`);
      user = text ? text.value.trim() : '';
    }
    const isOk = user && correct && user.toString().toLowerCase() === correct.toString().toLowerCase();
    if (isOk) { score++; block.classList.add('border-success'); }
    else { block.classList.add('border-danger'); }
  });
  const total = blocks.length || 0;
  
  // Save score to participation
  const participationId = document.getElementById('participationMeta')?.getAttribute('data-participation-id');
  if (participationId) {
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      const response = await fetch(`/ai/quiz/save-score/${participationId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
          score: score,
          total_questions: total
        })
      });
      
      if (response.ok) {
        const result = await response.json();
        console.log('Score sauvegardé:', result);
        alert(`Votre score: ${score}/${total}\n\nScore sauvegardé dans votre participation !`);
      } else {
        console.error('Erreur lors de la sauvegarde du score');
        alert(`Votre score: ${score}/${total}\n\nErreur lors de la sauvegarde.`);
      }
    } catch (error) {
      console.error('Erreur:', error);
      alert(`Votre score: ${score}/${total}\n\nErreur lors de la sauvegarde.`);
    }
  } else {
    alert(`Votre score: ${score}/${total}`);
  }
}

// Simple direct event binding for quiz buttons
document.addEventListener('click', function(e) {
  if (e.target && e.target.id === 'btnCheckAnswers') {
    console.log('Vérifier button clicked');
    checkAnswers();
  }
  if (e.target && e.target.id === 'btnBackToInput') {
    console.log('Back to input clicked');
    toggleQuizSteps('input');
  }
  if (e.target && e.target.id === 'btnGenerateQuiz') {
    console.log('Generate quiz clicked');
    const text = (document.getElementById('quizText')?.value || '').trim();
    const num = parseInt(document.getElementById('quizNum')?.value || '4', 10);
    const difficulty = document.getElementById('quizDifficulty')?.value || '';
    if (text.length < 50) { alert('Veuillez fournir au moins 50 caractères.'); return; }
    generateQuiz(text, num, difficulty || undefined);
  }
});
</script>
@endsection



