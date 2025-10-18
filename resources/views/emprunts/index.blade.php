@extends('layouts.contentNavbarLayout')

@section('title', 'Gestion des Emprunts')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- AI Reading Personality Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-brain me-2"></i>Mon Profil de Lecture IA
                    </h5>
                    <div>
                        <button type="button" class="btn btn-outline-primary me-2" onclick="updatePersonality()">
                            <i class="bx bx-refresh"></i> Mettre à jour
                        </button>
                        <button type="button" class="btn btn-primary" onclick="generatePersonality()">
                            <i class="bx bx-magic-wand"></i> Générer mon profil
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="personality-content">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement de votre profil de lecture...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Emprunts</h5>
                    <a href="{{ route('emprunts.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nouvel Emprunt
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Livre</th>
                                    <th>Date Emprunt</th>
                                    <th>Date Retour Prévue</th>
                                    <th>Date Retour Effective</th>
                                    <th>Statut</th>
                                    <th>Pénalité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emprunts as $emprunt)
                                    <tr>
                                        <td>{{ $emprunt->id }}</td>
                                        <td>{{ $emprunt->utilisateur->name ?? 'N/A' }}</td>
                                        <td>{{ $emprunt->livre->title ?? 'N/A' }}</td>
                                        <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                        <td>{{ $emprunt->date_retour_prev->format('d/m/Y') }}</td>
                                        <td>{{ $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('d/m/Y') : 'Non retourné' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $emprunt->statut === 'En cours' ? 'warning' : ($emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                                {{ $emprunt->statut }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($emprunt->penalite, 2) }} €</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('emprunts.show', $emprunt) }}">
                                                        <i class="bx bx-show me-1"></i> Voir
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('emprunts.edit', $emprunt) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')">
                                                            <i class="bx bx-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun emprunt trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $emprunts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Load personality data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadPersonalityData();
});

function loadPersonalityData() {
    fetch('{{ route("reading-personality.data") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayPersonality(data.personality);
            } else {
                displayNoPersonality();
            }
        })
        .catch(error => {
            console.error('Error loading personality:', error);
            displayNoPersonality();
        });
}

function displayPersonality(personality) {
    const content = `
        <div class="row">
            <div class="col-md-8">
                <div class="mb-4">
                    <h4 class="text-primary mb-3">${personality.personality_title}</h4>
                    <p class="text-muted">${personality.personality_description}</p>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary">Genres Préférés</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${personality.reading_patterns.favorite_genres.map(genre => 
                                `<span class="badge bg-primary">${genre}</span>`
                            ).join('')}
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary">Thèmes de Lecture</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${personality.reading_patterns.reading_themes.map(theme => 
                                `<span class="badge bg-info">${theme}</span>`
                            ).join('')}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary">Style de Lecture</h6>
                        <p class="text-muted">${personality.reading_patterns.reading_style}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-primary">Comportement d'Emprunt</h6>
                        <p class="text-muted">${personality.reading_patterns.borrowing_behavior}</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">Suggestions de Lecture</h6>
                        <ul class="list-unstyled">
                            ${personality.recommendations.map(rec => 
                                `<li class="mb-2"><i class="bx bx-book text-primary me-2"></i>${rec}</li>`
                            ).join('')}
                        </ul>
                        
                        <div class="mt-4">
                            <h6 class="text-primary mb-2">Défi Suggéré</h6>
                            <p class="text-muted small">${personality.challenge_suggestion}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('personality-content').innerHTML = content;
}

function displayNoPersonality() {
    const content = `
        <div class="text-center py-4">
            <i class="bx bx-brain text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">Aucun profil de lecture généré</h5>
            <p class="text-muted">Générez votre profil IA basé sur votre historique d'emprunts</p>
            <button type="button" class="btn btn-primary" onclick="generatePersonality()">
                <i class="bx bx-magic-wand"></i> Générer mon profil
            </button>
        </div>
    `;
    
    document.getElementById('personality-content').innerHTML = content;
}

function generatePersonality() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Génération...';
    button.disabled = true;
    
    fetch('{{ route("reading-personality.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayPersonality(data.personality);
        } else {
            alert('Erreur lors de la génération: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la génération du profil');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

function updatePersonality() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Mise à jour...';
    button.disabled = true;
    
    fetch('{{ route("reading-personality.update") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayPersonality(data.personality);
        } else {
            alert('Erreur lors de la mise à jour: ' + (data.message || 'Erreur inconnue'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour du profil');
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
    });
}
</script>
@endsection
