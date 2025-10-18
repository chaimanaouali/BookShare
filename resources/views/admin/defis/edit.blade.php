@extends('layouts/contentNavbarLayout')

@section('title', 'Modifier défi')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header"><h5 class="mb-0">Modifier le défi</h5></div>
    <div class="card-body">
      <form method="POST" action="{{ route('defis.update', $defi) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label class="form-label">Titre</label>
          <input name="titre" type="text" class="form-control" value="{{ old('titre', $defi->titre) }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4">{{ old('description', $defi->description) }}</textarea>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Date début</label>
            <input name="date_debut" type="date" class="form-control" value="{{ old('date_debut', $defi->date_debut) }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Date fin</label>
            <input name="date_fin" type="date" class="form-control" value="{{ old('date_fin', $defi->date_fin) }}">
          </div>
        </div>
        <div class="mt-4 d-flex gap-2">
          <button class="btn btn-primary" type="submit">Mettre à jour</button>
          <a href="{{ route('defis.index') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection


