<!-- Participation metadata for JS -->
<div id="participationMeta" data-participation-id="{{ $participation->id }}" class="d-none"></div>

<!-- Success Message for Completed Challenge -->
@if($participation->status === 'termine')
  <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center">
      <i class="bx bx-trophy me-3" style="font-size: 2rem; color: #28a745;"></i>
      <div>
        <h5 class="alert-heading mb-1">🎉 Félicitations !</h5>
        <p class="mb-2">Vous avez terminé le défi "{{ $participation->defi->titre }}" avec succès !</p>
        <div class="d-flex gap-2">
          <button class="btn btn-success btn-sm" data-redirect-url="{{ route('front.events.index') }}" data-role="go-events">
            <i class="bx bx-home me-1"></i>Voir les événements
          </button>
          <button class="btn btn-outline-success btn-sm" data-redirect-url="{{ route('participation-defis.my-participations') }}" data-role="go-participations">
            <i class="bx bx-list-ul me-1"></i>Mes participations
          </button>
        </div>
      </div>
    </div>
  </div>
@endif

<!-- Défi Info -->
<div class="row mb-4">
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

<!-- Livre à lire -->
<div class="card mb-4">
  <div class="card-header">
    <h5 class="mb-0 fw-semibold text-dark">Livre à lire</h5>
  </div>
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-md-3">
        <div class="text-center">
          <div class="image-placeholder mx-auto mb-3" style="width: 100px; height: 120px;">
            @if($participation->livre->cover_image && file_exists(public_path($participation->livre->cover_image)))
              <img src="/{{ $participation->livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
            @else
              <div class="d-flex align-items-center justify-content-center h-100">
                <i class="bx bx-book text-white" style="font-size: 2.5rem;"></i>
              </div>
            @endif
          </div>
          <h6 class="fw-bold text-dark mb-1">{{ $participation->livre->title }}</h6>
          <p class="text-muted small">{{ $participation->livre->author }}</p>
        </div>
      </div>
      <div class="col-md-9">
        <div class="row">
          <div class="col-md-6">
            <small class="text-muted d-block">Auteur</small>
            <span class="fw-medium">{{ $participation->livre->author ?: 'Non spécifié' }}</span>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block">Description</small>
            <span class="fw-medium">{{ $participation->livre->user_description ?: 'Aucune description' }}</span>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-6">
            <small class="text-muted d-block">Format</small>
            <span class="badge bg-primary">{{ strtoupper($participation->livre->format ?: 'PDF') }}</span>
          </div>
          <div class="col-md-6">
            <small class="text-muted d-block">ISBN</small>
            <span class="fw-medium">{{ $participation->livre->isbn ?: 'Non spécifié' }}</span>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-md-6">
            <small class="text-muted d-block">Taille</small>
            <span class="fw-medium">{{ $participation->livre->taille ?: 'Non spécifiée' }}</span>
          </div>
          @if($participation->quiz_score !== null)
          <div class="col-md-6">
            <small class="text-muted d-block">Score du Quiz</small>
            <span class="badge bg-info">
              {{ $participation->quiz_score }}/{{ $participation->quiz_total_questions }}
              @if($participation->quiz_completed_at)
                ({{ \Carbon\Carbon::parse($participation->quiz_completed_at)->translatedFormat('d M Y à H:i') }})
              @endif
            </span>
          </div>
          @endif
        </div>
        
        @if($participation->livre->fichier_livre)
          <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-3">
            <a href="/storage/{{ $participation->livre->fichier_livre }}" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;" target="_blank">
              <i class="bx bx-download me-2"></i>Télécharger le livre
            </a>
            <button class="btn btn-success btn-enhanced px-4 py-2" style="border-radius: 8px;" data-role="open-reader" data-file-url="{{ $participation->livre->fichier_livre ? "/storage/" . $participation->livre->fichier_livre : '' }}">
              <i class="bx bx-book-open me-2"></i>Lire en ligne
            </button>
            <button class="btn btn-outline-primary btn-enhanced px-4 py-2" style="border-radius: 8px;" data-role="open-quiz"
                    data-title="{{ $participation->livre->title }}"
                    data-author="{{ $participation->livre->author }}"
                    data-description="{{ preg_replace('/[\r\n]+/',' ', (string)($participation->livre->user_description ?? '')) }}"
                    data-default-text="{{ \Illuminate\Support\Str::limit(preg_replace('/[\r\n]+/',' ', (string)($participation->livre->user_description ?? '')), 800) }}">
              <i class="bx bx-edit-alt me-2"></i>Passer le quiz
            </button>
          </div>
        @else
          <div class="alert alert-warning mt-3">
            <i class="bx bx-info-circle me-2"></i>
            Aucun fichier de livre disponible pour le moment.
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Progression et notes -->
<div class="card">
  <div class="card-header">
    <h5 class="mb-0 fw-semibold text-dark">Progression et notes</h5>
  </div>
  <div class="card-body">
    <form id="participation-form" action="{{ route('participation-defis.update-status', $participation) }}" method="POST">
      @csrf
      @method('PUT')
      
      <div class="row mb-3">
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
            <button type="button" class="btn btn-success btn-enhanced px-4 py-2" style="border-radius: 8px;" data-role="complete-defi">
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

<!-- Book Reader Modal -->
<div class="modal fade" id="bookReaderModal" tabindex="-1" aria-labelledby="bookReaderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bookReaderModalLabel">{{ $participation->livre->title }}</h5>
        <div class="d-flex gap-2">
          <a href="/storage/{{ $participation->livre->fichier_livre }}" class="btn btn-sm btn-outline-secondary" target="_blank">
            <i class="bx bx-download me-1"></i>Télécharger
          </a>
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

<!-- Quiz Modal -->
<div class="modal fade" id="quizModal" tabindex="-1" aria-labelledby="quizModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="quizModalLabel">Quiz du livre</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="quizStepInput" class="d-none">
          <p class="text-muted small mb-2">Collez un extrait du livre (ou utilisez la description préremplie) puis générez un quiz.</p>
          <div class="mb-3">
            <label class="form-label">Texte source</label>
            <textarea id="quizText" class="form-control" rows="6"></textarea>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label">Nombre de questions</label>
              <input type="number" id="quizNum" class="form-control" min="3" max="20" value="6">
            </div>
            <div class="col-md-6">
              <label class="form-label">Difficulté</label>
              <select id="quizDifficulty" class="form-select">
                <option value="">Auto</option>
                <option value="easy">Facile</option>
                <option value="medium" selected>Moyenne</option>
                <option value="hard">Difficile</option>
              </select>
            </div>
          </div>
          <div class="d-flex justify-content-end">
            <button class="btn btn-primary" id="btnGenerateQuiz">
              <span class="me-2 bx bx-bulb"></span>Générer le quiz
            </button>
          </div>
        </div>
        <div id="quizStepRender">
          <div id="quizContainer">
            <div class="text-center py-4">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
              </div>
              <p class="mt-2 mb-0">Génération du quiz...</p>
            </div>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-outline-secondary" id="btnBackToInput">Modifier le texte</button>
            <button class="btn btn-success" id="btnCheckAnswers"><i class="bx bx-check-double me-1"></i>Vérifier</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .image-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    overflow: hidden;
  }

  .btn-enhanced {
    transition: all 0.2s ease;
    font-weight: 500;
  }

  .btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
</style>

<script>
// Open embedded reader
document.querySelector('[data-role="open-reader"]')?.addEventListener('click', function() {
  const url = this.getAttribute('data-file-url');
  if (!url) return;
  const modal = new bootstrap.Modal(document.getElementById('bookReaderModal'));
  modal.show();
});

// Open quiz modal and prefill
// Quiz button click handler
document.addEventListener('click', async function(e) {
  const btn = e.target.closest('[data-role="open-quiz"]');
  if (!btn) return;
  
  const title = btn.getAttribute('data-title') || '';
  const author = btn.getAttribute('data-author') || '';
  const description = btn.getAttribute('data-description') || '';
  const participationId = document.getElementById('participationMeta')?.getAttribute('data-participation-id');
  
  // Show quiz modal
  const quizModal = document.getElementById('quizModal');
  if (!quizModal) {
    console.error('Quiz modal not found!');
    return;
  }
  const modal = new bootstrap.Modal(quizModal);
  modal.show();
  
  // Generate quiz
  try {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const res = await fetch(`/ai/quiz/from-participation/${participationId}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
      body: JSON.stringify({ num_questions: 4, difficulty: 'medium' })
    });
    if (res.ok) { 
      const quiz = await res.json(); 
      renderQuiz(quiz); 
      return; 
    }
  } catch(err) {
    console.error('Quiz generation error:', err);
  }
  
  // Fallback to client text
  let text = `${title} — ${author}. ${description}`.trim();
  if (text.length < 50) text = (text + ' ').repeat(10);
  await generateQuiz(text, 4, 'medium');
});

// Generate quiz
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
    container.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p class="mt-2 mb-0">Génération du quiz...</p></div>`;
  }
  const token = document.querySelector('meta[name="csrf-token"]')?.content;
  const res = await fetch('/ai/quiz', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      ...(token ? { 'X-CSRF-TOKEN': token } : {})
    },
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
        div.innerHTML = `<input class="form-check-input" type="radio" name="q_${idx}" id="${id}" value="${choice}">`+
                        `<label class="form-check-label" for="${id}">${choice}</label>`;
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
      input.setAttribute('type', 'text');
      input.setAttribute('name', `q_${idx}`);
      block.appendChild(input);
    }

    // Store answer for checking
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
</script>
