@extends('layouts/contentNavbarLayout')

@section('title', 'Détails du Livre')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Détails du Livre</h5>
                    <div>
                        <a href="{{ route('admin.livres.edit', $livre) }}" class="btn btn-warning me-2">
                            <i class="bx bx-edit me-1"></i> Modifier
                        </a>
                        <a href="{{ route('admin.livres.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($livre->cover_image)
                                <img src="{{ asset('storage/' . $livre->cover_image) }}" alt="{{ $livre->title }}" class="img-fluid rounded mb-3">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="bx bx-book display-1 text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $livre->title }}</h3>
                            <p class="text-muted mb-3">par {{ $livre->author }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>ISBN:</strong> {{ $livre->isbn ?? 'N/A' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Édition:</strong> {{ $livre->edition ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Date de publication:</strong> 
                                    {{ $livre->publication_date ? $livre->publication_date->format('d/m/Y') : 'N/A' }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Pages:</strong> {{ $livre->pages ?? 'N/A' }}
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Catégorie:</strong> 
                                    @if($livre->categorie)
                                        <span class="badge bg-primary">{{ $livre->categorie->name }}</span>
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <strong>Disponibilité:</strong>
                                    @if($livre->disponibilite)
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Indisponible</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Bibliothèque:</strong> 
                                    @if($livre->bibliotheque)
                                        <a href="{{ route('admin.bibliotheques.show', $livre->bibliotheque) }}">
                                            {{ $livre->bibliotheque->nom }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <strong>Propriétaire:</strong> 
                                    @if($livre->bibliotheque && $livre->bibliotheque->user)
                                        {{ $livre->bibliotheque->user->name }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            
                            @if($livre->description)
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p class="mt-2">{{ $livre->description }}</p>
                                </div>
                            @endif
                            
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <strong>Créé le:</strong> {{ $livre->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="col-sm-6">
                                    <strong>Modifié le:</strong> {{ $livre->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($livre->file_path)
                        <div class="mt-4">
                            <h5>Fichier PDF</h5>
                            <div class="d-flex align-items-center">
                                <i class="bx bx-file-pdf text-danger me-2"></i>
                                <span>{{ basename($livre->file_path) }}</span>
                                <a href="{{ asset('storage/' . $livre->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                                    <i class="bx bx-download me-1"></i> Télécharger
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($livre->avis && $livre->avis->count() > 0)
                        <div class="mt-4">
                            <h5>Avis ({{ $livre->avis->count() }})</h5>
                            <div class="row">
                                @foreach($livre->avis->take(3) as $avis)
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center mb-2">
                                                    <strong>{{ $avis->user->name }}</strong>
                                                    <div class="ms-auto">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $avis->rating)
                                                                <i class="bx bxs-star text-warning"></i>
                                                            @else
                                                                <i class="bx bx-star text-muted"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <p class="mb-0">{{ Str::limit($avis->comment, 100) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($livre->avis->count() > 3)
                                <p class="text-muted">Et {{ $livre->avis->count() - 3 }} autres avis...</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
