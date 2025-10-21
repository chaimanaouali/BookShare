<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  <div class="app-brand demo">
    <a href="{{ (Auth::check() && Auth::user()->role === 'contributor') ? url('contributor/dashboard') : url('/dashboard') }}" class="app-brand-link">
      <img src="{{ asset('assets/images/bookVerse.png') }}" alt="Book Verse" style="height: 25px; width: auto;" class="app-brand-logo demo">
      <span class="app-brand-text demo menu-text fw-bold ms-2">{{config('variables.templateName')}}</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)

      {{-- adding active and open class if child is active --}}

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
      @else

      {{-- Check role-based access --}}
      @php
      $hasAccess = true;
      if (isset($menu->roles) && Auth::check()) {
        $userRole = Auth::user()->role;
        $hasAccess = in_array($userRole, $menu->roles);
      }
      @endphp

      @if ($hasAccess)
      {{-- active menu method --}}
      @php
      $activeClass = null;
      $currentRouteName = Route::currentRouteName();

      if ($currentRouteName === $menu->slug) {
        $activeClass = 'active';
      }
      elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
          foreach($menu->slug as $slug){
            if (str_contains($currentRouteName,$slug) and strpos($currentRouteName,$slug) === 0) {
              $activeClass = 'active open';
            }
          }
        }
        else{
          if (str_contains($currentRouteName,$menu->slug) and strpos($currentRouteName,$menu->slug) === 0) {
            $activeClass = 'active open';
          }
        }
      }
      @endphp

      {{-- main menu --}}
      <li class="menu-item {{$activeClass}}">
        @php
          $resolvedUrl = isset($menu->url) ? $menu->url : null;
          // Route contributors away from admin-only dashboard
          if (isset($menu->slug) && $menu->slug === 'dashboard' && Auth::check() && Auth::user()->role === 'contributor') {
            $resolvedUrl = 'contributor/dashboard';
          }
        @endphp
        @if(isset($menu->slug) && $menu->slug === 'logout')
          <a href="#" class="menu-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        @else
          <a href="{{ isset($resolvedUrl) ? url($resolvedUrl) : 'javascript:void(0);' }}" class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
        @endif
          @isset($menu->icon)
            <i class="{{ $menu->icon }}"></i>
          @endisset
          <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          @isset($menu->badge)
            <div class="badge rounded-pill bg-{{ $menu->badge[0] }} text-uppercase ms-auto">{{ $menu->badge[1] }}</div>
          @endisset
        </a>

        {{-- submenu --}}
        @isset($menu->submenu)
          @include('layouts.sections.menu.submenu',['menu' => $menu->submenu])
        @endisset
      </li>
      @endif
      @endif
    @endforeach
  </ul>

  <!-- Logout Form (Hidden) -->
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>

</aside>
