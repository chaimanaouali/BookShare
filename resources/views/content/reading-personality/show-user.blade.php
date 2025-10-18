@extends('layouts/contentNavbarLayout')

@section('title', 'Profil de Lecture - {{ $user->name }}')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/apex-charts/apex-charts.js')}}"></script>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="bx bx-brain me-2"></i>Profil de Lecture - {{ $user->name }}
                    </h4>
                    <a href="{{ route('reading-personality.show') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-arrow-back me-1"></i>Mon Profil
                    </a>
                </div>
                <div class="card-body">
                    @if(!$hasEnoughHistory)
                        <div class="text-center py-5">
                            <i class="bx bx-book-open display-1 text-muted"></i>
                            <h4 class="mt-3">Pas assez d'historique</h4>
                            <p class="text-muted">{{ $user->name }} doit avoir emprunté au moins 3 livres pour générer un profil de lecture.</p>
                        </div>
                    @elseif(!$personality)
                        <div class="text-center py-5">
                            <i class="bx bx-brain display-1 text-primary"></i>
                            <h4 class="mt-3">Profil non généré</h4>
                            <p class="text-muted">{{ $user->name }} n'a pas encore généré son profil de lecture.</p>
                        </div>
                    @else
                        <div class="row">
                            <!-- Main Personality Card -->
                            <div class="col-lg-8">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">
                                            <i class="bx bx-user-circle me-2"></i>Personnalité de Lecteur
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <h3 class="text-primary mb-3">{{ $personality->personality_title }}</h3>
                                        <p class="lead">{{ $personality->personality_description }}</p>
                                        
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-tag me-1"></i>Genres Préférés</h6>
                                                <div class="mb-3">
                                                    @if($personality->reading_patterns['favorite_genres'] ?? false)
                                                        @foreach($personality->reading_patterns['favorite_genres'] as $genre)
                                                            <span class="badge bg-primary me-1">{{ $genre }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Aucun genre préféré identifié</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-palette me-1"></i>Thèmes de Lecture</h6>
                                                <div class="mb-3">
                                                    @if($personality->reading_patterns['reading_themes'] ?? false)
                                                        @foreach($personality->reading_patterns['reading_themes'] as $theme)
                                                            <span class="badge bg-info me-1">{{ $theme }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">Aucun thème identifié</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-timer me-1"></i>Style de Lecture</h6>
                                                <p class="text-muted">{{ $personality->reading_patterns['reading_style'] ?? 'Non spécifié' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6><i class="bx bx-trending-up me-1"></i>Comportement d'Emprunt</h6>
                                                <p class="text-muted">{{ $personality->reading_patterns['borrowing_behavior'] ?? 'Non spécifié' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Recommendations & Challenge -->
                            <div class="col-lg-4">
                                <!-- Recommendations -->
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-star me-1"></i>Suggestions de Lecture
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            @if($personality->recommendations)
                                                @foreach($personality->recommendations as $recommendation)
                                                    <li class="list-group-item d-flex align-items-center">
                                                        <i class="bx bx-book me-2"></i>
                                                        {{ $recommendation }}
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="list-group-item text-muted">Aucune suggestion disponible</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                
                                <!-- Challenge -->
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="bx bx-target-lock me-1"></i>Défi Suggéré
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">{{ $personality->challenge_suggestion ?? 'Aucun défi suggéré' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Metadata -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Utilisateur</h6>
                                                <h6 class="text-primary">{{ $user->name }}</h6>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Livres Analysés</h6>
                                                <h4 class="text-primary">{{ $personality->books_analyzed }}</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Dernière Mise à Jour</h6>
                                                <h6 class="text-muted">{{ $personality->last_updated ? $personality->last_updated->format('d/m/Y') : 'Jamais' }}</h6>
                                            </div>
                                            <div class="col-md-3">
                                                <h6 class="text-muted">Statut</h6>
                                                <span class="badge bg-success">Profil Généré</span>
                                            </div>
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
