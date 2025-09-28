@extends('layouts/contentNavbarLayout')

@section('title', 'Détails Avis - Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails de l'Avis #{{ $avis->id }}</h5>
                    <a href="{{ route('admin.avis.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Informations Utilisateur</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary fs-3">
                                                {{ substr($avis->utilisateur->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $avis->utilisateur->name }}</h5>
                                            <p class="text-muted mb-0">{{ $avis->utilisateur->email }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">ID Utilisateur</small>
                                            <p class="mb-0">{{ $avis->utilisateur->id }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Rôle</small>
                                            <p class="mb-0">
                                                <span class="badge bg-label-{{ $avis->utilisateur->role === 'admin' ? 'danger' : 'primary' }}">
                                                    {{ ucfirst($avis->utilisateur->role) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Informations Livre</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            <span class="avatar-initial rounded-circle bg-label-success fs-3">
                                                <i class="bx bx-book"></i>
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $avis->livre->title }}</h5>
                                            <p class="text-muted mb-0">ID: {{ $avis->livre->id }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Total Avis</small>
                                            <p class="mb-0">{{ $avis->livre->avis->count() }}</p>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Note Moyenne</small>
                                            <p class="mb-0">
                                                @if($avis->livre->avis->count() > 0)
                                                    {{ number_format($avis->livre->avis->avg('note'), 1) }}/5
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Détails de l'Avis</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <small class="text-muted">Note</small>
                                            <div class="d-flex align-items-center mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $avis->note)
                                                        <i class="bx bxs-star text-warning fs-4"></i>
                                                    @else
                                                        <i class="bx bx-star text-muted fs-4"></i>
                                                    @endif
                                                @endfor
                                                <span class="ms-2 fs-5 fw-semibold">({{ $avis->note }}/5)</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Date de Publication</small>
                                            <p class="mb-0 mt-1">
                                                <span class="badge bg-label-info fs-6">
                                                    {{ $avis->date_publication->format('d/m/Y') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Date de Création</small>
                                            <p class="mb-0 mt-1">
                                                <span class="badge bg-label-secondary fs-6">
                                                    {{ $avis->created_at->format('d/m/Y H:i') }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted">Dernière Modification</small>
                                            <p class="mb-0 mt-1">
                                                <span class="badge bg-label-warning fs-6">
                                                    {{ $avis->updated_at->format('d/m/Y H:i') }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Commentaire</small>
                                        <div class="mt-2 p-3 bg-light rounded">
                                            <p class="mb-0">{{ $avis->commentaire }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
