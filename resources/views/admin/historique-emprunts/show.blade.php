@extends('layouts.contentNavbarLayout')

@section('title', 'Détails Historique Emprunt - Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bx bx-history me-2"></i>Détails de l'Historique #{{ $historiqueEmprunt->id }}
                    </h5>
                    <a href="{{ route('admin.historique-emprunts.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i>Retour à la liste
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Historique Details -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Informations de l'Historique</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td><span class="badge bg-secondary">#{{ $historiqueEmprunt->id }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Action:</strong></td>
                                            <td>
                                                @if($historiqueEmprunt->action === 'Emprunt automatique' || $historiqueEmprunt->action === 'Création')
                                                    <span class="badge bg-success">
                                                        <i class="bx bx-plus-circle me-1"></i>{{ $historiqueEmprunt->action }}
                                                    </span>
                                                @elseif($historiqueEmprunt->action === 'Retour')
                                                    <span class="badge bg-warning">
                                                        <i class="bx bx-undo me-1"></i>{{ $historiqueEmprunt->action }}
                                                    </span>
                                                @elseif($historiqueEmprunt->action === 'Modification')
                                                    <span class="badge bg-info">
                                                        <i class="bx bx-edit me-1"></i>{{ $historiqueEmprunt->action }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="bx bx-trash me-1"></i>{{ $historiqueEmprunt->action }}
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date Action:</strong></td>
                                            <td>
                                                <div>
                                                    <span class="fw-medium">{{ $historiqueEmprunt->date_action->format('d/m/Y') }}</span>
                                                    <br>
                                                    <small class="text-muted">{{ $historiqueEmprunt->date_action->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Détails:</strong></td>
                                            <td>{{ $historiqueEmprunt->details ?? 'Aucun détail' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Informations Utilisateur</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            <span class="avatar-initial rounded-circle bg-primary fs-4">
                                                {{ substr($historiqueEmprunt->utilisateur->name ?? 'U', 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $historiqueEmprunt->utilisateur->name ?? 'N/A' }}</h6>
                                            <small class="text-muted">{{ $historiqueEmprunt->utilisateur->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>ID Utilisateur:</strong></td>
                                            <td><span class="badge bg-primary">#{{ $historiqueEmprunt->utilisateur->id ?? 'N/A' }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $historiqueEmprunt->utilisateur->email ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rôle:</strong></td>
                                            <td>
                                                @if($historiqueEmprunt->utilisateur->role ?? false)
                                                    <span class="badge bg-{{ $historiqueEmprunt->utilisateur->role === 'admin' ? 'danger' : ($historiqueEmprunt->utilisateur->role === 'contributor' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($historiqueEmprunt->utilisateur->role) }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">User</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Membre depuis:</strong></td>
                                            <td>{{ $historiqueEmprunt->utilisateur->created_at ? $historiqueEmprunt->utilisateur->created_at->format('d/m/Y') : 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emprunt Information -->
                    @if($historiqueEmprunt->emprunt)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">Informations de l'Emprunt</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>ID Emprunt:</strong></td>
                                                        <td><span class="badge bg-info">#{{ $historiqueEmprunt->emprunt->id }}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Livre:</strong></td>
                                                        <td>
                                                            <div>
                                                                <h6 class="mb-0">{{ $historiqueEmprunt->emprunt->livre->title ?? 'N/A' }}</h6>
                                                                <small class="text-muted">Auteur: {{ $historiqueEmprunt->emprunt->livre->author ?? 'N/A' }}</small>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date Emprunt:</strong></td>
                                                        <td>{{ $historiqueEmprunt->emprunt->date_emprunt ? $historiqueEmprunt->emprunt->date_emprunt->format('d/m/Y H:i') : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date Retour Prévue:</strong></td>
                                                        <td>{{ $historiqueEmprunt->emprunt->date_retour_prev ? $historiqueEmprunt->emprunt->date_retour_prev->format('d/m/Y H:i') : 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <td><strong>Statut:</strong></td>
                                                        <td>
                                                            <span class="badge bg-{{ $historiqueEmprunt->emprunt->statut === 'emprunté' ? 'success' : ($historiqueEmprunt->emprunt->statut === 'retourné' ? 'info' : 'warning') }}">
                                                                {{ ucfirst($historiqueEmprunt->emprunt->statut) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date Retour Effectif:</strong></td>
                                                        <td>{{ $historiqueEmprunt->emprunt->date_retour_eff ? $historiqueEmprunt->emprunt->date_retour_eff->format('d/m/Y H:i') : 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Pénalité:</strong></td>
                                                        <td>{{ $historiqueEmprunt->emprunt->penalite ?? 0 }} €</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Commentaire:</strong></td>
                                                        <td>{{ $historiqueEmprunt->emprunt->commentaire ?? 'Aucun commentaire' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <a href="{{ route('emprunts.show', $historiqueEmprunt->emprunt) }}" class="btn btn-primary">
                                                <i class="bx bx-show me-1"></i>Voir l'emprunt complet
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

