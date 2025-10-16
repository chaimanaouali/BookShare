@extends('layouts.contentNavbarLayout')

@section('title', 'Gestion des Emprunts')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste des Emprunts</h5>
                    <a href="{{ route('emprunts.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nouvel Emprunt
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Livre</th>
                                    <th>Date Emprunt</th>
                                    <th>Date Retour Prévue</th>
                                    <th>Date Retour Effective</th>
                                    <th>Statut</th>
                                    <th>Pénalité</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($emprunts as $emprunt)
                                    <tr>
                                        <td>{{ $emprunt->id }}</td>
                                        <td>{{ $emprunt->utilisateur->name ?? 'N/A' }}</td>
                                        <td>{{ $emprunt->livre->title ?? 'N/A' }}</td>
                                        <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                        <td>{{ $emprunt->date_retour_prev->format('d/m/Y') }}</td>
                                        <td>{{ $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('d/m/Y') : 'Non retourné' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $emprunt->statut === 'En cours' ? 'warning' : ($emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                                {{ $emprunt->statut }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($emprunt->penalite, 2) }} €</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('emprunts.show', $emprunt) }}">
                                                        <i class="bx bx-show me-1"></i> Voir
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('emprunts.edit', $emprunt) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')">
                                                            <i class="bx bx-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun emprunt trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $emprunts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
