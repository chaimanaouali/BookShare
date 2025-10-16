@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('vendor-style')
@vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
@vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
@vite('resources/assets/js/dashboards-analytics.js')
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Book Events</h5>
        <a href="{{ route('book-events.create') }}" class="btn btn-primary btn-sm">
          <i class="bx bx-plus"></i> Nouvel événement
        </a>
      </div>
      <div class="card-body">
        @if($events->count() > 0)
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>ÉVÉNEMENT</th>
                  <th>TYPE</th>
                  <th>DATE</th>
                  <th>STATUT</th>
                  <th>ACTIONS</th>
                </tr>
              </thead>
              <tbody>
                @foreach($events as $event)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="avatar flex-shrink-0 me-3">
                          @if($event->image)
                            <img src="{{ asset($event->image) }}" alt="{{ $event->titre }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                          @else
                            <span class="avatar-initial rounded bg-label-primary">
                              <i class="bx bx-book"></i>
                            </span>
                          @endif
                        </div>
                        <div class="d-flex flex-column">
                          <h6 class="mb-0">{{ $event->titre }}</h6>
                          <small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <span class="badge bg-label-info">{{ $event->type }}</span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="fw-medium">{{ \Carbon\Carbon::parse($event->date_evenement)->format('d M Y') }}</span>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($event->date_evenement)->diffForHumans() }}</small>
                      </div>
                    </td>
                    <td>
                      @php
                        $now = now();
                        $eventDate = \Carbon\Carbon::parse($event->date_evenement);
                        $isPast = $eventDate->isPast();
                        $isToday = $eventDate->isToday();
                        $isUpcoming = $eventDate->isFuture();
                      @endphp
                      @if($isPast)
                        <span class="badge bg-label-secondary">Terminé</span>
                      @elseif($isToday)
                        <span class="badge bg-label-warning">Aujourd'hui</span>
                      @elseif($isUpcoming)
                        <span class="badge bg-label-success">À venir</span>
                      @endif
                    </td>
                    <td>
                      <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                          <a class="dropdown-item" href="{{ route('book-events.show', $event->id) }}">
                            <i class="bx bx-show me-1"></i> Voir
                          </a>
                          <a class="dropdown-item" href="{{ route('book-events.edit', $event->id) }}">
                            <i class="bx bx-edit-alt me-1"></i> Modifier
                          </a>
                          <form action="{{ route('book-events.destroy', $event->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Supprimer cet événement ?')">
                              <i class="bx bx-trash me-1"></i> Supprimer
                            </button>
                          </form>
                        </div>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <i class="bx bx-book-open display-4 text-muted"></i>
            <h5 class="mt-3 text-muted">Aucun événement</h5>
            <p class="text-muted">Commencez par créer votre premier événement.</p>
            <a href="{{ route('book-events.create') }}" class="btn btn-primary">
              <i class="bx bx-plus"></i> Créer un événement
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
