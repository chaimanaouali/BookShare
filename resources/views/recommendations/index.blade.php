@extends('layouts/contentNavbarLayout')

@section('title', 'Recommendations')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">
            Your <span class="text-primary">Recommendations</span>
        </h1>
        <p class="lead text-muted mb-4">Personalized suggestions based on your reviews</p>
        <a href="{{ route('recommendations.generate.get') }}" class="btn btn-lg px-4 py-2" 
           style="background-color: #FF3B30; border: 1px solid #FF3B30; color: white;">
            Generate Recommendations
        </a>
    </div>

    <!-- Recommendations Grid -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($recommendations->count() > 0)
            <div class="row g-4">
                @foreach($recommendations as $recommendation)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 recommendation-card" 
                             data-recommendation-id="{{ $recommendation->id }}"
                             style="border-radius: 12px; transition: transform 0.2s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-primary text-white px-3 py-2" style="border-radius: 8px; font-size: 0.75rem;">
                                        {{ $recommendation->source === 'AI' ? 'AI' : ucfirst($recommendation->source) }}
                                    </span>
                                    <small class="text-muted fw-medium">{{ number_format($recommendation->score * 100, 0) }}% match</small>
                                </div>
                                
                                <h5 class="card-title fw-bold mb-2" style="color: #333; line-height: 1.3;">
                                    {{ $recommendation->livre->title }}
                                </h5>
                                
                                @if($recommendation->livre->categorie)
                                    <span class="badge bg-dark text-white mb-3" style="border-radius: 6px; font-size: 0.7rem;">
                                        {{ $recommendation->livre->categorie->nom }}
                                    </span>
                                @endif
                                
                                @if($recommendation->reason)
                                    <p class="card-text small text-muted mb-3" style="line-height: 1.4;">
                                        {{ \Illuminate\Support\Str::limit($recommendation->reason, 80) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        {{ $recommendation->date_creation->format('M d, Y') }}
                                    </small>
                                    <a href="{{ route('livres') }}?search={{ urlencode($recommendation->livre->title) }}" 
                                       class="btn btn-sm px-3 py-2" 
                                       style="background-color: #ff6b35; border: 1px solid #ff6b35; color: white; border-radius: 6px; font-weight: 500;">
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
                <div class="mb-4">
                    <i class="bx bx-book-open display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">No recommendations yet</h4>
                <p class="text-muted mb-4">Start rating books to get personalized recommendations!</p>
                <a href="{{ route('recommendations.generate') }}" class="btn btn-lg px-4 py-2" 
                   style="background-color: #FF3B30; border: 1px solid #FF3B30; color: white;">
                    Generate Recommendations
                </a>
            </div>
        @endif
    </div>
</div>

<style>
.recommendation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>
@endsection
