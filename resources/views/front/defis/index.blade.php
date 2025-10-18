@extends('front.layouts.app')

@section('title', 'Défis de Lecture')

@section('content')
<div class="container py-5">
  <!-- Header Section -->
  <div class="row mb-5">
    <div class="col-12 text-center">
      <h1 class="display-4 fw-bold text-dark mb-3">Défis de Lecture</h1>
      <p class="lead text-muted">Relevez des défis de lecture et partagez votre passion avec la communauté</p>
    </div>
  </div>

  <!-- Défis Grid -->
  @if($defis->count() > 0)
    <div class="row g-4">
      @foreach($defis as $defi)
        <div class="col-lg-4 col-md-6">
          <div class="card enhanced-card h-100">
            <div class="card-body d-flex flex-column">
              <!-- Défi Image/Icon -->
              <div class="text-center mb-3">
                <div class="image-placeholder mx-auto mb-3" style="width: 80px; height: 80px;">
                  <i class="bx bx-trophy text-white" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="card-title fw-bold text-dark">{{ $defi->titre }}</h5>
              </div>

              <!-- Défi Description -->
              <p class="card-text text-muted flex-grow-1">
                {{ Str::limit($defi->description, 120) }}
              </p>

              <!-- Défi Stats -->
              <div class="row text-center mb-3">
                <div class="col-6">
                  <small class="text-muted d-block">Livres disponibles</small>
                  <span class="fw-bold text-primary">{{ $defi->livres_count }}</span>
                </div>
                <div class="col-6">
                  <small class="text-muted d-block">Durée</small>
                  <span class="fw-bold text-info">
                    @if($defi->date_debut && $defi->date_fin)
                      {{ \Carbon\Carbon::parse($defi->date_debut)->diffInDays(\Carbon\Carbon::parse($defi->date_fin)) + 1 }} jours
                    @else
                      Flexible
                    @endif
                  </span>
                </div>
              </div>

              <!-- Défi Dates -->
              <div class="mb-3">
                @if($defi->date_debut)
                  <small class="text-muted d-block">Début</small>
                  <span class="fw-medium">{{ \Carbon\Carbon::parse($defi->date_debut)->translatedFormat('d M Y') }}</span>
                @endif
                @if($defi->date_fin)
                  <small class="text-muted d-block mt-1">Fin</small>
                  <span class="fw-medium">{{ \Carbon\Carbon::parse($defi->date_fin)->translatedFormat('d M Y') }}</span>
                @endif
              </div>

              <!-- Action Button -->
              <div class="mt-auto">
                <a href="{{ route('front.defis.show', $defi) }}" class="btn btn-primary btn-enhanced w-100">
                  <i class="bx bx-play me-2"></i>Voir le défi
                </a>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
      {{ $defis->links() }}
    </div>
  @else
    <!-- Empty State -->
    <div class="text-center py-5">
      <div class="empty-state">
        <i class="bx bx-book-open mb-3" style="font-size: 4rem; color: #dee2e6;"></i>
        <h4 class="text-muted mb-3">Aucun défi disponible</h4>
        <p class="text-muted mb-4">Il n'y a actuellement aucun défi de lecture disponible.</p>
        <a href="{{ route('front.events.index') }}" class="btn btn-primary">
          <i class="bx bx-calendar me-2"></i>Voir les événements
        </a>
      </div>
    </div>
  @endif
</div>
@endsection

@section('extra-css')
<style>
  .enhanced-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .enhanced-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .image-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
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

  .empty-state {
    padding: 3rem 2rem;
  }

  .empty-state i {
    opacity: 0.6;
  }
</style>
@endsection
