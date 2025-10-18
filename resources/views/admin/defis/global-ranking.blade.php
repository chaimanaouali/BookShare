@extends('layouts/contentNavbarLayout')

@section('title', 'Classement Global')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Classement Global</h4>
          <p class="text-muted mb-0 mt-1">Classement de tous les participants sur tous les défis</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('defis.index') }}" class="btn btn-secondary btn-enhanced px-3 py-2" style="border-radius: 8px;">
            <i class="bx bx-arrow-back me-2"></i>Retour aux défis
          </a>
          <form action="{{ route('ranking.recalculate') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-enhanced px-3 py-2" style="border-radius: 8px;">
              <i class="bx bx-refresh me-2"></i>Recalculer
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Global Ranking Table -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Classement des utilisateurs</h5>
      <p class="text-muted mb-0 mt-1">Basé sur le score moyen et le nombre de défis terminés</p>
    </div>
    
    @if(count($userRankings) > 0)
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 book-events-table">
            <thead>
              <tr>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">POSITION</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">UTILISATEUR</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">DÉFIS TERMINÉS</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">SCORE MOYEN</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">TEMPS MOYEN</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">SCORE TOTAL</th>
              </tr>
            </thead>
            <tbody>
              @foreach($userRankings as $ranking)
                <tr class="border-bottom">
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      @if($ranking['position'] == 1)
                        <i class="bx bx-trophy text-warning me-2" style="font-size: 1.5rem;"></i>
                      @elseif($ranking['position'] == 2)
                        <i class="bx bx-medal text-secondary me-2" style="font-size: 1.5rem;"></i>
                      @elseif($ranking['position'] == 3)
                        <i class="bx bx-award text-warning me-2" style="font-size: 1.5rem;"></i>
                      @else
                        <span class="badge bg-light text-dark me-2" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.9rem; font-weight: bold;">
                          {{ $ranking['position'] }}
                        </span>
                      @endif
                      <span class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $ranking['position'] }}</span>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 48px; height: 48px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                          <i class="bx bx-user text-white" style="font-size: 20px;"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-bold text-dark mb-1" style="font-size: 1rem;">{{ $ranking['user']->name }}</span>
                        <small class="text-muted" style="font-size: 0.85rem;">{{ $ranking['user']->email }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <span class="fw-bold text-primary me-2" style="font-size: 1.2rem;">{{ $ranking['completed_challenges'] }}</span>
                      <span class="text-muted">défi(s)</span>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <span class="fw-bold text-dark me-2" style="font-size: 1.1rem;">{{ $ranking['average_score'] }}/5</span>
                      <div class="progress" style="width: 80px; height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ ($ranking['average_score'] / 5) * 100 }}%"></div>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    @if($ranking['average_time_minutes'] > 0)
                      @php
                        $hours = floor($ranking['average_time_minutes'] / 60);
                        $minutes = $ranking['average_time_minutes'] % 60;
                      @endphp
                      <span class="fw-medium text-dark">
                        @if($hours > 0)
                          {{ $hours }}h {{ $minutes }}min
                        @else
                          {{ $minutes }}min
                        @endif
                      </span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <span class="fw-bold text-primary me-2" style="font-size: 1.2rem;">{{ $ranking['total_score'] }}</span>
                      <div class="progress" style="width: 80px; height: 8px;">
                        @php
                          $maxScore = max(array_column($userRankings, 'total_score'));
                          $percentage = $maxScore > 0 ? ($ranking['total_score'] / $maxScore) * 100 : 0;
                        @endphp
                        <div class="progress-bar bg-primary" style="width: {{ $percentage }}%"></div>
                      </div>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @else
      <div class="card-body p-5 text-center">
        <div class="d-flex flex-column align-items-center">
          <i class="bx bx-trophy mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
          <h6 class="text-muted mb-2">Aucun participant</h6>
          <p class="text-muted mb-3" style="font-size: 0.9rem;">Le classement sera disponible quand des utilisateurs auront terminé des défis</p>
          <a href="{{ route('defis.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-arrow-back me-2"></i>Retour aux défis
          </a>
        </div>
      </div>
    @endif
  </div>

  <!-- Ranking Criteria Info -->
  <div class="card enhanced-card mt-4">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Critères de classement</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #28a745 0%, #20c997 100%); display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-star text-white" style="font-size: 18px;"></i>
            </div>
            <div>
              <h6 class="fw-semibold text-dark mb-1">Score Moyen (40%)</h6>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">Moyenne des notes et scores de quiz sur tous les défis terminés</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #ff9800 0%, #ffc107 100%); display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-time text-white" style="font-size: 18px;"></i>
            </div>
            <div>
              <h6 class="fw-semibold text-dark mb-1">Vitesse (30%)</h6>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">Temps moyen pour terminer les défis (plus rapide = meilleur score)</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="d-flex align-items-start">
            <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #9c27b0 0%, #e91e63 100%); display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-check-circle text-white" style="font-size: 18px;"></i>
            </div>
            <div>
              <h6 class="fw-semibold text-dark mb-1">Nombre de défis (30%)</h6>
              <p class="text-muted mb-0" style="font-size: 0.9rem;">Nombre total de défis terminés avec succès</p>
            </div>
          </div>
        </div>
      </div>
    </div>
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
  
  /* Progress bar styling */
  .progress {
    background-color: #e9ecef;
    border-radius: 4px;
  }
  
  .progress-bar {
    border-radius: 4px;
  }
</style>
@endsection

