@extends('front.layouts.app')

@section('title', 'Détails de l\'Emprunt')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Détails de l'<em>Emprunt</em></h2>
                            <p>Consultez les détails complets de votre emprunt de livre.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="emprunt details">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="emprunt-details" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Détails de l'Emprunt</h2>
                    <p>Informations complètes sur votre emprunt</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informations de l'Emprunt</h5>
                        <div>
                            <a href="{{ route('emprunts.edit', $emprunt) }}" class="btn btn-primary">
                                <i class="fa fa-edit"></i> Modifier
                            </a>
                            <a href="{{ route('emprunts.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Informations de l'Emprunt</h6>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Utilisateur:</strong></td>
                                        <td>{{ $emprunt->utilisateur->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Livre:</strong></td>
                                        <td>{{ $emprunt->livre->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date d'Emprunt:</strong></td>
                                        <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de Retour Prévue:</strong></td>
                                        <td>{{ $emprunt->date_retour_prev->format('d/m/Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date de Retour Effective:</strong></td>
                                        <td>{{ $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('d/m/Y') : 'Non retourné' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Statut:</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $emprunt->statut === 'En cours' ? 'warning' : ($emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                                {{ $emprunt->statut }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Pénalité:</strong></td>
                                        <td>{{ number_format($emprunt->penalite, 2) }} €</td>
                                    </tr>
                                    @if($emprunt->commentaire)
                                    <tr>
                                        <td><strong>Commentaire:</strong></td>
                                        <td>{{ $emprunt->commentaire }}</td>
                                    </tr>
                                    @endif
                                </table>
                                @if($emprunt->statut === 'emprunté' && !empty($emprunt->livre->fichier_livre))
                                    <a href="{{ Storage::url($emprunt->livre->fichier_livre) }}" target="_blank" class="btn btn-success mt-3">
                                        <i class="fa fa-book-open"></i> Read Book
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6>Historique des Actions</h6>
                                @if($emprunt->historiqueEmprunts->count() > 0)
                                    <div class="timeline">
                                        @foreach($emprunt->historiqueEmprunts->sortByDesc('date_action') as $historique)
                                            <div class="timeline-item">
                                                <div class="timeline-marker"></div>
                                                <div class="timeline-content">
                                                    <h6 class="timeline-title">{{ $historique->action }}</h6>
                                                    <p class="timeline-text">{{ $historique->details }}</p>
                                                    <small class="text-muted">
                                                        Par {{ $historique->utilisateur->name ?? 'N/A' }} 
                                                        le {{ $historique->date_action->format('d/m/Y à H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Aucun historique disponible</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -8px;
    top: 5px;
    width: 12px;
    height: 12px;
    background-color: #007bff;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #007bff;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
    color: #6c757d;
}
</style>
@endsection
