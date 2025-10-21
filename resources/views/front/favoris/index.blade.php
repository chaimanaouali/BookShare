@extends('front.layouts.app')

@section('title', 'Mes Favoris - BookShare')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6 style="color: #FF3B30; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">MES FAVORIS</h6>
                            <h2 style="font-size: 3rem; font-weight: 700; line-height: 1.2; margin: 20px 0;">
                                Mes <span style="color: #007bff;">Livres</span> <span style="color: #FF3B30;">Préférés</span>
                            </h2>
                            <p style="font-size: 1.2rem; color: #6c757d; margin-bottom: 30px; line-height: 1.6;">
                                Retrouvez tous vos livres favoris en un seul endroit. Gérez facilement votre collection personnelle.
                            </p>
                            <div class="d-flex gap-3">
                                <button onclick="refreshFavorites()" class="btn btn-lg px-4 py-3" 
                                        style="background-color: #FF3B30; border: 1px solid #FF3B30; color: white; border-radius: 8px; font-weight: 600; font-size: 1.1rem;">
                                    <i class="bx bx-refresh me-2"></i>Actualiser
                                </button>
                                <a href="{{ route('livres') }}" class="btn btn-lg px-4 py-3" 
                                   style="background-color: #007bff; border: 1px solid #007bff; color: white; border-radius: 8px; font-weight: 600; font-size: 1.1rem;">
                                    <i class="bx bx-search me-2"></i>Découvrir des livres
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <div class="text-center">
                                <i class="bx bx-heart" style="font-size: 8rem; color: #FF3B30; opacity: 0.3;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="favoris" class="our-livres section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Favorites Grid -->
                @if($favoris->count() > 0)
                    <div class="livres-grid">
                        <div class="row" id="favorites-grid">
                            @foreach($favoris as $livre)
                                <div class="col-lg-3 col-md-6 col-sm-6 mb-4" id="book-{{ $livre->id }}">
                                    <div class="livre-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                                        <div class="livre-content">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h4 class="mb-0">{{ $livre->title }}</h4>
                                                <x-favorite-button-inline :livre="$livre" :size="'sm'" />
                                            </div>
                                            
                                            <p class="text-muted mb-2">
                                                <i class="bx bx-user me-1"></i>{{ $livre->author }}
                                            </p>
                                            
                                            @if($livre->categorie)
                                                <p class="mb-2">
                                                    <span class="badge bg-primary">{{ $livre->categorie->nom }}</span>
                                                </p>
                                            @endif
                                            
                                            <div class="livre-rating mb-3">
                                                <span class="stars">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= round($livre->avis->avg('note') ?? 0))
                                                            ★
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                </span>
                                                <span class="rating-text">{{ number_format($livre->avis->avg('note') ?? 0, 1) }}/5</span>
                                                <span class="review-count">({{ $livre->avis->count() }} avis)</span>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <small class="text-muted">
                                                    <i class="bx bx-heart me-1"></i>{{ $livre->favoris_count }} favoris
                                                </small>
                                                <small class="text-muted">
                                                    <i class="bx bx-message me-1"></i>{{ $livre->avis->count() }} avis
                                                </small>
                                            </div>
                                            
                                            <div class="livre-actions">
                                                <a href="{{ route('contributor.livres.show', $livre->id) }}" 
                                                   class="main-button w-100 mb-2">
                                                    <i class="bx bx-show me-1"></i>Voir le livre
                                                </a>
                                                <button class="main-button secondary w-100" 
                                                        onclick="removeFromFavorites({{ $livre->id }})"
                                                        title="Retirer des favoris">
                                                    <i class="bx bx-heart-broken me-1"></i>Retirer des favoris
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $favoris->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bx bx-heart text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-3">Aucun favori pour le moment</h5>
                        <p class="text-muted mb-4">Commencez à ajouter des livres à vos favoris en cliquant sur l'icône cœur.</p>
                        <a href="{{ route('livres') }}" class="btn btn-primary">
                            <i class="bx bx-search me-1"></i>Découvrir des livres
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-none" 
     style="background: rgba(0,0,0,0.5); z-index: 9999;">
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden">Chargement...</span>
        </div>
    </div>
</div>

<script>
// Toggle favorite status
function toggleFavorite(bookId) {
    showLoading();
    
    fetch(`/favoris/toggle/${bookId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            // Remove the book card from the grid
            const bookCard = document.getElementById(`book-${bookId}`);
            if (bookCard) {
                bookCard.remove();
            }
            
            // Show success message
            showAlert(data.message, 'success');
            
            // Check if no more favorites
            const favoritesGrid = document.getElementById('favorites-grid');
            if (favoritesGrid && favoritesGrid.children.length === 0) {
                location.reload(); // Reload to show empty state
            }
        } else {
            showAlert(data.error || 'Une erreur est survenue', 'danger');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showAlert('Une erreur est survenue', 'danger');
    });
}

// Remove from favorites
function removeFromFavorites(bookId) {
    if (confirm('Êtes-vous sûr de vouloir retirer ce livre de vos favoris ?')) {
        showLoading();
        
        fetch(`/favoris/${bookId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                // Remove the book card from the grid
                const bookCard = document.getElementById(`book-${bookId}`);
                if (bookCard) {
                    bookCard.remove();
                }
                
                // Show success message
                showAlert(data.message, 'success');
                
                // Check if no more favorites
                const favoritesGrid = document.getElementById('favorites-grid');
                if (favoritesGrid && favoritesGrid.children.length === 0) {
                    location.reload(); // Reload to show empty state
                }
            } else {
                showAlert(data.error || 'Une erreur est survenue', 'danger');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showAlert('Une erreur est survenue', 'danger');
        });
    }
}

// Refresh favorites
function refreshFavorites() {
    location.reload();
}

// Show loading overlay
function showLoading() {
    document.getElementById('loading-overlay').classList.remove('d-none');
}

// Hide loading overlay
function hideLoading() {
    document.getElementById('loading-overlay').classList.add('d-none');
}

// Show alert message
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="bx bx-${type === 'success' ? 'check-circle' : 'error-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the content
    const content = document.querySelector('.container');
    content.insertBefore(alertDiv, content.firstChild);
    
    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>

<style>
.livre-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    overflow: hidden;
    height: 100%;
}

.livre-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.livre-content {
    padding: 20px;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.livre-rating {
    margin: 10px 0;
}

.stars {
    color: #ffc107;
    font-size: 1.2rem;
    margin-right: 8px;
}

.rating-text, .review-count {
    font-size: 0.9rem;
    color: #6c757d;
    margin-left: 5px;
}

.livre-actions {
    margin-top: auto;
}

.main-button {
    background-color: #FF3B30;
    border: 1px solid #FF3B30;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    transition: all 0.2s ease;
    text-align: center;
}

.main-button:hover {
    background-color: #e03428;
    border-color: #e03428;
    color: white;
    transform: translateY(-1px);
}

.main-button.secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.main-button.secondary:hover {
    background-color: #5a6268;
    border-color: #5a6268;
    color: white;
}

@media (max-width: 768px) {
    .col-lg-3 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection
