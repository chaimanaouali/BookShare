@extends('layouts/contentNavbarLayout')

@section('title', 'Mon Profil de Lecture')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apex-charts.js')}}"></script>
@endsection

@section('page-script')
<script>
// Reading Personality Management
let personalityData = null;
let isLoading = false;

document.addEventListener('DOMContentLoaded', function() {
    loadPersonalityData();
    
    // Generate button event
    document.getElementById('generatePersonalityBtn').addEventListener('click', generatePersonality);
    
    // Update button event
    document.getElementById('updatePersonalityBtn').addEventListener('click', updatePersonality);
});

function loadPersonalityData() {
    fetch('{{ route("reading-personality.data") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                personalityData = data.personality;
                updateUI(data);
            } else {
                console.error('Error loading personality data:', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Erreur lors du chargement des données');
        });
}

function updateUI(data) {
    const hasEnoughHistory = data.has_enough_history;
    const personality = data.personality;
    const needsUpdate = data.needs_update;
    
    // Show/hide sections based on data
    document.getElementById('noHistorySection').style.display = hasEnoughHistory ? 'none' : 'block';
    document.getElementById('personalitySection').style.display = personality ? 'block' : 'none';
    document.getElementById('generateSection').style.display = !personality && hasEnoughHistory ? 'block' : 'none';
    
    if (personality) {
        displayPersonality(personality);
        
        // Show update button if needed
        document.getElementById('updatePersonalityBtn').style.display = needsUpdate ? 'block' : 'none';
    }
}

function displayPersonality(personality) {
    // Update personality title and description
    document.getElementById('personalityTitle').textContent = personality.personality_title;
    document.getElementById('personalityDescription').textContent = personality.personality_description;
    
    // Update reading patterns
    const patterns = personality.reading_patterns;
    if (patterns) {
        document.getElementById('favoriteGenres').innerHTML = patterns.favorite_genres ? 
            patterns.favorite_genres.map(genre => `<span class="badge bg-primary me-1">${genre}</span>`).join('') : 
            '<span class="text-muted">Aucun genre préféré identifié</span>';
            
        document.getElementById('readingThemes').innerHTML = patterns.reading_themes ? 
            patterns.reading_themes.map(theme => `<span class="badge bg-info me-1">${theme}</span>`).join('') : 
            '<span class="text-muted">Aucun thème identifié</span>';
            
        document.getElementById('readingStyle').textContent = patterns.reading_style || 'Non spécifié';
        document.getElementById('borrowingBehavior').textContent = patterns.borrowing_behavior || 'Non spécifié';
    }
    
    // Update recommendations
    const recommendations = personality.recommendations;
    if (recommendations && recommendations.length > 0) {
        const recommendationsList = document.getElementById('recommendationsList');
        recommendationsList.innerHTML = recommendations.map(rec => 
            `<li class="list-group-item d-flex align-items-center">
                <i class="bx bx-book me-2"></i>
                ${rec}
            </li>`
        ).join('');
    }
    
    // Update challenge suggestion
    document.getElementById('challengeSuggestion').textContent = personality.challenge_suggestion || 'Aucun défi suggéré';
    
    // Update metadata
    document.getElementById('booksAnalyzed').textContent = personality.books_analyzed || 0;
    document.getElementById('lastUpdated').textContent = personality.last_updated ? 
        new Date(personality.last_updated).toLocaleDateString('fr-FR') : 'Jamais';
}

function generatePersonality() {
    if (isLoading) return;
    
    isLoading = true;
    showLoading('Génération de votre profil de lecture...');
    
    fetch('{{ route("reading-personality.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        isLoading = false;
        hideLoading();
        
        if (data.success) {
            showSuccess(data.message);
            personalityData = data.personality;
            updateUI({
                has_enough_history: true,
                personality: data.personality,
                needs_update: false
            });
        } else {
            showError(data.error);
        }
    })
    .catch(error => {
        isLoading = false;
        hideLoading();
        console.error('Error:', error);
        showError('Erreur lors de la génération du profil');
    });
}

function updatePersonality() {
    if (isLoading) return;
    
    isLoading = true;
    showLoading('Mise à jour de votre profil de lecture...');
    
    fetch('{{ route("reading-personality.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        isLoading = false;
        hideLoading();
        
        if (data.success) {
            showSuccess(data.message);
            personalityData = data.personality;
            updateUI({
                has_enough_history: true,
                personality: data.personality,
                needs_update: false
            });
        } else {
            showError(data.error);
        }
    })
    .catch(error => {
        isLoading = false;
        hideLoading();
        console.error('Error:', error);
        showError('Erreur lors de la mise à jour du profil');
    });
}

function showLoading(message) {
    const loadingDiv = document.getElementById('loadingDiv');
    loadingDiv.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">${message}</p>
        </div>
    `;
    loadingDiv.style.display = 'block';
}

function hideLoading() {
    document.getElementById('loadingDiv').style.display = 'none';
}

function showSuccess(message) {
    // You can implement a toast notification here
    alert('Succès: ' + message);
}

function showError(message) {
    // You can implement a toast notification here
    alert('Erreur: ' + message);
}
</script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-brain me-2"></i>Mon Profil de Lecture IA
                    </h4>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="updatePersonalityBtn" style="display: none;">
                            <i class="bx bx-refresh me-1"></i>Mettre à jour
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" id="generatePersonalityBtn" style="display: none;">
                            <i class="bx bx-magic-wand me-1"></i>Générer mon profil
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Loading Section -->
                    <div id="loadingDiv" style="display: none;"></div>
                    
                    <!-- No History Section -->
                    <div id="noHistorySection" style="display: none;">
                        <div class="text-center py-5">
                            <i class="bx bx-book-open display-1 text-muted"></i>
                            <h4 class="mt-3">Pas assez d'historique</h4>
                            <p class="text-muted">Vous devez avoir emprunté au moins 3 livres pour générer votre profil de lecture.</p>
                            <a href="{{ route('livres') }}" class="btn btn-primary">
                                <i class="bx bx-book me-1"></i>Explorer les livres
                            </a>
                        </div>
                    </div>
                    
                    <!-- Generate Section -->
                    <div id="generateSection" style="display: none;">
                        <div class="text-center py-5">
                            <i class="bx bx-brain display-1 text-primary"></i>
                            <h4 class="mt-3">Découvrez votre profil de lecture</h4>
                            <p class="text-muted">L'IA va analyser votre historique d'emprunts pour créer un profil personnalisé de votre personnalité de lecteur.</p>
                            <button type="button" class="btn btn-primary btn-lg" onclick="generatePersonality()">
                                <i class="bx bx-magic-wand me-2"></i>Générer mon profil IA
                            </button>
                        </div>
                    </div>
                    
                    <!-- Personality Section -->
                    <div id="personalitySection" style="display: none;">
                        <div class="row">
                            <!-- Main Personality Card -->
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bx bx-user-circle me-2"></i>Votre Personnalité de Lecteur
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h3 id="personalityTitle" class="text-primary mb-3"></h3>
                                        <p id="personalityDescription" class="lead"></p>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-tag me-1"></i>Genres Préférés</h6>
                                                <div id="favoriteGenres" class="mb-3"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-palette me-1"></i>Thèmes de Lecture</h6>
                                                <div id="readingThemes" class="mb-3"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-timer me-1"></i>Style de Lecture</h6>
                                                <p id="readingStyle" class="text-muted"></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-trending-up me-1"></i>Comportement d'Emprunt</h6>
                                                <p id="borrowingBehavior" class="text-muted"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recommendations & Challenge -->
                            <div class="col-lg-4">
                                <!-- Recommendations -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-star me-1"></i>Suggestions de Lecture
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul id="recommendationsList" class="list-group list-group-flush">
                                            <!-- Recommendations will be populated here -->
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Challenge -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-target-lock me-1"></i>Défi Suggéré
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p id="challengeSuggestion" class="text-muted"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Metadata -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Livres Analysés</h6>
                                                <h4 id="booksAnalyzed" class="text-primary">0</h4>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Dernière Mise à Jour</h6>
                                                <h6 id="lastUpdated" class="text-muted">Jamais</h6>
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="text-muted">Statut</h6>
                                                <span class="badge bg-success">Profil Généré</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
