@extends('front.layouts.app')

@section('title', 'Détails de l\'Historique')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Détails de <em>l'Historique</em> d'<span>Emprunt</span></h2>
                            <p>Consultez les détails complets de cette action d'emprunt.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="historique details">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="historique-details" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Détails de l'Entrée d'Historique</h2>
                    <p>Informations complètes sur cette action</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fa fa-info-circle me-2"></i>Informations de l'Entrée
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Emprunt:</strong></td>
                                <td>
                                    <a href="{{ route('emprunts.show', $historiqueEmprunt->emprunt) }}" class="text-decoration-none" style="color: #667eea; font-weight: 500;">
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
                                    @if($historiqueEmprunt->action === 'Création')
                                        <span class="badge bg-success">
                                            <i class="fa fa-plus-circle"></i> {{ $historiqueEmprunt->action }}
                                        </span>
                                    @elseif($historiqueEmprunt->action === 'Modification')
                                        <span class="badge bg-warning">
                                            <i class="fa fa-edit"></i> {{ $historiqueEmprunt->action }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fa fa-trash"></i> {{ $historiqueEmprunt->action }}
                                        </span>
                                    @endif
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
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fa fa-book me-2"></i>Informations de l'Emprunt Associé
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($historiqueEmprunt->emprunt)
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Livre:</strong></td>
                                    <td>{{ $historiqueEmprunt->emprunt->livre->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Auteur:</strong></td>
                                    <td>{{ $historiqueEmprunt->emprunt->livre->author ?? 'N/A' }}</td>
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
                                    <td><strong>Date de Retour Effective:</strong></td>
                                    <td>{{ $historiqueEmprunt->emprunt->date_retour_eff ? $historiqueEmprunt->emprunt->date_retour_eff->format('d/m/Y') : 'Non retourné' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $historiqueEmprunt->emprunt->statut === 'En cours' ? 'warning' : ($historiqueEmprunt->emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                            {{ $historiqueEmprunt->emprunt->statut }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Pénalité:</strong></td>
                                    <td>{{ number_format($historiqueEmprunt->emprunt->penalite, 2) }} €</td>
                                </tr>
                            </table>
                        @else
                            <p class="text-muted">Aucune information d'emprunt disponible</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="text-center">
                    <a href="{{ route('historique-emprunts.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fa fa-arrow-left"></i> Retour à l'Historique
                    </a>
                    <a href="{{ route('emprunts.show', $historiqueEmprunt->emprunt) }}" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="fa fa-book"></i> Voir l'Emprunt
                    </a>
                    <a href="{{ route('historique-emprunts.edit', $historiqueEmprunt) }}" class="btn btn-warning btn-lg">
                        <i class="fa fa-edit"></i> Modifier
                    </a>
                    <form action="{{ route('historique-emprunts.destroy', $historiqueEmprunt) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée d\'historique ?')">
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
