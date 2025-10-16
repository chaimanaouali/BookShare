@extends('layouts/contentNavbarLayout')

@section('title', 'Recommendations')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Your Book Recommendations</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('recommendations.generate.get') }}" class="btn btn-primary">
                            <i class="bx bx-refresh me-1"></i> Generate New Recommendations
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Filter by Source
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('recommendations.index') }}">All Sources</a></li>
                                <li><a class="dropdown-item" href="{{ route('recommendations.by-source', 'AI') }}">AI Recommendations</a></li>
                                <li><a class="dropdown-item" href="{{ route('recommendations.by-source', 'collaborative') }}">Collaborative</a></li>
                                <li><a class="dropdown-item" href="{{ route('recommendations.by-source', 'manual') }}">Manual</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($recommendations->count() > 0)
                        <div class="row">
                            @foreach($recommendations as $recommendation)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 recommendation-card {{ $recommendation->is_viewed ? '' : 'border-primary' }}" 
                                         data-recommendation-id="{{ $recommendation->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge bg-{{ $recommendation->source === 'AI' ? 'info' : ($recommendation->source === 'collaborative' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst($recommendation->source) }}
                                                </span>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item mark-viewed" href="#" data-id="{{ $recommendation->id }}">
                                                                <i class="bx bx-check me-2"></i>Mark as Viewed
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" 
                                                               onclick="deleteRecommendation({{ $recommendation->id }})">
                                                                <i class="bx bx-trash me-2"></i>Remove
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <h6 class="card-title">{{ $recommendation->livre->title }}</h6>
                                            <p class="text-muted small mb-2">by {{ $recommendation->livre->author }}</p>
                                            
                                            @if($recommendation->livre->categorie)
                                                <span class="badge bg-light text-dark mb-2">{{ $recommendation->livre->categorie->nom }}</span>
                                            @endif
                                            
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ $recommendation->score * 100 }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($recommendation->score * 100, 1) }}% match</small>
                                            </div>
                                            
                                            @if($recommendation->reason)
                                                <p class="card-text small text-muted">{{ \Illuminate\Support\Str::limit($recommendation->reason, 100) }}</p>
                                            @endif
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    {{ $recommendation->date_creation->format('M d, Y') }}
                                                </small>
                                                <a href="{{ route('livres') }}?search={{ urlencode($recommendation->livre->title) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Book
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-book-open display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No recommendations yet</h5>
                            <p class="text-muted">Start rating books to get personalized recommendations!</p>
                            <a href="{{ route('recommendations.generate') }}" class="btn btn-primary">
                                Generate Recommendations
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRecommendation(id) {
    if (confirm('Are you sure you want to remove this recommendation?')) {
        fetch(`/recommendations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Mark as viewed functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mark-viewed').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const recommendationId = this.getAttribute('data-id');
            
            fetch(`/recommendations/${recommendationId}/mark-viewed`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.querySelector(`[data-recommendation-id="${recommendationId}"]`);
                    card.classList.remove('border-primary');
                    this.closest('.dropdown-menu').querySelector('.mark-viewed').style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endsection
