@extends('layouts/contentNavbarLayout')

@section('title', 'Modifier Avis - Admin')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Modifier l'Avis #{{ $avis->id }}</h5>
                    <a href="{{ route('admin.avis.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Retour
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.avis.update', $avis) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="utilisateur_id" class="form-label">Utilisateur</label>
                                    <select class="form-select @error('utilisateur_id') is-invalid @enderror" 
                                            id="utilisateur_id" name="utilisateur_id" required>
                                        <option value="">Sélectionner un utilisateur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                    {{ old('utilisateur_id', $avis->user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('utilisateur_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="livre_id" class="form-label">Livre</label>
                                    <select class="form-select @error('livre_id') is-invalid @enderror" 
                                            id="livre_id" name="livre_id" required>
                                        <option value="">Sélectionner un livre</option>
                                        @foreach($livres as $livre)
                                            <option value="{{ $livre->id }}" 
                                                    {{ old('livre_id', $avis->livre_id) == $livre->id ? 'selected' : '' }}>
                                                {{ $livre->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('livre_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="note" class="form-label">Note</label>
                                    <select class="form-select @error('note') is-invalid @enderror" 
                                            id="note" name="note" required>
                                        <option value="">Sélectionner une note</option>
                                        @for($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" 
                                                    {{ old('note', $avis->note) == $i ? 'selected' : '' }}>
                                                {{ $i }} étoile{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date_publication" class="form-label">Date de Publication</label>
                                    <input type="date" 
                                           class="form-control @error('date_publication') is-invalid @enderror" 
                                           id="date_publication" 
                                           name="date_publication" 
                                           value="{{ old('date_publication', $avis->date_publication->format('Y-m-d')) }}">
                                    @error('date_publication')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="commentaire" class="form-label">Commentaire</label>
                            <textarea class="form-control @error('commentaire') is-invalid @enderror" 
                                      id="commentaire" 
                                      name="commentaire" 
                                      rows="5" 
                                      maxlength="1000" 
                                      required>{{ old('commentaire', $avis->commentaire) }}</textarea>
                            <div class="form-text">Maximum 1000 caractères</div>
                            @error('commentaire')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.avis.index') }}" class="btn btn-outline-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
