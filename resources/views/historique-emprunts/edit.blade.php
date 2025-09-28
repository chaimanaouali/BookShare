@extends('layouts.contentNavbarLayout')

@section('title', 'Modifier l\'Entrée d\'Historique')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier l'Entrée d'Historique #{{ $historiqueEmprunt->id }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('historique-emprunts.update', $historiqueEmprunt) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emprunt_id" class="form-label">Emprunt *</label>
                                <select class="form-select @error('emprunt_id') is-invalid @enderror" id="emprunt_id" name="emprunt_id" required>
                                    <option value="">Sélectionner un emprunt</option>
                                    @foreach($emprunts as $emprunt)
                                        <option value="{{ $emprunt->id }}" 
                                                {{ (old('emprunt_id', $historiqueEmprunt->emprunt_id) == $emprunt->id) ? 'selected' : '' }}>
                                            Emprunt #{{ $emprunt->id }} - {{ $emprunt->utilisateur->name ?? 'N/A' }} / {{ $emprunt->livre->title ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('emprunt_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="utilisateur_id" class="form-label">Utilisateur *</label>
                                <select class="form-select @error('utilisateur_id') is-invalid @enderror" id="utilisateur_id" name="utilisateur_id" required>
                                    <option value="">Sélectionner un utilisateur</option>
                                    @foreach($utilisateurs as $utilisateur)
                                        <option value="{{ $utilisateur->id }}" 
                                                {{ (old('utilisateur_id', $historiqueEmprunt->utilisateur_id) == $utilisateur->id) ? 'selected' : '' }}>
                                            {{ $utilisateur->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('utilisateur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="action" class="form-label">Action *</label>
                                <select class="form-select @error('action') is-invalid @enderror" id="action" name="action" required>
                                    <option value="">Sélectionner une action</option>
                                    <option value="Création" {{ (old('action', $historiqueEmprunt->action) == 'Création') ? 'selected' : '' }}>Création</option>
                                    <option value="Modification" {{ (old('action', $historiqueEmprunt->action) == 'Modification') ? 'selected' : '' }}>Modification</option>
                                    <option value="Suppression" {{ (old('action', $historiqueEmprunt->action) == 'Suppression') ? 'selected' : '' }}>Suppression</option>
                                    <option value="Retour" {{ (old('action', $historiqueEmprunt->action) == 'Retour') ? 'selected' : '' }}>Retour</option>
                                    <option value="Prolongation" {{ (old('action', $historiqueEmprunt->action) == 'Prolongation') ? 'selected' : '' }}>Prolongation</option>
                                    <option value="Pénalité" {{ (old('action', $historiqueEmprunt->action) == 'Pénalité') ? 'selected' : '' }}>Pénalité</option>
                                </select>
                                @error('action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="date_action" class="form-label">Date de l'Action *</label>
                                <input type="datetime-local" class="form-control @error('date_action') is-invalid @enderror" 
                                       id="date_action" name="date_action" 
                                       value="{{ old('date_action', $historiqueEmprunt->date_action->format('Y-m-d\TH:i')) }}" required>
                                @error('date_action')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="details" class="form-label">Détails</label>
                                <textarea class="form-control @error('details') is-invalid @enderror" 
                                          id="details" name="details" rows="4" 
                                          placeholder="Décrivez les détails de l'action...">{{ old('details', $historiqueEmprunt->details) }}</textarea>
                                @error('details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('historique-emprunts.index') }}" class="btn btn-secondary me-2">Annuler</a>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
