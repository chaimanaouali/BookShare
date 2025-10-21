@extends('layouts.front')

@section('title', 'Book Details - ' . ($livre->title ?? 'Untitled'))

@section('content')
<div class="container py-4" style="margin-top:100px;">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-primary mb-3">{{ $livre->title ?? 'Untitled' }}</h2>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong><i class="bx bx-user me-1"></i> Author:</strong> {{ $livre->author ?? 'Unknown' }}</p>
                            <p><strong><i class="bx bx-book me-1"></i> ISBN:</strong> {{ $livre->isbn ?? '-' }}</p>
                            <p><strong><i class="bx bx-calendar me-1"></i> Publication Date:</strong> {{ $livre->publication_date ? $livre->publication_date->format('Y-m-d') : '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong><i class="bx bx-tag me-1"></i> Genre:</strong> {{ $livre->genre ?? '-' }}</p>
                            <p><strong><i class="bx bx-globe me-1"></i> Language:</strong> {{ $livre->langue ?? '-' }}</p>
                            <p><strong><i class="bx bx-file me-1"></i> Format:</strong> {{ strtoupper($livre->format ?? '-') }}</p>
                        </div>
                    </div>

                    @if($livre->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="text-muted">{{ $livre->description }}</p>
                        </div>
                    @endif

                    @if($livre->resume)
                        <div class="mb-4">
                            <h5>Summary</h5>
                            <p class="text-muted">{{ $livre->resume }}</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <h5>Book Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Pages:</strong> {{ $livre->nb_pages ?? '-' }}</p>
                                <p><strong>File Size:</strong> {{ $livre->taille ? number_format($livre->taille / 1024, 2) . ' KB' : '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Visibility:</strong> 
                                    <span class="badge bg-{{ $livre->visibilite == 'public' ? 'success' : 'warning' }}">
                                        {{ ucfirst($livre->visibilite) }}
                                    </span>
                                </p>
                                <p><strong>Availability:</strong> 
                                    <span class="badge bg-{{ $livre->disponibilite ? 'success' : 'danger' }}">
                                        {{ $livre->disponibilite ? 'Available' : 'Not Available' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($livre->bibliotheque)
                        <div class="mb-4">
                            <h5>Library Information</h5>
                            <p><strong>Library:</strong> {{ $livre->bibliotheque->nom_bibliotheque }}</p>
                            <p><strong>Owner:</strong> {{ $livre->bibliotheque->user->name ?? 'Unknown' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    @if($livre->cover_image)
                        <img src="{{ Storage::url($livre->cover_image) }}" alt="Book Cover" class="img-fluid mb-3" style="max-height: 300px;">
                    @else
                        <div class="bg-light p-5 mb-3 rounded">
                            <i class="bx bx-book display-4 text-muted"></i>
                            <p class="text-muted mt-2">No cover image</p>
                        </div>
                    @endif

                    <!-- Emprunt Section -->
                    <div class="mt-4">
                        @php
                            $user = auth()->user();
                            $empruntActif = null;
                            if ($user) {
                                $empruntActif = \App\Models\Emprunt::where('utilisateur_id', $user->id)
                                    ->where('livre_id', $livre->id)
                                    ->where('statut', 'emprunté')
                                    ->first();
                            }
                        @endphp

                        @auth
                            @if($empruntActif)
                                <!-- User has borrowed this book -->
                                <div class="alert alert-success">
                                    <i class="bx bx-check-circle me-1"></i>
                                    <strong>You have borrowed this book!</strong>
                                    <p class="mb-2">Borrowed on: {{ $empruntActif->date_emprunt->format('Y-m-d') }}</p>
                                    <p class="mb-2">Return by: {{ $empruntActif->date_retour_prev->format('Y-m-d') }}</p>
                                </div>

                                @if($livre->fichier_livre)
                                    <a href="{{ Storage::url($livre->fichier_livre) }}" class="btn btn-success btn-lg w-100 mb-2" download>
                                        <i class="bx bx-download me-1"></i> Download & Read
                                    </a>
                                @endif

                                <form action="{{ route('emprunts.retourner', $empruntActif) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('Are you sure you want to return this book?')">
                                        <i class="bx bx-undo me-1"></i> Return Book
                                    </button>
                                </form>
                            @elseif($livre->disponibilite)
                                <!-- Book is available for borrowing -->
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle me-1"></i>
                                    <strong>This book is available for borrowing</strong>
                                    <p class="mb-0">You need to borrow this book before you can read it.</p>
                                </div>

                                <form action="{{ route('livres.emprunter', $livre) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg w-100">
                                        <i class="bx bx-book-add me-1"></i> Borrow This Book
                                    </button>
                                </form>
                                <small class="text-muted mt-2 d-block">Borrowing period: 14 days</small>
                            @else
                                <!-- Book is not available -->
                                <div class="alert alert-danger">
                                    <i class="bx bx-x-circle me-1"></i>
                                    <strong>This book is not available for borrowing</strong>
                                </div>
                            @endif
                        @else
                            <!-- User not logged in -->
                            <div class="alert alert-warning">
                                <i class="bx bx-lock me-1"></i>
                                <strong>Login required</strong>
                                <p class="mb-2">You need to be logged in to borrow and read books.</p>
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="bx bx-log-in me-1"></i> Login
                                </a>
                            </div>
                        @endauth
                    </div>

                    <!-- Reviews Section -->
                    <div class="mt-4">
                        <h6>Reviews</h6>
                        @if($livre->avis && $livre->avis->count() > 0)
                            <p class="text-muted">{{ $livre->avis->count() }} review(s)</p>
                            <a href="#" class="btn btn-outline-secondary btn-sm" onclick="openReviewsModal({{ $livre->id }}, '{{ $livre->title }}')">
                                <i class="bx bx-star me-1"></i> View Reviews
                            </a>
                        @else
                            <p class="text-muted">No reviews yet</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewsModalLabel">Reviews</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewsContent">
                <!-- Reviews will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
function openReviewsModal(livreId, bookTitle) {
    document.getElementById('reviewsModalLabel').textContent = `Reviews for ${bookTitle}`;
    document.getElementById('reviewsContent').innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('reviewsModal'));
    modal.show();
    
    // Load reviews via AJAX
    fetch(`/api/livres/${livreId}/avis`)
        .then(response => response.json())
        .then(data => {
            let reviewsHtml = '';
            if (data.length > 0) {
                data.forEach(review => {
                    reviewsHtml += `
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1">${review.user ? review.user.name : 'Anonymous'}</h6>
                                <small class="text-muted">${new Date(review.created_at).toLocaleDateString()}</small>
                            </div>
                            <div class="mb-2">
                                ${'★'.repeat(review.note)}${'☆'.repeat(5-review.note)}
                            </div>
                            <p class="mb-0">${review.commentaire || 'No comment'}</p>
                        </div>
                    `;
                });
            } else {
                reviewsHtml = '<p class="text-muted text-center">No reviews yet.</p>';
            }
            document.getElementById('reviewsContent').innerHTML = reviewsHtml;
        })
        .catch(error => {
            document.getElementById('reviewsContent').innerHTML = '<p class="text-danger text-center">Error loading reviews.</p>';
        });
}
</script>
@endsection
