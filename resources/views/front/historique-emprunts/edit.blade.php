@extends('front.layouts.app')

@section('title', 'Modifier l\'Historique')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Modifier <em>l'Historique</em> d'<span>Emprunt</span></h2>
                            <p>Modifiez les informations de cette entrée d'historique.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="modifier historique">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="edit-historique" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Modifier l'Entrée d'Historique</h2>
                    <p>Mettez à jour les informations de cette action</p>
                </div>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fa fa-edit me-2"></i>Formulaire de Modification
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('historique-emprunts.update', $historiqueEmprunt) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="emprunt_id" class="form-label">Emprunt <span class="text-danger">*</span></label>
                                <select name="emprunt_id" id="emprunt_id" class="form-select" required disabled>
                                    <option value="{{ $historiqueEmprunt->emprunt->id }}">
                                        Emprunt #{{ $historiqueEmprunt->emprunt->id }} - {{ $historiqueEmprunt->emprunt->livre->title ?? 'N/A' }}
                                    </option>
                                </select>
                                <input type="hidden" name="emprunt_id" value="{{ $historiqueEmprunt->emprunt_id }}">
                                <small class="text-muted">L'emprunt associé ne peut pas être modifié</small>
                            </div>

                            <input type="hidden" name="utilisateur_id" value="{{ auth()->id() }}">

                            <div class="mb-3">
                                <label for="action" class="form-label">Action <span class="text-danger">*</span></label>
                                <select name="action" id="action" class="form-select" required>
                                    <option value="Création" {{ $historiqueEmprunt->action === 'Création' ? 'selected' : '' }}>Création</option>
                                    <option value="Modification" {{ $historiqueEmprunt->action === 'Modification' ? 'selected' : '' }}>Modification</option>
                                    <option value="Suppression" {{ $historiqueEmprunt->action === 'Suppression' ? 'selected' : '' }}>Suppression</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="date_action" class="form-label">Date de l'Action <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date_action" id="date_action" class="form-control" 
                                       value="{{ $historiqueEmprunt->date_action->format('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="details" class="form-label">Détails</label>
                                <textarea name="details" id="details" class="form-control" rows="4" 
                                          placeholder="Ajoutez des détails sur cette action...">{{ old('details', $historiqueEmprunt->details) }}</textarea>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <a href="{{ route('historique-emprunts.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                    <i class="fa fa-save"></i> Enregistrer les Modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Warning Info Box -->
                <div class="alert alert-info mt-4" style="border-radius: 10px;">
                    <h5><i class="fa fa-info-circle"></i> Information</h5>
                    <p class="mb-0">La modification de cette entrée d'historique mettra à jour les informations de cette action spécifique sans affecter l'emprunt associé.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
