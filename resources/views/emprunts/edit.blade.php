@extends('layouts.contentNavbarLayout')

@section('title', 'Modifier l\'Emprunt')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier l'Emprunt #{{ $emprunt->id }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('emprunts.update', $emprunt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="utilisateur_id" class="form-label">Utilisateur *</label>
                                <select class="form-select @error('utilisateur_id') is-invalid @enderror" id="utilisateur_id" name="utilisateur_id" required>
                                    <option value="">Sélectionner un utilisateur</option>
                                    @foreach($utilisateurs as $utilisateur)
                                        <option value="{{ $utilisateur->id }}" 
                                                {{ (old('utilisateur_id', $emprunt->utilisateur_id) == $utilisateur->id) ? 'selected' : '' }}>
                                            {{ $utilisateur->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('utilisateur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="livre_id" class="form-label">Livre *</label>
                                <select class="form-select @error('livre_id') is-invalid @enderror" id="livre_id" name="livre_id" required>
                                    <option value="">Sélectionner un livre</option>
                                    @foreach($livres as $livre)
                                        <option value="{{ $livre->id }}" 
                                                {{ (old('livre_id', $emprunt->livre_id) == $livre->id) ? 'selected' : '' }}>
                                            {{ $livre->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('livre_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_emprunt" class="form-label">Date d'Emprunt *</label>
                                <input type="date" class="form-control @error('date_emprunt') is-invalid @enderror" 
                                       id="date_emprunt" name="date_emprunt" 
                                       value="{{ old('date_emprunt', $emprunt->date_emprunt->format('Y-m-d')) }}" required>
                                @error('date_emprunt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_retour_prev" class="form-label">Date de Retour Prévue *</label>
                                <input type="date" class="form-control @error('date_retour_prev') is-invalid @enderror" 
                                       id="date_retour_prev" name="date_retour_prev" 
                                       value="{{ old('date_retour_prev', $emprunt->date_retour_prev->format('Y-m-d')) }}" required>
                                @error('date_retour_prev')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_retour_eff" class="form-label">Date de Retour Effective</label>
                                <input type="date" class="form-control @error('date_retour_eff') is-invalid @enderror" 
                                       id="date_retour_eff" name="date_retour_eff" 
                                       value="{{ old('date_retour_eff', $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('Y-m-d') : '') }}">
                                @error('date_retour_eff')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="statut" class="form-label">Statut *</label>
                                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                    <option value="">Sélectionner un statut</option>
                                    <option value="En cours" {{ (old('statut', $emprunt->statut) == 'En cours') ? 'selected' : '' }}>En cours</option>
                                    <option value="Retourné" {{ (old('statut', $emprunt->statut) == 'Retourné') ? 'selected' : '' }}>Retourné</option>
                                    <option value="En retard" {{ (old('statut', $emprunt->statut) == 'En retard') ? 'selected' : '' }}>En retard</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="penalite" class="form-label">Pénalité (€)</label>
                                <input type="number" step="0.01" min="0" class="form-control @error('penalite') is-invalid @enderror" 
                                       id="penalite" name="penalite" 
                                       value="{{ old('penalite', $emprunt->penalite) }}">
                                @error('penalite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="commentaire" class="form-label">Commentaire</label>
                                <textarea class="form-control @error('commentaire') is-invalid @enderror" 
                                          id="commentaire" name="commentaire" rows="3">{{ old('commentaire', $emprunt->commentaire) }}</textarea>
                                @error('commentaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('emprunts.index') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
