@extends('front.layouts.app')

@section('title', 'Mes Emprunts')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Management  of <em>Emprunts</em>  <span>Books</span></h2>
                            <p>Generate and manage your books borrowing.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="book management">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="emprunts" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>My Emprunts</h2>
                    <p>consult and manage your books borrowing</p>
                </div>
            </div>
        </div>

        <!-- AI Reading Personality Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fa fa-brain me-2"></i>My Ia Reading Personality
                        </h5>
                        <div>
                            <button type="button" class="btn btn-light me-2" onclick="updatePersonality()">
                                <i class="fa fa-refresh"></i> Update
                            </button>
                            <button type="button" class="btn btn-warning" onclick="generatePersonality()">
                                <i class="fa fa-magic"></i> profile management
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="personality-content">
                            <div class="text-center">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">loading...</span>
                                </div>
                                <p class="mt-2">loading your reading profile...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">Liste of Emprunts</h5>
                        <a href="{{ route('emprunts.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fa fa-plus"></i> new Emprunt
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
                                        <th>Book</th>
                                        <th>Date Emprunt</th>
                                        <th>Date Return Expected</th>
                                        <th>Date Return Actual</th>
                                        <th>Statut</th>
                                        <th>Penalty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emprunts as $emprunt)
                                        <tr>
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
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('emprunts.show', $emprunt) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                    <a href="{{ route('emprunts.edit', $emprunt) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i> update
                                                    </a>
                                                    <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">no Emprunt found</td>
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
                                `<li class="mb-2"><i class="fa fa-book text-primary me-2"></i>${rec}</li>`
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
            <i class="fa fa-brain fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Aucun profil de lecture généré</h5>
            <p class="text-muted">Générez votre profil IA basé sur votre historique d'emprunts</p>
            <button type="button" class="btn btn-primary" onclick="generatePersonality()">
                <i class="fa fa-magic"></i> generate my profile
            </button>
        </div>
    `;
    
    document.getElementById('personality-content').innerHTML = content;
}

function generatePersonality() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Génération...';
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
    
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mise à jour...';
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
