<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/templatemo-space-dynamic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/animated.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/owl.css') }}">
    
    <style>
      /* Reserve space for sticky header so content isn't hidden under it */
      :root { --header-height: 96px; }
      body { padding-top: var(--header-height); }
      @media (max-width: 991.98px) { :root { --header-height: 72px; } }
      /* Keep the header fixed and above all content */
      .header-area { background-color: #ffffff; position: relative; z-index: 9998; }
      .header-area.header-sticky { position: fixed; top: 0; left: 0; right: 0; box-shadow: 0 4px 16px rgba(0,0,0,0.06); z-index: 9998; }
      /* Layout: ensure nav content fits inside header height */
      .header-area .container, .header-area .row, .header-area .col-12 { height: var(--header-height); }
      .header-area .main-nav { display: flex; align-items: center; height: var(--header-height); }
      .header-area .main-nav .logo { display: flex; align-items: center; height: 100%; }
      .header-area .main-nav .nav { display: flex; align-items: center; height: 100%; margin-bottom: 0; margin-left: auto; flex-wrap: nowrap; white-space: nowrap; column-gap: 8px; justify-content: flex-end; }
      .header-area .main-nav .nav li { height: 100%; display: flex; align-items: center; }
      .header-area .main-nav .nav li a { display: flex; align-items: center; height: 100%; padding: 0 12px; line-height: 1; }
      .header-area .main-nav .nav li .main-red-button a { padding: 6px 14px; height: auto; }
      /* Stacking contexts */
      .header-area .main-nav, .header-area .main-nav .nav { position: relative; z-index: 9999; }
      .header-area .main-nav .nav li { position: relative; }
      .header-area .main-nav .nav li ul, .header-area .dropdown-menu { position: absolute; z-index: 10000; }
      /* Push common hero/sections below header stacking context */
      .main-banner, #about, #services, #portfolio, #blog, #contact { position: relative; z-index: 1; }
    </style>
    
    @yield('extra-css')

  </head>

<body>

  <!-- ***** Preloader Start ***** -->
  @isset($usePreloader)
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  @endisset
  <!-- ***** Preloader End ***** -->

  @unless (Request::is('auth*'))
      @include('front.partials.header')
  @endunless

  @yield('content')

  @unless (Request::is('auth*'))
    @include('front.partials.footer')
  @endunless
  <!-- Scripts -->
  <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl-carousel.js') }}"></script>
    <script src="{{ asset('assets/js/animation.js') }}"></script>
    <script src="{{ asset('assets/js/imagesloaded.js') }}"></script>
    @yield('extra-js')

</body>
</html>