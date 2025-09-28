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
                            <h6>Welcome to BookShare</h6>
                            <h2>Share <em>Books</em> &amp; <span>Knowledge</span></h2>
                            <p>BookShare is a collaborative platform that connects reading enthusiasts. Share your books, discover new titles, and let's build together a supportive community where knowledge flows freely.</p>
                            <form id="search" action="#" method="GET">
                                <fieldset>
                                    <input type="text" name="book_search" class="email" placeholder="Search for a book, author..." autocomplete="on" required>
                                </fieldset>
                                <fieldset>
                                    <button type="submit" class="main-button">Search</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-image wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.5s">
                            <img src="{{ asset('assets/images/banner-right-image.png') }}" alt="communauté de lecteurs partageant des livres">
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
                    <img src="{{ asset('assets/images/about-left-image.png') }}" alt="personne lisant passionnément">
                </div>
            </div>
            <div class="col-lg-8 align-self-center">
                <div class="services">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.5s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-01.png') }}" alt="icône partage de livres">
                                </div>
                                <div class="right-text">
                                    <h4>Book Sharing</h4>
                                    <p>Easily share your books with other enthusiasts and discover new titles</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.7s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/service-icon-02.png') }}" alt="icône communauté">
                                </div>
                                <div class="right-text">
                                    <h4>Supportive Community</h4>
                                    <p>Join a caring community of readers who share your passion for books</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="0.9s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/free-exchange-icon.png') }}" alt="icône échange gratuit">
                                </div>
                                <div class="right-text">
                                    <h4>Free Exchanges</h4>
                                    <p>All exchanges are free and based on mutual trust between members</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="item wow fadeIn" data-wow-duration="1s" data-wow-delay="1.1s">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/eco-friendly-icon.png') }}" alt="icône écologie">
                                </div>
                                <div class="right-text">
                                    <h4>Eco-Friendly Approach</h4>
                                    <p>Let's reduce waste together by giving books a second life</p>
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
                    <img src="{{ asset('assets/images/collaborative-library.png') }}" alt="bibliothèque collaborative">
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-duration="1s" data-wow-delay="0.2s">
                <div class="section-heading">
                    <h2>Grow Your <em>Library</em> Through Our <span>Community</span></h2>
                    <p>BookShare facilitates access to knowledge by creating connections between passionate readers. Our platform encourages sharing, learning, and discovering new literary horizons while reducing environmental impact.</p>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="first-bar progress-skill-bar">
                            <h4>Available Books</h4>
                            <span>2,847</span>
                            <div class="filled-bar"></div>
                            <div class="full-bar"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="second-bar progress-skill-bar">
                            <h4>Active Members</h4>
                            <span>1,234</span>
                            <div class="filled-bar"></div>
                            <div class="full-bar"></div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="third-bar progress-skill-bar">
                            <h4>Successful Exchanges</h4>
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
                    <h2>Discover Our <em>Categories</em> &amp; <span>Services</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.3s">
                        <div class="hidden-content">
                            <h4>Novels & Fiction</h4>
                            <p>Discover a wide selection of novels and fiction works</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/fiction-books.jpg') }}" alt="livres de fiction">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.4s">
                        <div class="hidden-content">
                            <h4>Science & Technology</h4>
                            <p>Share and discover scientific and technical books</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/science-books.jpg') }}" alt="livres scientifiques">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.5s">
                        <div class="hidden-content">
                            <h4>Personal Development</h4>
                            <p>Books to grow and develop your personal skills</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/self-development-books.jpg') }}" alt="livres développement personnel">
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6">
                <a href="#">
                    <div class="item wow bounceInUp" data-wow-duration="1s" data-wow-delay="0.6s">
                        <div class="hidden-content">
                            <h4>History & Society</h4>
                            <p>Explore history and the challenges of our society</p>
                        </div>
                        <div class="showed-content">
                            <img src="{{ asset('assets/images/history-books.jpg') }}" alt="livres d'histoire">
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
                    <h2>Discover the <em>Latest</em> Community <span>Exchanges</span></h2>
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
                    <a href="#"><img src="{{ asset('assets/images/book-of-the-month.jpg') }}" alt="livre du mois sélectionné"></a>
                    <div class="info">
                        <div class="inner-content">
                            <ul>
                                <li><i class="fa fa-calendar"></i> 24 Mar 2024</li>
                                <li><i class="fa fa-users"></i> BookShare</li>
                                <li><i class="fa fa-folder"></i> Featured</li>
                            </ul>
                            <a href="#"><h4>Book of the Month: "The Art of Simplicity"</h4></a>
                            <p>Discover our monthly selection chosen by the community. A book that transforms our relationship with things and life...</p>
                            <div class="main-blue-button">
                                <a href="#">Learn More</a>
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
                                <a href="#"><h4>New Feature: Virtual Exchanges</h4></a>
                                <p>Launch digital book exchanges with our new feature...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/virtual-exchange.jpg') }}" alt="échanges virtuels de livres"></a>
                            </div>
                        </li>
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> 14 Mar 2024</span>
                                <a href="#"><h4>Monthly Book Club</h4></a>
                                <p>Join our book club and share your thoughts with other enthusiasts...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/reading-club.jpg') }}" alt="club de lecture communautaire"></a>
                            </div>
                        </li>
                        <li>
                            <div class="left-content align-self-center">
                                <span><i class="fa fa-calendar"></i> 06 Mar 2024</span>
                                <a href="#"><h4>Testimonial: Marie & Her 50 Shared Books</h4></a>
                                <p>Discover Marie's inspiring story who has shared over 50 books since joining...</p>
                            </div>
                            <div class="right-image">
                                <a href="#"><img src="{{ asset('assets/images/member-testimonial.jpg') }}" alt="témoignage membre actif"></a>
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
                    <h2>Join Our Community of Passionate Readers</h2>
                    <p>Do you have questions about BookShare? Would you like to propose a partnership or simply share your suggestions? Don't hesitate to contact us!</p>
                    <div class="phone-info">
                        <h4>For any questions: <span><i class="fa fa-phone"></i> <a href="#">+216 XX XXX XXX</a></span></h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.25s">
                <form id="contact" action="" method="post">
                    <div class="row">
                        <div class="col-lg-6">
                            <fieldset>
                                <input type="name" name="name" id="name" placeholder="First Name" autocomplete="on" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-6">
                            <fieldset>
                                <input type="surname" name="surname" id="surname" placeholder="Last Name" autocomplete="on" required>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <input type="text" name="email" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email" required="">
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <textarea name="message" type="text" class="form-control" id="message" placeholder="Your Message" required=""></textarea>
                            </fieldset>
                        </div>
                        <div class="col-lg-12">
                            <fieldset>
                                <button type="submit" id="form-submit" class="main-button">Send Message</button>
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