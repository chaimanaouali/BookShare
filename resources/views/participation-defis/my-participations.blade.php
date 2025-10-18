@extends('layouts/contentNavbarLayout')

@section('title', 'Mes participations aux défis')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Mes participations aux défis</h4>
          <p class="text-muted mb-0 mt-1">Suivez votre progression dans les défis de lecture</p>
        </div>
        <a href="{{ route('defis.index') }}" class="btn btn-primary btn-enhanced px-3 py-2" style="border-radius: 8px;">
          <i class="bx bx-plus me-2"></i>Découvrir les défis
        </a>
      </div>
    </div>
  </div>

  <!-- Participations List -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Mes défis en cours</h5>
    </div>
    
    @if($participations->count() > 0)
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 book-events-table">
            <thead>
              <tr>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">DÉFI</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">STATUT</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PROGRESSION</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4 text-end" style="font-size: 0.75rem; letter-spacing: 0.5px;">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($participations as $participation)
                <tr class="border-bottom">
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); display: flex; align-items: center; justify-content: center;">
                        <i class="bx bx-flag text-white" style="font-size: 18px;"></i>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $participation->defi->titre }}</span>
                        <small class="text-muted" style="font-size: 0.8rem;">
                          {{ $participation->defi->date_debut ? \Carbon\Carbon::parse($participation->defi->date_debut)->translatedFormat('d M Y') : 'Sans date' }}
                          @if($participation->defi->date_fin)
                            → {{ \Carbon\Carbon::parse($participation->defi->date_fin)->translatedFormat('d M Y') }}
                          @endif
                        </small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 36px; height: 36px; border-radius: 6px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        @if($participation->livre->cover_image && file_exists(public_path($participation->livre->cover_image)))
                          <img src="/{{ $participation->livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bx bx-book text-white" style="font-size: 16px;"></i>
                          </div>
                        @endif
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $participation->livre->title }}</span>
                        <small class="text-muted" style="font-size: 0.75rem;">{{ $participation->livre->author ?: 'Auteur non spécifié' }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    @php
                      $statusConfig = [
                        'en_cours' => ['class' => 'bg-light-primary text-primary', 'label' => 'En cours'],
                        'termine' => ['class' => 'bg-light-success text-success', 'label' => 'Terminé'],
                        'abandonne' => ['class' => 'bg-light-secondary text-secondary', 'label' => 'Abandonné'],
                      ];
                      $config = $statusConfig[$participation->status] ?? $statusConfig['en_cours'];
                    @endphp
                    <span class="badge rounded-pill px-3 py-2 {{ $config['class'] }}" style="font-size: 0.8rem; font-weight: 500;">
                      {{ $config['label'] }}
                    </span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex flex-column">
                      <small class="text-muted mb-1" style="font-size: 0.75rem;">
                        Commencé le {{ $participation->date_debut_lecture ? \Carbon\Carbon::parse($participation->date_debut_lecture)->translatedFormat('d M Y') : 'N/A' }}
                      </small>
                      @if($participation->date_fin_lecture)
                        <small class="text-success" style="font-size: 0.75rem;">
                          Terminé le {{ \Carbon\Carbon::parse($participation->date_fin_lecture)->translatedFormat('d M Y') }}
                        </small>
                      @endif
                      @if($participation->note)
                        <div class="d-flex align-items-center mt-1">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="bx bx-star{{ $i <= $participation->note ? '' : '-o' }}" style="color: #ffc107; font-size: 12px;"></i>
                          @endfor
                        </div>
                      @endif
                    </div>
                  </td>
                  <td class="py-3 px-4 text-end">
                    <div class="d-flex gap-2 justify-content-end">
                      <a href="{{ route('participation-defis.show', $participation) }}" class="btn btn-sm px-3 py-2" style="background-color: #1976d2; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                        <i class="bx bx-show me-1"></i>Voir
                      </a>
                      @if($participation->status === 'en_cours')
                        <form action="{{ route('participation-defis.update-status', $participation) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="status" value="termine">
                          <button class="btn btn-sm px-3 py-2" style="background-color: #28a745; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;" onclick="return confirm('Marquer ce défi comme terminé ?')">
                            <i class="bx bx-check me-1"></i>Terminer
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      
      @if($participations->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4">
          <div class="d-flex justify-content-center">
            {{ $participations->links() }}
          </div>
        </div>
      @endif
    @else
      <div class="card-body p-5 text-center">
        <div class="d-flex flex-column align-items-center">
          <i class="bx bx-flag mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
          <h6 class="text-muted mb-2">Aucune participation</h6>
          <p class="text-muted mb-3" style="font-size: 0.9rem;">Commencez par participer à un défi de lecture</p>
          <a href="{{ route('defis.index') }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-plus me-2"></i>Découvrir les défis
          </a>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection

@section('extra-css')
<style>
  /* Enhanced Défis Table Styling */
  .book-events-table {
    border-collapse: separate;
    border-spacing: 0;
  }
  
  .book-events-table thead th {
    border-bottom: 1px solid #e9ecef;
    font-weight: 600;
    color: #6c757d;
    background-color: #f8f9fa;
  }
  
  .book-events-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f3f4;
  }
  
  .book-events-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  }
  
  .book-events-table tbody tr:last-child {
    border-bottom: none;
  }
  
  /* Enhanced Card Styling */
  .enhanced-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
  }
  
  .enhanced-card .card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
  }
  
  /* Enhanced Button Styling */
  .btn-enhanced {
    transition: all 0.2s ease;
    font-weight: 500;
  }
  
  .btn-enhanced:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  }
  
  /* Action Button Hover Effects */
  .btn[style*="background-color: #1976d2"]:hover {
    background-color: #1565c0 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
  }
  
  .btn[style*="background-color: #28a745"]:hover {
    background-color: #218838 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
  }
</style>
@endsection


