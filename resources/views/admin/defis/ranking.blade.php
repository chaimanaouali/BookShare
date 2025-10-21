@extends('layouts/contentNavbarLayout')

@section('title', 'Classement du défi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Header Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-0 fw-semibold text-dark">Challenges Ranking</h4>
          <p class="text-muted mb-0 mt-1">{{ $defi->titre }}</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary btn-enhanced px-3 py-2" style="border-radius: 8px;">
            <i class="bx bx-arrow-back me-2"></i>Back to challenge
          </a>
          <form action="{{ route('ranking.recalculate') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning btn-enhanced px-3 py-2" style="border-radius: 8px;">
              <i class="bx bx-refresh me-2"></i>Recalculate
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Ranking Table -->
  <div class="card enhanced-card">
    <div class="card-header">
      <h5 class="mb-0 fw-semibold text-dark">Participants Ranking</h5>
      <p class="text-muted mb-0 mt-1">Ranked by average score and number of completed challenges</p>
    </div>
    
    @if($rankedParticipations->count() > 0)
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 book-events-table">
            <thead>
              <tr>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">POSITION</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PARTICIPANT</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">SCORE MOYEN</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">TEMPS</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">SCORE QUIZ</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">SCORE TOTAL</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rankedParticipations as $participation)
                <tr class="border-bottom">
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      @if($participation->ranking_position == 1)
                        <i class="bx bx-trophy text-warning me-2" style="font-size: 1.2rem;"></i>
                      @elseif($participation->ranking_position == 2)
                        <i class="bx bx-medal text-secondary me-2" style="font-size: 1.2rem;"></i>
                      @elseif($participation->ranking_position == 3)
                        <i class="bx bx-award text-warning me-2" style="font-size: 1.2rem;"></i>
                      @else
                        <span class="badge bg-light text-dark me-2" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-size: 0.8rem;">
                          {{ $participation->ranking_position }}
                        </span>
                      @endif
                      <span class="fw-bold text-dark">{{ $participation->ranking_position }}</span>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="d-flex align-items-center justify-content-center h-100">
                          <i class="bx bx-user text-white" style="font-size: 16px;"></i>
                        </div>
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $participation->user->name }}</span>
                        <small class="text-muted" style="font-size: 0.8rem;">{{ $participation->user->email }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 40px; height: 40px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                        @if($participation->livre->cover_image && file_exists(public_path($participation->livre->cover_image)))
                          <img src="/{{ $participation->livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bx bx-book text-white" style="font-size: 16px;"></i>
                          </div>
                        @endif
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $participation->livre->title }}</span>
                        <small class="text-muted" style="font-size: 0.8rem;">{{ $participation->livre->author }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    @if($participation->average_score)
                      <div class="d-flex align-items-center">
                        <span class="fw-bold text-dark me-2">{{ $participation->average_score }}/5</span>
                        <div class="progress" style="width: 60px; height: 6px;">
                          <div class="progress-bar bg-success" style="width: {{ ($participation->average_score / 5) * 100 }}%"></div>
                        </div>
                      </div>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="py-3 px-4">
                    @if($participation->completion_time_minutes)
                      @php
                        $hours = floor($participation->completion_time_minutes / 60);
                        $minutes = $participation->completion_time_minutes % 60;
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
                    @if($participation->quiz_score !== null)
                      <span class="badge bg-info">{{ $participation->quiz_score }}/{{ $participation->quiz_total_questions }}</span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <span class="fw-bold text-dark me-2">{{ $participation->ranking_score ?? 0 }}</span>
                      <div class="progress" style="width: 60px; height: 6px;">
                        <div class="progress-bar bg-primary" style="width: {{ (($participation->ranking_score ?? 0) / 5) * 100 }}%"></div>
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
          <h6 class="text-muted mb-2">Aucun participant terminé</h6>
          <p class="text-muted mb-3" style="font-size: 0.9rem;">Le classement sera disponible quand des participants auront terminé le défi</p>
          <a href="{{ route('defis.show', $defi) }}" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-arrow-back me-2"></i>Retour au défi
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
  
  /* Progress bar styling */
  .progress {
    background-color: #e9ecef;
    border-radius: 3px;
  }
  
  .progress-bar {
    border-radius: 3px;
  }
</style>
@endsection


