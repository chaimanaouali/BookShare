@extends('front.layouts.app')

@section('title', 'Mon Historique d\'Emprunts')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>BookShare</h6>
                            <h2>Mon <em>Historique</em> d'<span>Emprunts</span></h2>
                            <p>Consultez l'historique complet de vos emprunts de livres et suivez toutes vos actions.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="historique emprunts">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="historique-emprunts" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-heading">
                    <h2>Mon Historique d'Emprunts</h2>
                    <p>Consultez toutes les actions effectuées sur vos emprunts</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="card" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px 10px 0 0;">
                        <h5 class="mb-0">
                            <i class="fa fa-history me-2"></i>Historique des Emprunts
                        </h5>
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
                                        <th>Emprunt</th>
                                        <th>Livre</th>
                                        <th>Action</th>
                                        <th>Date Action</th>
                                        <th>Détails</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($historiqueEmprunts as $historique)
                                        <tr>
                                            <td>
                                                <a href="{{ route('emprunts.show', $historique->emprunt) }}" class="text-decoration-none" style="color: #667eea; font-weight: 500;">
                                                    Emprunt #{{ $historique->emprunt->id }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $historique->emprunt->livre->title ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                @if($historique->action === 'Création')
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-plus-circle"></i> {{ $historique->action }}
                                                    </span>
                                                @elseif($historique->action === 'Modification')
                                                    <span class="badge bg-warning">
                                                        <i class="fa fa-edit"></i> {{ $historique->action }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fa fa-trash"></i> {{ $historique->action }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $historique->date_action->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <span class="text-muted">{{ Str::limit($historique->details, 50) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('historique-emprunts.show', $historique) }}" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Voir
                                                    </a>
                                                    <a href="{{ route('historique-emprunts.edit', $historique) }}" class="btn btn-sm btn-warning">
                                                        <i class="fa fa-edit"></i> Modifier
                                                    </a>
                                                    <form action="{{ route('historique-emprunts.destroy', $historique) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée d\'historique ?')">
                                                            <i class="fa fa-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fa fa-history" style="font-size: 48px; color: #ccc;"></i>
                                                <p class="mt-3 text-muted">Aucun historique d'emprunt trouvé</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($historiqueEmprunts->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $historiqueEmprunts->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Section -->
        <div class="row mt-5">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fa fa-plus-circle" style="font-size: 48px; color: #28a745;"></i>
                        </div>
                        <h3 class="mb-0">{{ $historiqueEmprunts->where('action', 'Création')->count() }}</h3>
                        <p class="text-muted mb-0">Créations</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fa fa-edit" style="font-size: 48px; color: #ffc107;"></i>
                        </div>
                        <h3 class="mb-0">{{ $historiqueEmprunts->where('action', 'Modification')->count() }}</h3>
                        <p class="text-muted mb-0">Modifications</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card text-center" style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: none; border-radius: 10px;">
                    <div class="card-body">
                        <div class="mb-3">
                            <i class="fa fa-trash" style="font-size: 48px; color: #dc3545;"></i>
                        </div>
                        <h3 class="mb-0">{{ $historiqueEmprunts->where('action', 'Suppression')->count() }}</h3>
                        <p class="text-muted mb-0">Suppressions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
