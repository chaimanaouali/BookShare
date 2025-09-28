@extends('layouts.contentNavbarLayout')

@section('title', 'Historique des Emprunts')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Historique des Emprunts</h5>
                    <a href="{{ route('historique-emprunts.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i> Nouvelle Entrée
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
                                    <th>Emprunt</th>
                                    <th>Utilisateur</th>
                                    <th>Action</th>
                                    <th>Date Action</th>
                                    <th>Détails</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($historiqueEmprunts as $historique)
                                    <tr>
                                        <td>{{ $historique->id }}</td>
                                        <td>
                                            <a href="{{ route('emprunts.show', $historique->emprunt) }}" class="text-decoration-none">
                                                Emprunt #{{ $historique->emprunt->id }}
                                            </a>
                                        </td>
                                        <td>{{ $historique->utilisateur->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $historique->action === 'Création' ? 'success' : ($historique->action === 'Modification' ? 'warning' : 'danger') }}">
                                                {{ $historique->action }}
                                            </span>
                                        </td>
                                        <td>{{ $historique->date_action->format('d/m/Y H:i') }}</td>
                                        <td>{{ Str::limit($historique->details, 50) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('historique-emprunts.show', $historique) }}">
                                                        <i class="bx bx-show me-1"></i> Voir
                                                    </a>
                                                    <a class="dropdown-item" href="{{ route('historique-emprunts.edit', $historique) }}">
                                                        <i class="bx bx-edit-alt me-1"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('historique-emprunts.destroy', $historique) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée d\'historique ?')">
                                                            <i class="bx bx-trash me-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun historique trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $historiqueEmprunts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
