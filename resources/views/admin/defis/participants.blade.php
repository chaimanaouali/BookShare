@extends('layouts/contentNavbarLayout')

@section('title', 'Participants - ' . $defi->titre)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Participants Challenges: {{ $defi->titre }}</h4>
    <a href="{{ route('defis.index') }}" class="btn btn-outline-secondary"><i class="bx bx-arrow-back me-1"></i>Retour</a>
  </div>

  <div class="card enhanced-card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0">List of participants ({{ $defi->participations->count() }})</h5>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>User</th>
            <th>Book</th>
            <th>Statut</th>
            <th>Note</th>
            <th>Start </th>
            <th>End</th>
          </tr>
        </thead>
        <tbody>
          @forelse($defi->participations as $p)
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  <i class="bx bx-user-circle" style="font-size: 1.4rem"></i>
                  <div>
                    <div class="fw-semibold">{{ $p->user->name ?? 'Utilisateur' }}</div>
                    <small class="text-muted">ID: {{ $p->user_id }}</small>
                  </div>
                </div>
              </td>
              <td>
                <div class="fw-semibold">{{ $p->livre->title ?? 'Livre supprimé' }}</div>
                <small class="text-muted">{{ $p->livre->author ?? '' }}</small>
              </td>
              <td>
                @php
                  $badge = match($p->status){
                    'en_cours' => 'info',
                    'termine' => 'success',
                    'abandonne' => 'secondary',
                    default => 'light'
                  };
                @endphp
                <span class="badge bg-{{ $badge }}">{{ ucfirst(str_replace('_',' ', $p->status)) }}</span>
              </td>
              <td>{{ $p->note ? $p->note . '/5' : '-' }}</td>
              <td>{{ $p->date_debut_lecture ? \Carbon\Carbon::parse($p->date_debut_lecture)->translatedFormat('d M Y H:i') : '-' }}</td>
              <td>{{ $p->date_fin_lecture ? \Carbon\Carbon::parse($p->date_fin_lecture)->translatedFormat('d M Y H:i') : '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">No participants for this challenge.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('extra-css')
<style>
  .enhanced-card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); border-radius: 12px; overflow: hidden; }
  .enhanced-card .card-header { background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border-bottom: 1px solid #e9ecef; }
</style>
@endsection
