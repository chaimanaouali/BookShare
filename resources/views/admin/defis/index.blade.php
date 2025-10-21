@extends('layouts/contentNavbarLayout')

@section('title', 'Gestion des Défis')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Gestion des Défis</h5>
                    <a href="{{ route('defis.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Créer un Défi
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($defis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Titre</th>
                                        <th>Type</th>
                                        <th>Date Début</th>
                                        <th>Date Fin</th>
                                        <th>Livres Requis</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($defis as $defi)
                                        <tr>
                                            <td>{{ $defi->id }}</td>
                                            <td>{{ $defi->titre }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($defi->type_defi) }}</span>
                                            </td>
                                            <td>{{ $defi->date_debut->format('d/m/Y') }}</td>
                                            <td>{{ $defi->date_fin->format('d/m/Y') }}</td>
                                            <td>{{ $defi->nombre_livres_requis }}</td>
                                            <td>
                                                @if($defi->actif)
                                                    <span class="badge bg-success">Actif</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('defis.show', $defi) }}">
                                                            <i class="bx bx-show me-1"></i> Voir
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('defis.edit', $defi) }}">
                                                            <i class="bx bx-edit-alt me-1"></i> Modifier
                                                        </a>
                                                        <form method="POST" action="{{ route('defis.destroy', $defi) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce défi ?')">
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

                        <div class="d-flex justify-content-center">
                            {{ $defis->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bx bx-flag display-1 text-muted"></i>
                            <h5 class="mt-3">Aucun défi trouvé</h5>
                            <p class="text-muted">Commencez par créer votre premier défi.</p>
                            <a href="{{ route('defis.create') }}" class="btn btn-primary">
                                <i class="bx bx-plus me-1"></i> Créer un Défi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection