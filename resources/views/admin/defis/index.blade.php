@extends('layouts/contentNavbarLayout')

@section('title', 'Défis')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Enhanced Défis Card -->
  <div class="card enhanced-card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-semibold text-dark">Liste des défis</h4>
        <div class="d-flex gap-2">
          <a href="{{ route('ranking.global') }}" class="btn btn-success btn-enhanced px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-trophy me-2"></i>Classement Global
          </a>
          <a href="{{ route('defis.create') }}" class="btn btn-primary btn-enhanced px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-plus me-2"></i>Nouveau défi
          </a>
        </div>
      </div>
    </div>
    
    @if(session('success'))
      <div class="alert alert-success mx-4 mt-3 mb-0" style="border-radius: 8px;">{{ session('success') }}</div>
    @endif
    
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 book-events-table">
          <thead>
            <tr>
              <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">TITRE</th>
              <th class="text-uppercase small fw-semibold text-muted py-3 px-4 text-center" style="font-size: 0.75rem; letter-spacing: 0.5px;">DATES</th>
              <th class="text-uppercase small fw-semibold text-muted py-3 px-4 text-end" style="font-size: 0.75rem; letter-spacing: 0.5px;">ACTIONS</th>
            </tr>
          </thead>
          <tbody>
            @forelse($defis as $defi)
              @php
                $startDate = $defi->date_debut ? \Carbon\Carbon::parse($defi->date_debut)->format('Y-m-d') : null;
                $endDate = $defi->date_fin ? \Carbon\Carbon::parse($defi->date_fin)->format('Y-m-d') : null;
              @endphp
              <tr class="border-bottom">
                <td class="py-3 px-4">
                  <div class="d-flex align-items-center">
                    <div class="me-3" style="width: 40px; height: 40px; border-radius: 8px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); display: flex; align-items: center; justify-content: center;">
                      <i class="bx bx-flag text-white" style="font-size: 18px;"></i>
                    </div>
                    <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $defi->titre }}</span>
                  </div>
                </td>
                <td class="py-3 px-4 text-center">
                  @if($startDate && $endDate)
                    <div class="d-flex flex-column align-items-center">
                      <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $startDate }} → {{ $endDate }}</span>
                      @php
                        $start = \Carbon\Carbon::parse($defi->date_debut);
                        $end = \Carbon\Carbon::parse($defi->date_fin);
                        $now = \Carbon\Carbon::now();
                        $statusLabel = 'Sans date';
                        $statusClass = 'bg-light-secondary text-secondary';
                        if ($start && $end) {
                          if ($now->lt($start)) { $statusLabel = 'À venir'; $statusClass = 'bg-light-info text-info'; }
                          elseif ($now->between($start, $end)) { $statusLabel = 'En cours'; $statusClass = 'bg-light-success text-success'; }
                          else { $statusLabel = 'Terminé'; $statusClass = 'bg-light-secondary text-secondary'; }
                        }
                      @endphp
                      <small class="text-muted mt-1" style="font-size: 0.75rem;">{{ $statusLabel }}</small>
                      <small class="text-primary mt-1" style="font-size: 0.7rem;">{{ $defi->livres_count }} livre(s)</small>
                    </div>
                  @else
                    <div class="d-flex flex-column align-items-center">
                      <small class="text-muted">—</small>
                      <small class="text-primary mt-1" style="font-size: 0.7rem;">{{ $defi->livres_count }} livre(s)</small>
                    </div>
                  @endif
                </td>
                <td class="py-3 px-4 text-end">
                  <div class="d-flex gap-2 justify-content-end">
                    <a href="{{ route('defis.show', $defi) }}" class="btn btn-sm px-3 py-2" style="background-color: #1976d2; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                      <i class="bx bx-show me-1"></i>Voir
                    </a>
                    <a href="{{ route('defis.participants', $defi) }}" class="btn btn-sm px-3 py-2" style="background-color: #00bcd4; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                      <i class="bx bx-group me-1"></i>Participants
                    </a>
                    <a href="{{ route('defis.ranking', $defi) }}" class="btn btn-sm px-3 py-2" style="background-color: #4caf50; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                      <i class="bx bx-trophy me-1"></i>Classement
                    </a>
                    <a href="{{ route('defis.edit', $defi) }}" class="btn btn-sm px-3 py-2" style="background-color: #ff9800; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                      <i class="bx bx-edit me-1"></i>Modifier
                    </a>
                    <form action="{{ route('defis.destroy', $defi) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce défi ?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm px-3 py-2" style="background-color: #f44336; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                        <i class="bx bx-trash me-1"></i>Supprimer
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="text-center py-5">
                  <div class="d-flex flex-column align-items-center">
                    <i class="bx bx-flag mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
                    <h6 class="text-muted mb-2">Aucun défi</h6>
                    <p class="text-muted mb-3" style="font-size: 0.9rem;">Créez votre premier défi pour engager la communauté</p>
                    <a href="{{ route('defis.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
                      <i class="bx bx-plus me-2"></i>Créer votre premier défi
                    </a>
                  </div>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    
    @if($defis->hasPages())
      <div class="card-footer bg-white border-0 py-3 px-4">
        <div class="d-flex justify-content-center">
          {{ $defis->links() }}
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
  
  .btn[style*="background-color: #ff9800"]:hover {
    background-color: #f57c00 !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
  }
  
  .btn[style*="background-color: #f44336"]:hover {
    background-color: #d32f2f !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
  }
  
  /* Enhanced Empty State */
  .empty-state {
    padding: 3rem 2rem;
  }
  
  .empty-state i {
    opacity: 0.6;
  }
  
  /* Enhanced Image Placeholder */
  .image-placeholder {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    overflow: hidden;
  }
  
  /* Responsive Enhancements */
  @media (max-width: 768px) {
    .book-events-table {
      font-size: 0.9rem;
    }
    
    .enhanced-card .card-header {
      padding: 1rem;
    }
    
    .btn-enhanced {
      padding: 0.5rem 1rem;
      font-size: 0.9rem;
    }
    
    .d-flex.gap-2 {
      flex-direction: column;
      gap: 0.5rem !important;
    }
  }
</style>
@endsection


