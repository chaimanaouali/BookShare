@extends('front.layouts.app')

@section('title', $defi->titre)

@section('content')
<div class="container py-5">
  <!-- Back Button -->
  <div class="mb-4">
    <a href="{{ route('front.defis.index') }}" class="btn btn-outline-secondary">
      <i class="bx bx-arrow-back me-2"></i>Retour aux défis
    </a>
  </div>

  <!-- Défi Header -->
  <div class="card enhanced-card mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-8">
          <div class="d-flex align-items-center mb-3">
            <div class="image-placeholder me-3" style="width: 60px; height: 60px;">
              <i class="bx bx-trophy text-white" style="font-size: 2rem;"></i>
            </div>
            <div>
              <h1 class="h3 fw-bold text-dark mb-1">{{ $defi->titre }}</h1>
              <p class="text-muted mb-0">{{ $defi->livres->count() }} livre(s) disponible(s)</p>
            </div>
          </div>
          
          @if($defi->description)
            <p class="text-muted mb-3">{{ $defi->description }}</p>
          @endif

          <!-- Défi Info -->
          <div class="row">
            <div class="col-md-6">
              <small class="text-muted d-block">Date de début</small>
              <span class="fw-medium">
                {{ $defi->date_debut ? \Carbon\Carbon::parse($defi->date_debut)->translatedFormat('d M Y') : 'Non définie' }}
              </span>
            </div>
            <div class="col-md-6">
              <small class="text-muted d-block">Date de fin</small>
              <span class="fw-medium">
                {{ $defi->date_fin ? \Carbon\Carbon::parse($defi->date_fin)->translatedFormat('d M Y') : 'Non définie' }}
              </span>
            </div>
          </div>
        </div>
        
        <div class="col-md-4 text-md-end">
          @if($userParticipation)
            <!-- Already Participating -->
            <div class="alert alert-info">
              <i class="bx bx-check-circle me-2"></i>
              <strong>Vous participez déjà à ce défi</strong>
              <div class="mt-2">
                <a href="{{ route('participation-defis.show', $userParticipation) }}" class="btn btn-primary btn-sm">
                  <i class="bx bx-eye me-1"></i>Voir ma participation
                </a>
              </div>
            </div>
          @else
            <!-- Participate Button -->
            <a href="{{ route('participation-defis.create', $defi) }}" class="btn btn-success btn-enhanced btn-lg px-4 py-3">
              <i class="bx bx-play me-2"></i>Participer au défi
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Livres Disponibles -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Livres disponibles pour ce défi</h5>
    </div>
    <div class="card-body">
      @if($defi->livres->count() > 0)
        <div class="row g-3">
          @foreach($defi->livres as $livre)
            <div class="col-lg-4 col-md-6">
              <div class="card border h-100">
                <div class="card-body">
                  <div class="d-flex align-items-start mb-3">
                    <div class="me-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                      @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                        <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                      @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                          <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                        </div>
                      @endif
                    </div>
                    <div class="flex-grow-1">
                      <h6 class="card-title fw-bold text-dark mb-1">{{ $livre->title }}</h6>
                      <p class="text-muted small mb-1">{{ $livre->author }}</p>
                      <span class="badge bg-primary">{{ strtoupper($livre->format ?: 'PDF') }}</span>
                    </div>
                  </div>
                  
                  @if($livre->user_description)
                    <p class="card-text text-muted small">{{ Str::limit($livre->user_description, 80) }}</p>
                  @endif
                  
                  <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $livre->taille ?: 'Taille non spécifiée' }}</small>
                    <small class="text-muted">Par {{ $livre->user->name ?? 'Utilisateur' }}</small>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="text-center py-4">
          <i class="bx bx-book-open mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
          <h6 class="text-muted">Aucun livre disponible</h6>
          <p class="text-muted">Ce défi n'a pas encore de livres associés.</p>
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@section('extra-css')
<style>
  .enhanced-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
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
</style>
@endsection
