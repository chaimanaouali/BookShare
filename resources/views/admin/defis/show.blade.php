@extends('layouts/contentNavbarLayout')

@section('title', 'Détails du défi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Défi Details Card -->
  <div class="card enhanced-card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h4 class="mb-0 fw-semibold text-dark">{{ $defi->titre }}</h4>
        <div class="d-flex gap-2">
          <a href="{{ route('defis.add-books', $defi) }}" class="btn btn-primary btn-enhanced px-3 py-2" style="border-radius: 8px;">
            <i class="bx bx-plus me-2"></i>Ajouter des livres
          </a>
          <a href="{{ route('defis.edit', $defi) }}" class="btn btn-warning btn-enhanced px-3 py-2" style="border-radius: 8px;">
            <i class="bx bx-edit me-2"></i>Modifier
          </a>
        </div>
      </div>
    </div>
    <div class="card-body">
      @if($defi->description)
        <p class="mb-4 text-muted">{{ $defi->description }}</p>
      @endif
      <div class="row">
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de début</small>
            <span class="fw-medium">{{ $defi->date_debut ? \Carbon\Carbon::parse($defi->date_debut)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex flex-column">
            <small class="text-muted mb-1">Date de fin</small>
            <span class="fw-medium">{{ $defi->date_fin ? \Carbon\Carbon::parse($defi->date_fin)->translatedFormat('d M Y') : 'Non définie' }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Livres associés -->
  <div class="card enhanced-card">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold text-dark">Livres associés ({{ $defi->livres->count() }})</h5>
        <a href="{{ route('defis.add-books', $defi) }}" class="btn btn-primary btn-enhanced px-3 py-2" style="border-radius: 8px;">
          <i class="bx bx-plus me-2"></i>Ajouter des livres
        </a>
      </div>
    </div>
    
    @if($defi->livres->count() > 0)
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 book-events-table">
            <thead>
              <tr>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">LIVRE</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">AUTEUR</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4" style="font-size: 0.75rem; letter-spacing: 0.5px;">PROPRIÉTAIRE</th>
                <th class="text-uppercase small fw-semibold text-muted py-3 px-4 text-end" style="font-size: 0.75rem; letter-spacing: 0.5px;">ACTIONS</th>
              </tr>
            </thead>
            <tbody>
              @foreach($defi->livres as $livre)
                <tr class="border-bottom">
                  <td class="py-3 px-4">
                    <div class="d-flex align-items-center">
                      <div class="me-3" style="width: 48px; height: 48px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        @if($livre->cover_image && file_exists(public_path($livre->cover_image)))
                          <img src="/{{ $livre->cover_image }}" alt="cover" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                          <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="bx bx-book text-white" style="font-size: 20px;"></i>
                          </div>
                        @endif
                      </div>
                      <div class="d-flex flex-column">
                        <span class="fw-semibold text-dark mb-1" style="font-size: 0.95rem;">{{ $livre->title }}</span>
                        <small class="text-muted" style="font-size: 0.8rem;">{{ $livre->genre ?: 'Genre non spécifié' }}</small>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->author ?: 'Auteur non spécifié' }}</span>
                  </td>
                  <td class="py-3 px-4">
                    <span class="fw-medium text-dark" style="font-size: 0.9rem;">{{ $livre->user->name ?? 'Utilisateur inconnu' }}</span>
                  </td>
                  <td class="py-3 px-4 text-end">
                    <form action="{{ route('defis.remove-book', [$defi, $livre]) }}" method="POST" class="d-inline" onsubmit="return confirm('Retirer ce livre du défi ?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm px-3 py-2" style="background-color: #f44336; color: white; border-radius: 6px; font-size: 0.8rem; font-weight: 500; border: none;">
                        <i class="bx bx-trash me-1"></i>Retirer
                      </button>
                    </form>
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
          <i class="bx bx-book mb-3" style="font-size: 3rem; color: #dee2e6;"></i>
          <h6 class="text-muted mb-2">Aucun livre associé</h6>
          <p class="text-muted mb-3" style="font-size: 0.9rem;">Ajoutez des livres à ce défi pour commencer</p>
          <a href="{{ route('defis.add-books', $defi) }}" class="btn btn-primary px-4 py-2" style="border-radius: 8px;">
            <i class="bx bx-plus me-2"></i>Ajouter des livres
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
  .btn[style*="background-color: #f44336"]:hover {
    background-color: #d32f2f !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(244, 67, 54, 0.3);
  }
</style>
@endsection


