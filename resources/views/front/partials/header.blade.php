<!-- ***** Header Area Start ***** -->
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="main-nav">
          <!-- ***** Logo Start ***** -->
          <a href="{{ url('/') }}" class="logo">
          <img src="{{ asset('assets/images/logo.png') }}" alt="BookShare Logo" style="height:60px; width:auto; display:inline-block; vertical-align:middle; margin-bottom:10px; margin-left:10px;">
            <h4>Book<span>Verse</span></h4>
          </a>
          <!-- ***** Logo End ***** -->
          <!-- ***** Menu Start ***** -->
          <ul class="nav">
            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
            <li>
              @auth
                @if(Auth::user()->role === 'user')
                  <a href="{{ url('/livres') }}">Books</a>
                @endif
              @endauth
            </li>
            @auth
              @if(Auth::user()->role === 'user')
            <li>
              <a href="{{ url('/livres') }}#recommendations">Recommendations</a>
            </li>
              @endif
            @endauth
            <li class="scroll-to-section"><a href="#about">About Us</a></li>
            <li class="scroll-to-section"><a href="#services">How It Works</a></li>
            <li class="scroll-to-section"><a href="#portfolio">Categories</a></li>
            <li class="scroll-to-section"><a href="#blog">Community</a></li>
            <li class="scroll-to-section"><a href="#contact">Contact</a></li>
            <li class="scroll-to-section">
              @auth
                @if(Auth::user()->role === 'user')
                  <a href="{{ url('/explore') }}">Explore</a>
                @endif
              @endauth
            </li>
            @auth
                 @if(Auth::user()->role === 'user')
            <li><a href="{{ route('emprunts.index') }}">Emprunts</a></li>
                  @endif
            @endauth
            <li class="scroll-to-section"><div class="main-red-button"><a href="{{ route('auth') }}">Join Us</a></div></li>
          </ul>
          <a class='menu-trigger'>
              <span>Menu</span>
          </a>
          <!-- ***** Menu End ***** -->
        </nav>
      </div>
    </div>
  </div>
</header>
<!-- ***** Header Area End ***** -->
