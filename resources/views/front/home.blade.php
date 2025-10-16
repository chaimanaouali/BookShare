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
        <div class="row">
            <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="left-image">
                    <a href="#"><img src="{{ asset('assets/images/big-blog-thumb.jpg') }}" alt="livre du mois"></a>
                    <div class="info">
                        <div class="inner-content">
                            <ul>
                                <li><i class="fa fa-calendar"></i> 24 Mar 2024</li>
                                <li><i class="fa fa-users"></i> BookShare</li>
                                <li><i class="fa fa-folder"></i> Coup de cœur</li>
                            </ul>
                            <a href="#"><h4>Livre du Mois : "L'Art de la Simplicité"</h4></a>
                            <p>Découvrez notre sélection du mois choisi par la communauté. Un ouvrage qui transforme notre rapport aux choses et à la vie...</p>
                            <div class="main-blue-button">
                                <a href="#">En savoir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.25s">
                <div class="right-list">
                    <ul>
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> 18 Mar 2024</span>
                                <a href="#"><h4>Nouveau : Échanges Virtuels</h4></a>
                                <p>Lancez des échanges de livres numériques avec notre nouvelle fonctionnalité...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/blog-thumb-01.jpg') }}" alt="échanges virtuels"></a>
                            </div>
                        </li>
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> 14 Mar 2024</span>
                                <a href="#"><h4>Club de Lecture Mensuel</h4></a>
                                <p>Rejoignez notre club de lecture et partagez vos impressions avec d'autres passionnés...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/blog-thumb-01.jpg') }}" alt="club lecture"></a>
                            </div>
                        </li>
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> 06 Mar 2024</span>
                                <a href="#"><h4>Témoignage : Marie & ses 50 livres partagés</h4></a>
                                <p>Découvrez l'histoire inspirante de Marie qui a partagé plus de 50 livres depuis son inscription...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/blog-thumb-01.jpg') }}" alt="témoignage"></a>
                            </div>
                        </li>
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

@section('extra-js')
<script src="{{ asset('assets/js/templatemo-custom.js') }}"></script>
@endsection
