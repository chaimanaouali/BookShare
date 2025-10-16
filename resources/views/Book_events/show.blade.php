@extends('layouts/contentNavbarLayout')

@section('title', "Détails de l'événement")

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Détails de l'événement</h5>
        <div class="d-flex gap-2">
          <a href="{{ route('book-events.edit', $bookEvent->id) }}" class="btn btn-warning btn-sm">
            <i class="bx bx-edit me-1"></i> Modifier
          </a>
          <a href="{{ route('book-events.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bx bx-arrow-back me-1"></i> Retour
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <div class="mb-4">
              <div class="d-flex align-items-center mb-3">
                <div class="avatar flex-shrink-0 me-3">
                  @if($bookEvent->image)
                    <img src="{{ asset($bookEvent->image) }}" alt="{{ $bookEvent->titre }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                  @else
                    <span class="avatar-initial rounded bg-label-primary">
                      <i class="bx bx-book"></i>
                    </span>
                  @endif
                </div>
                <div>
                  <h4 class="mb-1">{{ $bookEvent->titre }}</h4>
                  <span class="badge bg-label-info fs-6">{{ $bookEvent->type }}</span>
                </div>
              </div>
              
              @if($bookEvent->description)
                <div class="mb-4">
                  <h6 class="text-muted mb-2">Description</h6>
                  <p class="mb-0">{{ $bookEvent->description }}</p>
                </div>
              @endif
            </div>
          </div>
          
          <div class="col-md-4">
            <div class="card bg-light">
              <div class="card-body">
                <h6 class="card-title text-muted mb-3">Informations</h6>
                
                <div class="d-flex align-items-center mb-3">
                  <div class="avatar flex-shrink-0 me-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                      <i class="bx bx-hash"></i>
                    </span>
                  </div>
                  <div>
                    <small class="text-muted d-block">ID</small>
                    <span class="fw-medium">#{{ $bookEvent->id }}</span>
                  </div>
                </div>
                
                <div class="d-flex align-items-center">
                  <div class="avatar flex-shrink-0 me-3">
                    <span class="avatar-initial rounded bg-label-success">
                      <i class="bx bx-calendar"></i>
                    </span>
                  </div>
                  <div>
                    <small class="text-muted d-block">Date de l'événement</small>
                    <span class="fw-medium">{{ \Carbon\Carbon::parse($bookEvent->date_evenement)->format('d M Y') }}</span>
                    <br>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($bookEvent->date_evenement)->diffForHumans() }}</small>
                  </div>
                </div>
              </div>
            </div>
            
            @php
              $now = now();
              $eventDate = \Carbon\Carbon::parse($bookEvent->date_evenement);
              $isPast = $eventDate->isPast();
              $isToday = $eventDate->isToday();
              $isUpcoming = $eventDate->isFuture();
            @endphp
            
            <div class="mt-3">
              @if($isPast)
                <div class="alert alert-secondary" role="alert">
                  <i class="bx bx-time-five me-2"></i>
                  <strong>Événement terminé</strong><br>
                  <small>Cet événement a eu lieu dans le passé.</small>
                </div>
              @elseif($isToday)
                <div class="alert alert-warning" role="alert">
                  <i class="bx bx-calendar-check me-2"></i>
                  <strong>Événement aujourd'hui</strong><br>
                  <small>Cet événement se déroule aujourd'hui !</small>
                </div>
              @elseif($isUpcoming)
                <div class="alert alert-success" role="alert">
                  <i class="bx bx-calendar-plus me-2"></i>
                  <strong>Événement à venir</strong><br>
                  <small>Cet événement est programmé pour le futur.</small>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
