@extends('front.layouts.app')

@section('title', 'Our Books Collection')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>Welcome to BookShare</h6>
                            <h2>Discover Our <em>Book</em> Collection & <span>Reviews</span></h2>
                            <p>Explore our curated selection of amazing books and read reviews from our community. Share your thoughts and discover new favorites!</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="book collection">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="livres" class="our-livres section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading wow bounceIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <h2>Our <em>Book</em> Collection</h2>
                    <p>Discover our curated selection of amazing books</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="livres-grid">
                    <div class="row">
                        @foreach($livres as $livre)
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="livre-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                                <div class="livre-content">
                                    <h4>{{ $livre->title }}</h4>
                                    <p>Explore this amazing book and read community reviews</p>
                                    
                                    <div class="livre-rating">
                                        <span class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($livre->average_rating))
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                        <span class="rating-text">{{ number_format($livre->average_rating, 1) }}/5</span>
                                        <span class="review-count">({{ $livre->total_reviews }} reviews)</span>
                                    </div>
                                    
                                    <div class="livre-actions">
                                        <button class="main-button" onclick="openReviewsModal({{ $livre->id }}, '{{ $livre->title }}')">
                                            View Reviews
                                        </button>
                                        <button class="main-button secondary" onclick="openAddReviewModal({{ $livre->id }}, '{{ $livre->title }}')">
                                            Add Review
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
</div>

<!-- Recommendations Section -->
@auth
<div id="recommendations" class="our-livres section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading wow bounceIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <h2>Your <em>Recommendations</em></h2>
                    <p>Personalized suggestions based on your reviews</p>
                    <div class="text-center mt-2">
                        <button id="generateRecsBtn" class="main-button">Generate Recommendations</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div id="recommendationsList" class="row"></div>
            </div>
        </div>
    </div>
    <div class="container" id="recsEmptyState" style="display:none">
        <div class="alert alert-info text-center">No recommendations yet. Rate books with 4 or 5 stars and click Generate.</div>
    </div>
    <div class="container" id="recsLoading" style="display:none">
        <div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>
    </div>
</div>
@endauth

<!-- Reviews Modal -->
<div class="modal fade" id="reviewsModal" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewsModalLabel">Reviews for <span id="modalBookTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="reviewsList">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Review Modal -->
<div class="modal fade" id="addReviewModal" tabindex="-1" aria-labelledby="addReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReviewModalLabel">Add Review for <span id="addModalBookTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addReviewForm">
                    <input type="hidden" id="reviewLivreId" name="livre_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" id="addStarRating">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="reviewNote" name="note" required>
                        <div class="rating-text" id="ratingText">Click stars to rate</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="reviewCommentaire" class="form-label">Review</label>
                        <textarea class="form-control" id="reviewCommentaire" name="commentaire" rows="4" 
                                  placeholder="Share your thoughts about this book..." required maxlength="1000"></textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel">Edit Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReviewForm">
                    <input type="hidden" id="editReviewId" name="avis_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" id="editStarRating">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="editReviewNote" name="note" required>
                        <div class="rating-text" id="editRatingText">Click stars to rate</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editReviewCommentaire" class="form-label">Review</label>
                        <textarea class="form-control" id="editReviewCommentaire" name="commentaire" rows="4" 
                                  placeholder="Share your thoughts about this book..." required maxlength="1000"></textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
// Global variables
let currentLivreId = null;

// Get CSRF token safely
function getCSRFToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.getAttribute('content') : '';
}

// Show error message
function showError(message) {
    alert('Error: ' + message);
    console.error(message);
}

// Initialize star rating
function initializeStarRating(containerId, hiddenInputId, textElementId) {
    const container = document.getElementById(containerId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const textElement = document.getElementById(textElementId);
    const stars = container.querySelectorAll('.star');
    
    const ratingLabels = {
        1: 'Poor',
        2: 'Fair', 
        3: 'Good',
        4: 'Very Good',
        5: 'Excellent'
    };
    
    stars.forEach((star, index) => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            hiddenInput.value = rating;
            
            // Update visual state
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
            
            // Update text
            textElement.textContent = `${rating} Star${rating > 1 ? 's' : ''} - ${ratingLabels[rating]}`;
        });
        
        star.addEventListener('mouseenter', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            stars.forEach((s, i) => {
                if (i < rating) {
                    s.style.color = '#ff6b35';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
        
        star.addEventListener('mouseleave', () => {
            stars.forEach((s, i) => {
                if (s.classList.contains('active')) {
                    s.style.color = '#ff6b35';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    });
}

// Set star rating value
function setStarRating(containerId, hiddenInputId, textElementId, value) {
    const container = document.getElementById(containerId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const textElement = document.getElementById(textElementId);
    const stars = container.querySelectorAll('.star');
    
    const ratingLabels = {
        1: 'Poor',
        2: 'Fair', 
        3: 'Good',
        4: 'Very Good',
        5: 'Excellent'
    };
    
    if (value) {
        hiddenInput.value = value;
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.add('active');
                star.style.color = '#ff6b35';
            } else {
                star.classList.remove('active');
                star.style.color = '#ddd';
            }
        });
        textElement.textContent = `${value} Star${value > 1 ? 's' : ''} - ${ratingLabels[value]}`;
    }
}

// Reset star rating to default state
function resetStarRating(containerId, hiddenInputId, textElementId) {
    const container = document.getElementById(containerId);
    const hiddenInput = document.getElementById(hiddenInputId);
    const textElement = document.getElementById(textElementId);
    const stars = container.querySelectorAll('.star');
    
    // Clear hidden input
    hiddenInput.value = '';
    
    // Reset all stars to default state
    stars.forEach(star => {
        star.classList.remove('active');
        star.style.color = '#ddd';
    });
    
    // Reset text
    textElement.textContent = 'Click stars to rate';
}

// Open reviews modal
function openReviewsModal(livreId, bookTitle) {
    currentLivreId = livreId;
    document.getElementById('modalBookTitle').textContent = bookTitle;
    
    const modal = new bootstrap.Modal(document.getElementById('reviewsModal'));
    modal.show();
    
    loadReviews(livreId);
}

// Open add review modal
function openAddReviewModal(livreId, bookTitle) {
    currentLivreId = livreId;
    document.getElementById('addModalBookTitle').textContent = bookTitle;
    document.getElementById('reviewLivreId').value = livreId;
    
    // Reset form
    document.getElementById('addReviewForm').reset();
    
    const modal = new bootstrap.Modal(document.getElementById('addReviewModal'));
    modal.show();
    
    // Reset and initialize star rating after modal is shown
    setTimeout(() => {
        resetStarRating('addStarRating', 'reviewNote', 'ratingText');
        initializeStarRating('addStarRating', 'reviewNote', 'ratingText');
    }, 100);
}

// Load reviews for a specific livre
function loadReviews(livreId) {
    const reviewsList = document.getElementById('reviewsList');
    reviewsList.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';
    
    fetch(`/livres/${livreId}/avis`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayReviews(data.data);
            } else {
                reviewsList.innerHTML = '<div class="alert alert-danger">Error loading reviews</div>';
            }
        })
        .catch(error => {
            showError('Failed to load reviews');
            reviewsList.innerHTML = '<div class="alert alert-danger">Error loading reviews</div>';
        });
}

// Display reviews in the modal
function displayReviews(reviews) {
    const reviewsList = document.getElementById('reviewsList');
    
    if (reviews.length === 0) {
        reviewsList.innerHTML = '<div class="alert alert-info">No reviews yet. Be the first to review this book!</div>';
        return;
    }
    
    let html = '';
    reviews.forEach(review => {
        const stars = '★'.repeat(review.note) + '☆'.repeat(5 - review.note);
        const date = new Date(review.date_publication).toLocaleDateString();
        
        html += `
            <div class="review-item mb-3 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">${review.utilisateur.name}</h6>
                        <div class="stars mb-2">${stars} (${review.note}/5)</div>
                        <p class="mb-1">${review.commentaire}</p>
                        <small class="text-muted">${date}</small>
                    </div>
                    <div class="review-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="editReview(${review.id}, ${review.note}, '${review.commentaire.replace(/'/g, "\\'")}')">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteReview(${review.id})">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    reviewsList.innerHTML = html;
}

// Add review form submission
document.getElementById('addReviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    fetch('/avis', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Review added successfully!');
            bootstrap.Modal.getInstance(document.getElementById('addReviewModal')).hide();
            loadReviews(currentLivreId);
        } else {
            alert('Error adding review: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        showError('Failed to add review');
    });
});

// Edit review
function editReview(avisId, note, commentaire) {
    document.getElementById('editReviewId').value = avisId;
    document.getElementById('editReviewCommentaire').value = commentaire;
    
    // Initialize star rating and set the value
    initializeStarRating('editStarRating', 'editReviewNote', 'editRatingText');
    setStarRating('editStarRating', 'editReviewNote', 'editRatingText', note);
    
    const modal = new bootstrap.Modal(document.getElementById('editReviewModal'));
    modal.show();
}

// Edit review form submission
document.getElementById('editReviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const avisId = document.getElementById('editReviewId').value;
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    delete data.avis_id; // Remove the avis_id from data
    
    fetch(`/avis/${avisId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken()
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Review updated successfully!');
            bootstrap.Modal.getInstance(document.getElementById('editReviewModal')).hide();
            loadReviews(currentLivreId);
        } else {
            alert('Error updating review: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        showError('Failed to update review');
    });
});

// Delete review
function deleteReview(avisId) {
    if (confirm('Are you sure you want to delete this review?')) {
        fetch(`/avis/${avisId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': getCSRFToken()
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review deleted successfully!');
                loadReviews(currentLivreId);
            } else {
                alert('Error deleting review: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            showError('Failed to delete review');
        });
    }
}

// ------------------- Recommendations (Front) -------------------
@auth
document.getElementById('generateRecsBtn').addEventListener('click', function() {
    document.getElementById('recsLoading').style.display = 'block';
    fetch('{{ route('recommendations.generate.ajax') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCSRFToken()
        }
    }).then(() => loadRecommendations());
});

function loadRecommendations() {
    document.getElementById('recsLoading').style.display = 'block';
    fetch('{{ route('recommendations.list') }}')
        .then(r => r.json())
        .then(data => {
            document.getElementById('recsLoading').style.display = 'none';
            const list = document.getElementById('recommendationsList');
            list.innerHTML = '';
            if (!data.success || data.data.length === 0) {
                document.getElementById('recsEmptyState').style.display = 'block';
                return;
            }
            document.getElementById('recsEmptyState').style.display = 'none';
            data.data.forEach(rec => {
                const col = document.createElement('div');
                col.className = 'col-lg-4 col-md-6 mb-4';
                const percent = Math.round(rec.score * 100);
                const category = rec.category_name || (rec.livre && rec.livre.categorie ? rec.livre.categorie.nom : '');
                col.innerHTML = `
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-info">${rec.source || 'AI'}</span>
                                <div class="d-flex align-items-center gap-2">
                                    ${category ? `<span class=\"badge bg-secondary\">${category}</span>` : ''}
                                    <small class="text-muted">${percent}% match</small>
                                </div>
                            </div>
                            <h5 class="card-title">${rec.livre?.title || 'Book'}</h5>
                            <p class="card-text small text-muted">${rec.livre?.author || ''}</p>
                            ${category ? `<div class=\"small text-muted mb-2\"><strong>Category:</strong> ${category}</div>` : ''}
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('livres') }}?search=${encodeURIComponent(rec.livre?.title || '')}">View Book</a>
                        </div>
                    </div>`;
                list.appendChild(col);
            });
        })
        .catch(() => {
            document.getElementById('recsLoading').style.display = 'none';
        });
}

// Auto-load recommendations on page load
document.addEventListener('DOMContentLoaded', loadRecommendations);
@endauth
</script>
@endsection
