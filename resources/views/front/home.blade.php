@extends('front.layouts.app')

@section('title', 'BookShare - Partagez la Connaissance')

@section('content')
<div class="main-banner wow fadeIn" id="top" data-wow-duration="1s" data-wow-delay="0.5s">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-6 align-self-center">
                        <div class="left-content header-text wow fadeInLeft" data-wow-duration="1s" data-wow-delay="1s">
                            <h6>Bienvenue sur BookShare</h6>
                            <h2>Partageons les <em>Livres</em> &amp; <span>Connaissances</span></h2>
                            <p>BookShare est une plateforme collaborative qui met en relation les passionnés de lecture. Partagez vos livres, découvrez de nouveaux ouvrages et créons ensemble une communauté solidaire où la connaissance circule librement.</p>
                            <form id="search" action="#" method="GET">
                                <fieldset>
                                    <input type="text" name="book_search" class="email" placeholder="Rechercher un livre, un auteur..." autocomplete="on" required>
                                </fieldset>
                                <fieldset>
                                    <button type="submit" class="main-button">Rechercher</button>
                                </fieldset>
                            </form>
                            @auth
                            <div class="mt-3">
                                <a href="{{ route('recommendations.generate.get') }}" class="main-button" style="margin-right:10px">Générer des recommandations</a>
                                <a href="{{ route('recommendations.index') }}" class="main-button bordered">Voir mes recommandations</a>
                            </div>
                            @endauth
                            @guest
                            <div class="mt-3">
                                <a href="{{ route('login') }}" class="main-button" style="margin-right:10px">Se connecter pour générer</a>
                                <a href="{{ route('login') }}" class="main-button bordered">Voir mes recommandations</a>
                            </div>
                            @endguest
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="communauté de lecteurs">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mes défis en cours - Section pour utilisateurs connectés -->
@auth
    @php
        $userParticipations = \App\Models\ParticipationDefi::where('user_id', Auth::id())
            ->whereIn('status', ['en_cours', 'abandonne'])
            ->with(['defi', 'livre'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    @endphp
    
    @if($userParticipations->count() > 0)
        <div class="container py-5">
            <div class="row">
                <div class="col-12">
                    <div class="card enhanced-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">Mes défis en cours</h4>
                                    <p class="text-muted mb-0 mt-1">Continuez votre progression dans vos défis de lecture</p>
                                </div>
                                <a href="{{ route('participation-defis.my-participations') }}" class="btn btn-outline-primary btn-enhanced">
                                    <i class="bx bx-list-ul me-2"></i>Voir toutes mes participations
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($userParticipations as $participation)
                                    <div class="col-md-4">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="me-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);">
                                                        <div class="d-flex align-items-center justify-content-center h-100">
                                                            <i class="bx bx-trophy text-white" style="font-size: 20px;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold text-dark mb-1">{{ $participation->defi->titre }}</h6>
                                                        <p class="text-muted small mb-1">{{ $participation->livre->title }}</p>
                                                        <span class="badge bg-primary">{{ $participation->livre->format ?: 'PDF' }}</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Statut</small>
                                                    <span class="badge bg-info">En cours</span>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Progression</small>
                                                    <span class="fw-medium">
                                                        Commencé le {{ $participation->date_debut_lecture ? \Carbon\Carbon::parse($participation->date_debut_lecture)->translatedFormat('d M Y') : 'Non défini' }}
                                                    </span>
                                                </div>
                                                
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-primary btn-sm flex-grow-1" data-participation-id="{{ $participation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                                                        <i class="bx bx-eye me-1"></i>Voir
                                                    </button>
                                                    <button class="btn btn-success btn-sm" data-participation-id="{{ $participation->id }}" onclick="completeChallenge(this.dataset.participationId)">
                                                        <i class="bx bx-check me-1"></i>Terminer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mes défis terminés - Section pour utilisateurs connectés -->
    @php
        $completedParticipations = \App\Models\ParticipationDefi::where('user_id', Auth::id())
            ->where('status', 'termine')
            ->with(['defi', 'livre'])
            ->orderBy('quiz_completed_at', 'desc')
            ->limit(3)
            ->get();
    @endphp
    
    @if($completedParticipations->count() > 0)
        <div class="container py-3">
            <div class="row">
                <div class="col-12">
                    <div class="card enhanced-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0 fw-bold text-dark">Mes défis terminés</h4>
                                    <p class="text-muted mb-0 mt-1">Défis que vous avez complétés avec succès</p>
                                </div>
                                <a href="{{ route('participation-defis.my-participations') }}" class="btn btn-outline-success btn-enhanced">
                                    <i class="bx bx-trophy me-2"></i>Voir tous mes résultats
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                @foreach($completedParticipations as $participation)
                                    <div class="col-md-4">
                                        <div class="card border h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="me-3" style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                                        <div class="d-flex align-items-center justify-content-center h-100">
                                                            <i class="bx bx-trophy text-white" style="font-size: 20px;"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="card-title fw-bold text-dark mb-1">{{ $participation->defi->titre }}</h6>
                                                        <p class="text-muted small mb-1">{{ $participation->livre->title }}</p>
                                                        <span class="badge bg-success">Terminé</span>
                                                    </div>
                                                </div>
                                                
                                                @if($participation->quiz_score !== null)
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Score du Quiz</small>
                                                    <span class="badge bg-info">{{ $participation->quiz_score }}/{{ $participation->quiz_total_questions }}</span>
                                                </div>
                                                @endif
                                                
                                                <div class="mb-3">
                                                    <small class="text-muted d-block">Terminé le</small>
                                                    <span class="fw-medium">
                                                        {{ $participation->date_fin_lecture ? \Carbon\Carbon::parse($participation->date_fin_lecture)->translatedFormat('d M Y') : 'Non défini' }}
                                                    </span>
                                                </div>
                                                
                                                <div class="d-flex gap-2">
                                                    <button class="btn btn-success btn-sm flex-grow-1" data-participation-id="{{ $participation->id }}" onclick="showParticipationModal(this.dataset.participationId)">
                                                        <i class="bx bx-eye me-1"></i>Voir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth

<div id="about" class="about-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="left-image wow fadeIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <img src="{{ asset('assets/images/about-left-image.png') }}" alt="lecteur passionné">
                </div>
            </div>
            <div class="col-lg-8 align-self-center">
                <div class="services">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-01.png') }}" alt="partage de livres">
                                </div>
                                <div class="right-text">
                                    <h4>Partage de Livres</h4>
                                    <p>Partagez facilement vos livres avec d'autres passionnés et découvrez de nouveaux ouvrages</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-02.png') }}" alt="communauté">
                                </div>
                                <div class="right-text">
                                    <h4>Communauté Solidaire</h4>
                                    <p>Rejoignez une communauté bienveillante de lecteurs qui partagent votre passion</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.9s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-03.png') }}" alt="échange">
                                </div>
                                <div class="right-text">
                                    <h4>Échanges Gratuits</h4>
                                    <p>Tous les échanges sont gratuits et basés sur la confiance mutuelle entre membres</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-04.png') }}" alt="écologie">
                                </div>
                                <div class="right-text">
                                    <h4>Démarche Écologique</h4>
                                    <p>Réduisons ensemble le gaspillage en donnant une seconde vie aux livres</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="services" class="our-services section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center wow fadeInLeft" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="left-image">
                    <img src="{{ asset('assets/images/services-left-image.png') }}" alt="bibliothèque collaborative">
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="section-heading">
                    <h2>Développez votre <em>Bibliothèque</em> grâce à notre <span>Communauté</span></h2>
                    <p>BookShare facilite l'accès à la connaissance en créant des liens entre lecteurs passionnés. Notre plateforme encourage le partage, l'apprentissage et la découverte de nouveaux horizons littéraires tout en réduisant l'impact environnemental.</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="first-bar progress-skill-bar">
                            <h4>Livres Disponibles</h4>
                            <span>2,847</span>
                            <div class="filled-bar"></div>
                            <div class="full-bar"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="second-bar progress-skill-bar">
                            <h4>Membres Actifs</h4>
                            <span>1,234</span>
                            <div class="filled-bar"></div>
                            <div class="full-bar"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="third-bar progress-skill-bar">
                            <h4>Échanges Réalisés</h4>
                            <span>4,592</span>
                            <div class="filled-bar"></div>
                            <div class="full-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="portfolio" class="our-portfolio section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="section-heading wow bounceIn" data-wow-duration="1s" data-wow-delay="0.2s">
                    <h2>Découvrez Nos <em>Catégories</em> &amp; <span>Services</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                        <div class="hidden-content">
                            <h4>Romans & Fiction</h4>
                            <p>Découvrez une large sélection de romans et œuvres de fiction</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/portfolio-image.png') }}" alt="romans">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.4s">
                        <div class="hidden-content">
                            <h4>Sciences & Techniques</h4>
                            <p>Partagez et découvrez des ouvrages scientifiques et techniques</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/portfolio-image.png') }}" alt="sciences">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="hidden-content">
                            <h4>Développement Personnel</h4>
                            <p>Livres pour grandir et développer ses compétences personnelles</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/portfolio-image.png') }}" alt="développement">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.6s">
                        <div class="hidden-content">
                            <h4>Histoire & Société</h4>
                            <p>Explorez l'histoire et les enjeux de notre société</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/portfolio-image.png') }}" alt="histoire">
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<div id="blog" class="our-blog section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="section-heading">
                    <h2>Découvrez les <em>Derniers</em> Échanges de la <span>Communauté</span></h2>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInDown" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="top-dec">
                    <img src="{{ asset('assets/images/blog-dec.png') }}" alt="décoration">
                </div>
            </div>
        </div>

        @php
            $latestEvents = \App\Models\BookEvent::orderByDesc('date_evenement')->take(4)->get();
            $featuredEvent = $latestEvents->first();
            $otherEvents = $latestEvents->slice(1);
        @endphp

        <div class="row">
            <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                @if($featuredEvent)
                <div class="left-image">
                    <a href="{{ route('front.events.show', $featuredEvent) }}">
                        @if($featuredEvent->image)
                            <img src="/{{ $featuredEvent->image }}" alt="{{ $featuredEvent->titre }}">
                        @else
                            <img src="{{ asset('assets/images/big-blog-thumb.jpg') }}" alt="{{ $featuredEvent->titre }}">
                        @endif
                    </a>
                    <div class="info">
                        <div class="inner-content">
                            <ul>
                                <li><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($featuredEvent->date_evenement)->translatedFormat('d M Y') }}</li>
                                <li><i class="fa fa-users"></i> BookShare</li>
                                @if($featuredEvent->type)
                                <li><i class="fa fa-folder"></i> {{ ucfirst($featuredEvent->type) }}</li>
                                @endif
                            </ul>
                            <a href="{{ route('front.events.show', $featuredEvent) }}"><h4>{{ $featuredEvent->titre }}</h4></a>
                            <p>{{ Str::limit($featuredEvent->description, 160) }}</p>
                            <div class="main-blue-button">
                                <a href="{{ route('front.events.show', $featuredEvent) }}">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="text-muted">Aucun événement.</div>
                @endif
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="right-list">
                    <ul>
                        @forelse($otherEvents as $ev)
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($ev->date_evenement)->translatedFormat('d M Y') }}</span>
                                <a href="{{ route('front.events.show', $ev) }}"><h4>{{ $ev->titre }}</h4></a>
                                <p>{{ Str::limit($ev->description, 120) }}</p>
                            </div>
                            <div class="right-image">
                                <a href="{{ route('front.events.show', $ev) }}">
                                    @if($ev->image)
                                        <img src="/{{ $ev->image }}" alt="{{ $ev->titre }}">
                                    @else
                                        <img src="{{ asset('assets/images/blog-thumb-01.jpg') }}" alt="{{ $ev->titre }}">
                                    @endif
                                </a>
                            </div>
                        </li>
                        @empty
                        <li>
                            <div class="left-content align-self-center">
                                <span>Aucun événement.</span>
                            </div>
                        </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="contact" class="contact-us section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 align-self-center wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.25s">
                <div class="section-heading">
                    <h2>Rejoignez Notre Communauté de Lecteurs Passionnés</h2>
                    <p>Vous avez des questions sur BookShare ? Vous souhaitez proposer un partenariat ou simplement nous faire part de vos suggestions ? N'hésitez pas à nous contacter !</p>
                    <div class="phone-info">
                        <h4>Pour toute question : <span><i class="fa fa-phone"></i> <a href="#">+216 XX XXX XXX</a></span></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.25s">
                <form id="contact" action="" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <fieldset>
                                <input type="name" name="name" id="name" placeholder="Prénom" autocomplete="on" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <input type="surname" name="surname" id="surname" placeholder="Nom" autocomplete="on" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Votre Email" required="">
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <textarea name="message" type="text" class="form-control" id="message" placeholder="Votre Message" required=""></textarea>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Envoyer le Message</button>
                            </fieldset>
                        </div>
                    </div>
                    <div class="contact-dec">
                        <img src="{{ asset('assets/images/contact-decoration.png') }}" alt="décoration contact">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-css')
<style>
  .enhanced-card {
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    border-radius: 12px;
    overflow: hidden;
  }

  .enhanced-card .card-header {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-bottom: 1px solid #e9ecef;
  }

  .btn-enhanced { transition: all 0.2s ease; font-weight: 500; }
  .btn-enhanced:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

  /* Animated event cards (home) */
  #latest-events .event-card { border-radius: 16px; transition: transform .25s ease, box-shadow .25s ease; }
  #latest-events .event-card:hover { transform: translateY(-4px); box-shadow: 0 10px 24px rgba(0,0,0,0.12); }
  #latest-events .event-card-img { transition: transform .35s ease; }
  #latest-events .event-card:hover .event-card-img { transform: scale(1.04); }

  #latest-events .event-mini-card { transition: transform .2s ease, box-shadow .2s ease; border-radius: 14px; overflow: hidden; }
  #latest-events .event-mini-card:hover { transform: translateY(-3px); box-shadow: 0 10px 22px rgba(0,0,0,0.12); }
  #latest-events .event-mini-img { transition: transform .35s ease, filter .35s ease; }
  #latest-events .event-mini-card:hover .event-mini-img { transform: scale(1.06); filter: saturate(1.1); }

  #latest-events .event-card, #latest-events .event-mini-card { opacity: 0; animation: cardFadeIn .5s ease forwards; }
  #latest-events .event-mini-card { animation-delay: .05s; }
  @keyframes cardFadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

</style>
@endsection

@section('extra-js')
<script src="{{ asset('assets/js/templatemo-custom.js') }}"></script>

<!-- Participation Modal -->
<div class="modal fade" id="participationModal" tabindex="-1" aria-labelledby="participationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="participationModalLabel">Ma participation au défi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="participationModalBody">
        <!-- Content will be loaded here -->
      </div>
    </div>
  </div>
</div>

<script>
function showParticipationModal(participationId) {
  const modal = new bootstrap.Modal(document.getElementById('participationModal'));
  const modalBody = document.getElementById('participationModalBody');
  modalBody.innerHTML = `
    <div class="text-center py-4">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Chargement...</span>
      </div>
      <p class="mt-2">Chargement de votre participation...</p>
    </div>
  `;
  modal.show();
  fetch(`/participation-defis/${participationId}/modal-content`)
    .then(r => r.text())
    .then(html => { modalBody.innerHTML = html; bindParticipationModalHandlers(); })
    .catch(() => { modalBody.innerHTML = '<div class="alert alert-danger">Erreur de chargement.</div>'; });
}
function completeChallenge(participationId) { showParticipationModal(participationId); }

function bindParticipationModalHandlers() {
  const modalBody = document.getElementById('participationModalBody');
  if (!modalBody) return;

  const readerBtn = modalBody.querySelector('[data-role="open-reader"]');
  if (readerBtn && readerBtn.dataset.fileUrl) {
    readerBtn.addEventListener('click', () => new bootstrap.Modal(document.getElementById('bookReaderModal')).show());
  }

  modalBody.querySelectorAll('[data-role="go-events"],[data-role="go-participations"]').forEach(btn => {
    btn.addEventListener('click', () => {
      const url = btn.getAttribute('data-redirect-url');
      if (url) window.location.href = url;
    });
  });

  const form = modalBody.querySelector('#participation-form');
  const completeBtn = modalBody.querySelector('[data-role="complete-defi"]');
  if (completeBtn && form) {
    completeBtn.addEventListener('click', () => {
      const statusSelect = form.querySelector('#status');
      if (statusSelect) statusSelect.value = 'termine';
      submitParticipationForm(form, true);
    });
  }
  if (form) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      submitParticipationForm(form, false);
    });
  }

  // Quiz: bind click to open and generate
  const quizBtn = modalBody.querySelector('[data-role="open-quiz"]');
  if (quizBtn) {
    quizBtn.addEventListener('click', async () => {
      const qm = document.getElementById('quizModal');
      if (qm) new bootstrap.Modal(qm).show();
      // Prefer server-side extraction with current participation id
      const participationId = modalBody.querySelector('#participationMeta')?.getAttribute('data-participation-id')
        || quizBtn.getAttribute('data-participation-id') || '';
      try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const res = await fetch(`/ai/quiz/from-participation/${participationId}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
          body: JSON.stringify({ num_questions: 4, difficulty: 'medium' })
        });
        if (res.ok) { const quiz = await res.json(); renderQuiz(quiz); return; }
      } catch (_) {}
      // Fallback to description prompt
      const title = quizBtn.getAttribute('data-title') || '';
      const author = quizBtn.getAttribute('data-author') || '';
      const description = quizBtn.getAttribute('data-description') || '';
      let text = `${title} — ${author}. ${description}`.trim();
      if (text.length < 50) text = (text + ' ').repeat(10);
      await generateQuiz(text, 4, 'medium');
    });
  }
}

function submitParticipationForm(form, redirectOnComplete) {
  const formData = new FormData(form);
  const action = form.getAttribute('action');

  fetch(action, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: formData
  })
  .then(async response => {
    if (!response.ok) throw new Error('HTTP ' + response.status);
    const participationIdMatch = action.match(/participation-defis\/(\d+)/);
    const participationId = participationIdMatch ? participationIdMatch[1] : null;
    if (participationId) {
      return fetch(`/participation-defis/${participationId}/modal-content`).then(r => r.text());
    }
    return null;
  })
  .then(html => {
    if (!html) return;
    const modalBody = document.getElementById('participationModalBody');
    modalBody.innerHTML = html;
    bindParticipationModalHandlers();

    if (redirectOnComplete) {
      const statusSelect = modalBody.querySelector('#status');
      if (statusSelect && statusSelect.value === 'termine') {
        window.location.href = '/events';
      }
    }

    const success = document.createElement('div');
    success.className = 'alert alert-success mt-3';
    success.textContent = 'Participation mise à jour avec succès !';
    modalBody.prepend(success);
    setTimeout(() => success.remove(), 2000);
  })
  .catch(() => {
    const modalBody = document.getElementById('participationModalBody');
    const error = document.createElement('div');
    error.className = 'alert alert-danger mt-3';
    error.textContent = 'Erreur lors de la mise à jour.';
    modalBody.prepend(error);
    setTimeout(() => error.remove(), 3000);
  });
}

// Quiz helpers (shared with modal content)
function toggleQuizSteps(step) {
  const input = document.getElementById('quizStepInput');
  const render = document.getElementById('quizStepRender');
  if (!input || !render) return;
  if (step === 'render') { input.classList.add('d-none'); render.classList.remove('d-none'); }
  else { render.classList.add('d-none'); input.classList.remove('d-none'); }
}

async function generateQuiz(text, numQuestions, difficulty) {
  const container = document.getElementById('quizContainer');
  if (container) {
    container.innerHTML = `<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div><p class=\"mt-2 mb-0\">Génération du quiz...</p></div>`;
  }
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  const res = await fetch('/ai/quiz', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
    body: JSON.stringify({ text, num_questions: numQuestions, difficulty })
  });
  if (!res.ok) { const body = await res.text(); alert('Erreur IA: ' + body); return; }
  const quiz = await res.json();
  renderQuiz(quiz);
}

function renderQuiz(quiz) {
  const container = document.getElementById('quizContainer');
  if (!container) return;
  container.innerHTML = '';
  const title = document.createElement('h5');
  title.textContent = quiz.title || 'Quiz';
  container.appendChild(title);
  (quiz.questions || []).forEach((q, idx) => {
    const block = document.createElement('div');
    block.className = 'mb-3 p-3 border rounded';
    const label = document.createElement('div');
    label.className = 'fw-semibold mb-2';
    label.textContent = (idx + 1) + '. ' + (q.question || '');
    block.appendChild(label);
    if (q.type === 'mcq' && Array.isArray(q.choices)) {
      q.choices.forEach((choice, cidx) => {
        const id = `q_${idx}_c_${cidx}`;
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `<input class=\"form-check-input\" type=\"radio\" name=\"q_${idx}\" id=\"${id}\" value=\"${choice}\">`+
                        `<label class=\"form-check-label\" for=\"${id}\">${choice}</label>`;
        block.appendChild(div);
      });
    } else if (q.type === 'true_false') {
      ['true','false'].forEach((val, cidx) => {
        const id = `q_${idx}_tf_${cidx}`;
        const labelTxt = val === 'true' ? 'Vrai' : 'Faux';
        const div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = `<input class=\"form-check-input\" type=\"radio\" name=\"q_${idx}\" id=\"${id}\" value=\"${val}\">`+
                        `<label class=\"form-check-label\" for=\"${id}\">${labelTxt}</label>`;
        block.appendChild(div);
      });
    } else {
      const input = document.createElement('input');
      input.className = 'form-control';
      input.type = 'text';
      input.name = `q_${idx}`;
      block.appendChild(input);
    }
    const ans = document.createElement('input');
    ans.type = 'hidden';
    ans.value = (q.answer !== undefined && q.answer !== null) ? String(q.answer) : '';
    ans.setAttribute('data-role', `answer_${idx}`);
    block.appendChild(ans);
    if (q.explanation) {
      const exp = document.createElement('div');
      exp.className = 'small text-muted mt-2';
      exp.textContent = 'Indice: ' + q.explanation;
      block.appendChild(exp);
    }
    container.appendChild(block);
  });
  toggleQuizSteps('render');
}

async function checkAnswers() {
  console.log('checkAnswers called');
  const blocks = document.querySelectorAll('#quizContainer > div');
  console.log('Found blocks:', blocks.length);
  let score = 0;
  blocks.forEach((block, idx) => {
    const correct = block.querySelector(`[data-role="answer_${idx}"]`)?.value || '';
    let user;
    const selected = block.querySelector(`input[name="q_${idx}"]:checked`);
    if (selected) user = selected.value; else {
      const text = block.querySelector(`input[name="q_${idx}"]`);
      user = text ? text.value.trim() : '';
    }
    const isOk = user && correct && user.toString().toLowerCase() === correct.toString().toLowerCase();
    if (isOk) { score++; block.classList.add('border-success'); }
    else { block.classList.add('border-danger'); }
  });
  const total = blocks.length || 0;
  
  // Save score to participation
  const participationId = document.getElementById('participationMeta')?.getAttribute('data-participation-id');
  if (participationId) {
    try {
      const token = document.querySelector('meta[name="csrf-token"]')?.content;
      const response = await fetch(`/ai/quiz/save-score/${participationId}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
          score: score,
          total_questions: total
        })
      });
      
      if (response.ok) {
        const result = await response.json();
        console.log('Score sauvegardé:', result);
        alert(`Votre score: ${score}/${total}\n\nScore sauvegardé dans votre participation !`);
      } else {
        console.error('Erreur lors de la sauvegarde du score');
        alert(`Votre score: ${score}/${total}\n\nErreur lors de la sauvegarde.`);
      }
    } catch (error) {
      console.error('Erreur:', error);
      alert(`Votre score: ${score}/${total}\n\nErreur lors de la sauvegarde.`);
    }
  } else {
    alert(`Votre score: ${score}/${total}`);
  }
}

// Simple direct event binding for quiz buttons
document.addEventListener('click', function(e) {
  if (e.target && e.target.id === 'btnCheckAnswers') {
    console.log('Vérifier button clicked');
    checkAnswers();
  }
  if (e.target && e.target.id === 'btnBackToInput') {
    console.log('Back to input clicked');
    toggleQuizSteps('input');
  }
  if (e.target && e.target.id === 'btnGenerateQuiz') {
    console.log('Generate quiz clicked');
    const text = (document.getElementById('quizText')?.value || '').trim();
    const num = parseInt(document.getElementById('quizNum')?.value || '4', 10);
    const difficulty = document.getElementById('quizDifficulty')?.value || '';
    if (text.length < 50) { alert('Veuillez fournir au moins 50 caractères.'); return; }
    generateQuiz(text, num, difficulty || undefined);
  }
});
</script>
@endsection
