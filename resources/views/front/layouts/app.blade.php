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
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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
      .header-area .container { max-width: 100% !important; padding-left: 20px !important; padding-right: 20px !important; }
      @media (max-width: 1250px) {
        .header-area .container { padding-left: 15px !important; padding-right: 15px !important; }
      }
      @media (max-width: 1050px) {
        .header-area .container { padding-left: 10px !important; padding-right: 10px !important; }
      }
      .header-area .container, .header-area .row, .header-area .col-12 { height: var(--header-height); }
      .header-area .main-nav { display: flex; align-items: center; height: var(--header-height); justify-content: space-between; position: relative; gap: 20px; }
      .header-area .main-nav .logo { display: flex; align-items: center; height: 100%; flex-shrink: 0; min-width: fit-content; }
      .header-area .main-nav .nav { 
        display: flex; 
        align-items: center; 
        height: 100%; 
        margin-bottom: 0; 
        margin-left: auto; 
        flex-wrap: nowrap; 
        white-space: nowrap; 
        column-gap: 8px; 
        justify-content: flex-start;
        overflow-x: auto;
        overflow-y: hidden;
        max-width: 100%;
        flex: 1;
        /* Smooth scrolling */
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        /* Hide scrollbar but keep functionality */
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
      }
      .header-area .main-nav .nav::-webkit-scrollbar {
        display: none; /* Chrome/Safari/Opera */
      }
      /* Fade effect on right edge to indicate scrollability */
      .header-area .main-nav .nav::after {
        content: '';
        position: sticky;
        right: 0;
        top: 0;
        height: 100%;
        width: 60px;
        background: linear-gradient(to left, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%);
        pointer-events: none;
        z-index: 10;
        flex-shrink: 0;
        margin-left: auto;
      }
      .header-area .main-nav .nav li { height: 100%; display: flex; align-items: center; flex-shrink: 0; position: relative; z-index: 2; }
      .header-area .main-nav .nav li a { display: flex; align-items: center; height: 100%; padding: 0 12px; line-height: 1; font-size: 15px; }
      /* Override base template hiding of nav items and excessive padding */
      @media (min-width: 993px) {
        .header-area .main-nav .nav li:last-child,
        .background-header .main-nav .nav li:last-child,
        .header-area .main-nav .nav li:nth-last-child(2),
        .header-area .main-nav .nav li:nth-last-child(3) {
          display: flex !important;
        }
        /* Remove excessive padding from last child */
        .header-area .main-nav .nav li:last-child {
          padding-left: 0px !important;
          padding-right: 0px !important;
        }
      }
      /* Larger screens: more comfortable spacing */
      @media (min-width: 1400px) {
        .header-area .main-nav .nav { column-gap: 12px; }
        .header-area .main-nav .nav li a { padding: 0 15px; }
      }
      /* Medium screens: standard spacing */
      @media (max-width: 1200px) {
        .header-area .main-nav .nav { column-gap: 6px; }
        .header-area .main-nav .nav li a { padding: 0 10px; font-size: 14px; }
      }
      .header-area .main-nav .nav li .main-red-button a { padding: 8px 16px; height: auto; }
      @media (max-width: 1200px) {
        .header-area .main-nav .nav li .main-red-button a { padding: 6px 12px; }
      }
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