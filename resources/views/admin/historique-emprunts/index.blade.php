@extends('layouts.contentNavbarLayout')

@section('title', 'Historique des Emprunts - Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-history me-2"></i>Historique des Emprunts - Tous les Utilisateurs
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="exportToCSV()">
                            <i class="bx bx-download me-1"></i> Exporter CSV
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
                            <i class="bx bx-refresh me-1"></i> Réinitialiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <form method="GET" action="{{ route('admin.historique-emprunts.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="user_id" class="form-label">Utilisateur</label>
                                    <select name="user_id" id="user_id" class="form-select">
                                        <option value="">Tous les utilisateurs</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="action" class="form-label">Action</label>
                                    <select name="action" id="action" class="form-select">
                                        <option value="">Toutes les actions</option>
                                        @foreach($actions as $action)
                                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                {{ $action }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">Date début</label>
                                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">Date fin</label>
                                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="search" class="form-label">Rechercher livre</label>
                                    <input type="text" name="search" id="search" class="form-control" placeholder="Titre du livre..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bx-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $historiqueEmprunts->total() }}</h4>
                                            <small>Total des actions</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-history fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $historiqueEmprunts->where('action', 'Emprunt automatique')->count() + $historiqueEmprunts->where('action', 'Création')->count() }}</h4>
                                            <small>Emprunts</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-book-add fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $historiqueEmprunts->where('action', 'Retour')->count() }}</h4>
                                            <small>Retours</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-undo fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4 class="mb-0">{{ $historiqueEmprunts->unique('utilisateur_id')->count() }}</h4>
                                            <small>Utilisateurs actifs</small>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Livre</th>
                                    <th>Action</th>
                                    <th>Date Action</th>
                                    <th>Détails</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historiqueEmprunts as $historique)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary">#{{ $historique->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-primary">
                                                        {{ substr($historique->utilisateur->name ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $historique->utilisateur->name ?? 'N/A' }}</h6>
                                                    <small class="text-muted">{{ $historique->utilisateur->email ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $historique->emprunt->livre->title ?? 'N/A' }}</h6>
                                                <small class="text-muted">Emprunt #{{ $historique->emprunt->id ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($historique->action === 'Emprunt automatique' || $historique->action === 'Création')
                                                <span class="badge bg-success">
                                                    <i class="bx bx-plus-circle me-1"></i>{{ $historique->action }}
                                                </span>
                                            @elseif($historique->action === 'Retour')
                                                <span class="badge bg-warning">
                                                    <i class="bx bx-undo me-1"></i>{{ $historique->action }}
                                                </span>
                                            @elseif($historique->action === 'Modification')
                                                <span class="badge bg-info">
                                                    <i class="bx bx-edit me-1"></i>{{ $historique->action }}
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bx bx-trash me-1"></i>{{ $historique->action }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <div>
                                                <span class="fw-medium">{{ $historique->date_action->format('d/m/Y') }}</span>
                                                <br>
                                                <small class="text-muted">{{ $historique->date_action->format('H:i:s') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ Str::limit($historique->details, 50) }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('admin.historique-emprunts.show', $historique) }}">
                                                            <i class="bx bx-show me-2"></i>Voir détails
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('emprunts.show', $historique->emprunt) }}">
                                                            <i class="bx bx-book me-2"></i>Voir emprunt
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="bx bx-history" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="mt-3 text-muted">Aucun historique trouvé</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($historiqueEmprunts->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $historiqueEmprunts->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetFilters() {
    document.getElementById('user_id').value = '';
    document.getElementById('action').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('search').value = '';
    document.querySelector('form').submit();
}

function exportToCSV() {
    // Get current filters
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'csv');
    
    // Create download link
    const link = document.createElement('a');
    link.href = '{{ route("admin.historique-emprunts.index") }}?' + params.toString();
    link.download = 'historique-emprunts-' + new Date().toISOString().split('T')[0] + '.csv';
    link.click();
}
</script>
@endsection

