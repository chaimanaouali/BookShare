@extends('layouts.contentNavbarLayout')

@section('title', 'Détails de l\'Entrée d\'Historique')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de l'Entrée d'Historique #{{ $historiqueEmprunt->id }}</h5>
                    <div>
                        <a href="{{ route('historique-emprunts.edit', $historiqueEmprunt) }}" class="btn btn-primary">
                            <i class="bx bx-edit"></i> Modifier
                        </a>
                        <a href="{{ route('historique-emprunts.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations de l'Entrée</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $historiqueEmprunt->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Emprunt:</strong></td>
                                    <td>
                                        <a href="{{ route('emprunts.show', $historiqueEmprunt->emprunt) }}" class="text-decoration-none">
                                            Emprunt #{{ $historiqueEmprunt->emprunt->id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Utilisateur:</strong></td>
                                    <td>{{ $historiqueEmprunt->utilisateur->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Action:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $historiqueEmprunt->action === 'Création' ? 'success' : ($historiqueEmprunt->action === 'Modification' ? 'warning' : 'danger') }}">
                                            {{ $historiqueEmprunt->action }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Date de l'Action:</strong></td>
                                    <td>{{ $historiqueEmprunt->date_action->format('d/m/Y à H:i') }}</td>
                                </tr>
                                @if($historiqueEmprunt->details)
                                <tr>
                                    <td><strong>Détails:</strong></td>
                                    <td>{{ $historiqueEmprunt->details }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Informations de l'Emprunt Associé</h6>
                            @if($historiqueEmprunt->emprunt)
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Utilisateur de l'Emprunt:</strong></td>
                                        <td>{{ $historiqueEmprunt->emprunt->utilisateur->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Livre:</strong></td>
                                        <td>{{ $historiqueEmprunt->emprunt->livre->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date d'Emprunt:</strong></td>
                                        <td>{{ $historiqueEmprunt->emprunt->date_emprunt->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de Retour Prévue:</strong></td>
                                        <td>{{ $historiqueEmprunt->emprunt->date_retour_prev->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $historiqueEmprunt->emprunt->statut === 'En cours' ? 'warning' : ($historiqueEmprunt->emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                                {{ $historiqueEmprunt->emprunt->statut }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted">Emprunt non trouvé</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
