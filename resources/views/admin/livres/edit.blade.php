@extends('layouts/contentNavbarLayout')

@section('title', 'Modifier le Livre')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Modifier le Livre</h5>
                    <div>
                        <a href="{{ route('admin.livres.show', $livre) }}" class="btn btn-info me-2">
                            <i class="bx bx-show me-1"></i> Voir
                        </a>
                        <a href="{{ route('admin.livres.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.livres.update', $livre) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Titre du livre *</label>
                                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $livre->title) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Auteur *</label>
                                    <input type="text" class="form-control" id="author" name="author" value="{{ old('author', $livre->author) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" value="{{ old('isbn', $livre->isbn) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edition" class="form-label">Édition</label>
                                    <input type="text" class="form-control" id="edition" name="edition" value="{{ old('edition', $livre->edition) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="publication_date" class="form-label">Date de publication</label>
                                    <input type="date" class="form-control" id="publication_date" name="publication_date" value="{{ old('publication_date', $livre->publication_date ? $livre->publication_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pages" class="form-label">Nombre de pages</label>
                                    <input type="number" class="form-control" id="pages" name="pages" value="{{ old('pages', $livre->pages) }}" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="categorie_id" class="form-label">Catégorie</label>
                                    <select class="form-select" id="categorie_id" name="categorie_id">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach(\App\Models\Categorie::all() as $categorie)
                                            <option value="{{ $categorie->id }}" {{ old('categorie_id', $livre->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                                {{ $categorie->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bibliotheque_id" class="form-label">Bibliothèque *</label>
                                    <select class="form-select" id="bibliotheque_id" name="bibliotheque_id" required>
                                        <option value="">Sélectionner une bibliothèque</option>
                                        @foreach(\App\Models\BibliothequeVirtuelle::all() as $bibliotheque)
                                            <option value="{{ $bibliotheque->id }}" {{ old('bibliotheque_id', $livre->bibliotheque_id) == $bibliotheque->id ? 'selected' : '' }}>
                                                {{ $bibliotheque->nom }} ({{ $bibliotheque->user->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $livre->description) }}</textarea>
                        </div>

                        @if($livre->cover_image)
                            <div class="mb-3">
                                <label class="form-label">Image de couverture actuelle</label>
                                <div>
                                    <img src="{{ asset('storage/' . $livre->cover_image) }}" alt="{{ $livre->title }}" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cover_image" class="form-label">Nouvelle image de couverture</label>
                                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                                    <small class="form-text text-muted">Laissez vide pour conserver l'image actuelle</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="file_path" class="form-label">Nouveau fichier PDF</label>
                                    <input type="file" class="form-control" id="file_path" name="file_path" accept=".pdf">
                                    <small class="form-text text-muted">Laissez vide pour conserver le fichier actuel</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="disponibilite" name="disponibilite" value="1" {{ old('disponibilite', $livre->disponibilite) ? 'checked' : '' }}>
                                <label class="form-check-label" for="disponibilite">
                                    Livre disponible
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
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
