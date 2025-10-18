@extends('layouts/contentNavbarLayout')

@section('title', 'Book Events')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Book Events</h5>
      <a href="{{ route('book-events.create') }}" class="btn btn-primary">
        <span class="tf-icons bx bx-plus me-1"></span> Nouvel événement
      </a>
    </div>

    @if ($message = Session::get('success'))
      <div class="alert alert-success m-3 mb-0">{{ $message }}</div>
    @endif

    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
            <tr>
            <th class="text-uppercase small text-muted">Événement</th>
            <th class="text-uppercase small text-muted">Type</th>
            <th class="text-uppercase small text-muted">Date</th>
            <th class="text-uppercase small text-muted">Statut</th>
            <th class="text-uppercase small text-muted text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @php
          use Carbon\Carbon;
        @endphp
        @forelse ($events as $event)
          @php
            // Use stored status instead of calculating
            $statusLabels = [
                'a_venir' => 'À venir',
                'en_cours' => 'En cours',
                'termine' => 'Terminé'
            ];
            $statusClasses = [
                'a_venir' => 'bg-label-primary',
                'en_cours' => 'bg-label-success',
                'termine' => 'bg-label-secondary'
            ];
            $statusLabel = $statusLabels[$event->status] ?? 'À venir';
            $statusClass = $statusClasses[$event->status] ?? 'bg-label-primary';
            $image = $event->image ?: 'images/events/'.(file_exists(public_path('images/events/default.jpg')) ? 'default.jpg' : (collect(glob(public_path('images/events/*')))->first() ? basename(collect(glob(public_path('images/events/*')))->first()) : ''));
          @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div class="avatar me-3">
                  <img src="/{{ $image }}" alt="event" class="rounded" style="width:44px;height:44px;object-fit:cover;">
                </div>
                <div class="d-flex flex-column">
                  <span class="fw-medium">{{ $event->titre }}</span>
                  <small class="text-muted text-truncate" style="max-width:420px;">{{ $event->description }}</small>
                </div>
              </div>
            </td>
            <td>
              <span class="badge bg-label-primary">{{ ucfirst($event->type) }}</span>
            </td>
            <td>
              <div class="d-flex flex-column">
                <span>{{ Carbon::parse($event->date_evenement)->translatedFormat('d M Y') }}</span>
                <small class="text-muted">{{ Carbon::parse($event->date_evenement)->diffForHumans() }}</small>
              </div>
            </td>
            <td>
              <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
            </td>
            <td class="text-end">
              <div class="dropdown">
                <button class="btn p-0" type="button" id="evActions{{ $event->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="evActions{{ $event->id }}">
                  <a class="dropdown-item" href="{{ route('book-events.show', $event->id) }}">Voir</a>
                  <a class="dropdown-item" href="{{ route('book-events.edit', $event->id) }}">Modifier</a>
                  <div class="dropdown-divider"></div>
                  <form action="{{ route('book-events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger">Supprimer</button>
                  </form>
                </div>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Aucun événement pour le moment.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <h5 class="mb-0">Liste des défis</h5>
        @if(isset($recentDefis))
          <span class="badge bg-label-primary">{{ $recentDefis->count() }}</span>
        @endif
      </div>
      <a href="{{ route('defis.create') }}" class="btn btn-primary">Nouveau défi</a>
    </div>
    
    @if(isset($recentDefis) && $recentDefis->count())
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead>
            <tr>
              <th class="text-muted text-uppercase small">Titre</th>
              <th class="text-muted text-uppercase small">Période</th>
              <th class="text-muted text-uppercase small">Événements liés</th>
              <th class="text-muted text-uppercase small">Statut</th>
              <th class="text-muted text-uppercase small text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentDefis as $defi)
              @php
                $start = $defi->date_debut ? Carbon::parse($defi->date_debut) : null;
                $end = $defi->date_fin ? Carbon::parse($defi->date_fin) : null;
                $now = Carbon::now();
                $statusLabel = 'Sans date';
                $statusClass = 'bg-label-secondary';
                if ($start && $end) {
                  if ($now->lt($start)) { $statusLabel = 'À venir'; $statusClass = 'bg-label-info'; }
                  elseif ($now->between($start, $end)) { $statusLabel = 'En cours'; $statusClass = 'bg-label-success'; }
                  else { $statusLabel = 'Terminé'; $statusClass = 'bg-label-secondary'; }
                }
              @endphp
              <tr>
                <td class="fw-medium">
                  <div class="d-flex align-items-center">
                    <span class="avatar-initial rounded bg-label-primary me-3" style="width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;">
                      <i class="bx bx-flag"></i>
                    </span>
                    <span>{{ $defi->titre }}</span>
                  </div>
                </td>
                <td>
                  @if($start || $end)
                    <div class="d-flex flex-column">
                      <span>{{ $start?->translatedFormat('d M Y') }} @if($start && $end) → {{ $end->translatedFormat('d M Y') }} @endif</span>
                      @if($start)<small class="text-muted">{{ $start->diffForHumans() }} @if($end) • fin {{ $end->diffForHumans() }} @endif</small>@endif
                    </div>
                  @else
                    <small class="text-muted">—</small>
                  @endif
                </td>
                <td>
                  @if(isset($defi->bookEvents) && $defi->bookEvents->count())
                    <div class="d-flex flex-wrap gap-2">
                      @foreach($defi->bookEvents as $ev)
                        <span class="badge bg-label-info">{{ $ev->titre }}</span>
                      @endforeach
                    </div>
                  @else
                    <small class="text-muted">—</small>
                  @endif
                </td>
                <td>
                  <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                </td>
                <td class="text-end">
                  <div class="dropdown">
                    <button class="btn p-0" type="button" id="defiActions{{ $defi->id }}" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="defiActions{{ $defi->id }}">
                      <a class="dropdown-item" href="{{ route('defis.show', $defi) }}">Voir</a>
                      <a class="dropdown-item" href="{{ route('defis.edit', $defi) }}">Modifier</a>
                    </div>
                  </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
      </div>
    @else
      <div class="p-5 text-center text-muted">
        <i class="bx bx-flag mb-2" style="font-size:2rem;"></i>
        <p class="mb-3">Aucun défi</p>
        <a href="{{ route('defis.create') }}" class="btn btn-primary">Créer votre premier défi</a>
      </div>
    @endif
  </div>
  </div>
</div>
@endsection
