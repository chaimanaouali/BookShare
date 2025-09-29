@extends('front.layouts.app')

@section('title', 'Mes Emprunts')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Gestion des <em>Emprunts</em> de <span>Livres</span></h2>
                            <p>Gérez facilement vos emprunts de livres avec notre système de gestion intégré.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="book management">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="emprunts" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Mes Emprunts</h2>
                    <p>Consultez et gérez vos emprunts de livres</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">Liste des Emprunts</h5>
                        <a href="{{ route('emprunts.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                            <i class="fa fa-plus"></i> Nouvel Emprunt
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Livre</th>
                                        <th>Date Emprunt</th>
                                        <th>Date Retour Prévue</th>
                                        <th>Date Retour Effective</th>
                                        <th>Statut</th>
                                        <th>Pénalité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($emprunts as $emprunt)
                                        <tr>
                                            <td>{{ $emprunt->id }}</td>
                                            <td>{{ $emprunt->livre->title ?? 'N/A' }}</td>
                                            <td>{{ $emprunt->date_emprunt->format('d/m/Y') }}</td>
                                            <td>{{ $emprunt->date_retour_prev->format('d/m/Y') }}</td>
                                            <td>{{ $emprunt->date_retour_eff ? $emprunt->date_retour_eff->format('d/m/Y') : 'Non retourné' }}</td>
                                            <td>
                                                <span class="badge bg-{{ $emprunt->statut === 'En cours' ? 'warning' : ($emprunt->statut === 'Retourné' ? 'success' : 'danger') }}">
                                                    {{ $emprunt->statut }}
                                                </span>
                                            </td>
                                            <td>{{ number_format($emprunt->penalite, 2) }} €</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('emprunts.show', $emprunt) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                    <a href="{{ route('emprunts.edit', $emprunt) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('emprunts.destroy', $emprunt) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet emprunt ?')">
                                                            <i class="fa fa-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Aucun emprunt trouvé</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $emprunts->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
