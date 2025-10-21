<!-- *** Header Area Start *** -->
<style>
.main-blue-button a {
  font-size: 13px;
  color: #fff !important;
  background-color: #03a9f4;
  padding: 15px 35px;
  display: inline-block;
  border-radius: 30px;
  font-weight: 500;
  text-transform: uppercase;
  transition: all .3s;
  text-decoration: none;
}
.main-blue-button a:hover {
  background-color: #0288d1;
  color: #fff !important;
}

/* User Profile Dropdown Styles */
.user-profile-dropdown {
  position: relative;
  z-index: 10000;
}

.user-avatar-wrapper {
  position: relative;
  display: inline-block;
}

.user-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  cursor: pointer;
  border: 2px solid #03a9f4;
  transition: all 0.3s;
  display: block;
  position: relative;
}

.user-avatar:hover {
  border-color: #0288d1;
  transform: scale(1.05);
}

.user-avatar.online::after {
  content: '';
  position: absolute;
  bottom: 2px;
  right: 2px;
  width: 12px;
  height: 12px;
  background-color: #4caf50;
  border: 2px solid #fff;
  border-radius: 50%;
}

.dropdown-menu-user {
  position: fixed;
  top: 80px;
  right: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.15);
  min-width: 280px;
  z-index: 10001;
  display: none;
  padding: 0;
  overflow: hidden;
}

.dropdown-menu-user.show {
  display: block;
  animation: fadeInDown 0.3s;
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.dropdown-user-header {
  padding: 20px;
  border-bottom: 1px solid #f0f0f0;
  display: flex;
  align-items: center;
  gap: 15px;
}

.dropdown-user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  border: 2px solid #03a9f4;
}

.dropdown-user-info h6 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #333;
}

.dropdown-user-info small {
  color: #999;
  font-size: 13px;
}

.dropdown-menu-user a {
  display: flex;
  align-items: center;
  padding: 12px 20px;
  color: #666;
  text-decoration: none;
  transition: all 0.3s;
  gap: 12px;
}

.dropdown-menu-user a:hover {
  background-color: #f8f9fa;
  color: #03a9f4;
}

.dropdown-menu-user a i {
  font-size: 20px;
  width: 24px;
}

.dropdown-divider {
  height: 1px;
  background-color: #f0f0f0;
  margin: 0;
}

.downloads-section {
  padding: 15px 20px;
  background-color: #f8f9fa;
  display: flex;
  align-items: center;
  gap: 15px;
}

.downloads-icon {
  width: 50px;
  height: 50px;
  background-color: #03a9f4;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 24px;
}

.downloads-info small {
  display: block;
  color: #999;
  font-size: 12px;
  margin-bottom: 4px;
}

.downloads-info h5 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #333;
}

.star-count {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 15px;
  background: white;
  border: 1px solid #e0e0e0;
  border-radius: 25px;
  margin-right: 15px;
  font-size: 14px;
  color: #666;
}

.star-count i {
  color: #ffc107;
  font-size: 18px;
}
</style>
<header class="header-area header-sticky wow slideInDown" data-wow-duration="0.75s" data-wow-delay="0s">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <nav class="main-nav">
          <!-- *** Logo Start *** -->
          <a href="{{ url('/') }}" class="logo">
          <img src="{{ asset('assets/images/logo.png') }}" alt="BookShare Logo" style="height:60px; width:auto; display:inline-block; vertical-align:middle; margin-bottom:10px; margin-left:5px; margin-right:5px;">
            <h4 style="margin:0;">Book<span>Verse</span></h4>
          </a>
          <!-- *** Logo End *** -->
          <!-- *** Menu Start *** -->
          <ul class="nav">
            <li class="scroll-to-section"><a href="#top" class="active">Home</a></li>
            <li><a href="{{ url('/livres') }}">Books</a></li>
            <li><a href="{{ url('/explore') }}">Libraries</a></li>

            @auth
              @if(Auth::user()->role === 'user' || Auth::user()->role === 'contributor')
            <li><a href="{{ url('/livres') }}">Recommendations</a></li>
            <li><a href="{{ route('emprunts.index') }}">Emprunts</a></li>
            <li><a href="{{ route('front.defis.index') }}">Défis</a></li>
            <li><a href="{{ route('reading-personality.show') }}">Mon Profil IA</a></li>
              @endif
            @endauth
            <li class="scroll-to-section"><a href="#about">About Us</a></li>
            <li class="scroll-to-section"><a href="#contact">Contact</a></li>
            @guest
            <li class="scroll-to-section"><div class="main-red-button"><a href="{{ route('auth') }}">Join Us</a></div></li>
            @endguest
            @auth
              @if(Auth::user()->role === 'user')
            <li class="scroll-to-section">
              <div class="main-blue-button">
                <a href="#" onclick="showContributorPopup(event)">Become Contributor</a>
              </div>
            </li>
              @endif
              @if(Auth::user()->role === 'contributor')
            <li class="scroll-to-section">
              <div class="main-blue-button">
                <a href="{{ route('contributor.dashboard') }}">Contributor Page</a>
              </div>
            </li>
              @endif
            @endauth
            
            @auth
            <!-- Star Count -->
            <li class="scroll-to-section">
              <div class="star-count">
                <i class="bx bx-star"></i>
                <span>{{ Auth::user()->avis()->count() }}</span>
              </div>
            </li>
            
            <!-- User Profile Dropdown -->
            <li class="user-profile-dropdown">
              <div class="user-avatar-wrapper">
                <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar" class="user-avatar online" onclick="toggleUserDropdown(event)">
                <div class="dropdown-menu-user" id="userDropdown">
                  <!-- User Info Header -->
                  <div class="dropdown-user-header">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="User Avatar" class="dropdown-user-avatar">
                    <div class="dropdown-user-info">
                      <h6>{{ Auth::user()->name }}</h6>
                      <small>{{ ucfirst(Auth::user()->role) }}</small>
                    </div>
                  </div>
                  
                  <div class="dropdown-divider"></div>
                  
                  <!-- My Profile Link -->
                  <a href="{{ route('profile.show') }}">
                    <i class="bx bx-user"></i>
                    <span>My Profile</span>
                  </a>
                  
                  <div class="dropdown-divider"></div>
                  
                  <!-- Log Out Link -->
                  <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-dropdown').submit();">
                    <i class="bx bx-power-off"></i>
                    <span>Log Out</span>
                  </a>
                  
                  <!-- Total Downloads Section -->
                  <div class="downloads-section">
                    <div class="downloads-icon">
                      <i class="bx bx-download"></i>
                    </div>
                    <div class="downloads-info">
                      <small>Total Downloads</small>
                      <h5>0</h5>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            @endauth
          </ul>
          <a class='menu-trigger'>
              <span>Menu</span>
          </a>
          <!-- *** Menu End *** -->
        </nav>
      </div>
    </div>
  </div>
</header>
<!-- *** Header Area End *** -->

<!-- Contributor Confirmation Popup -->
<div id="contributorPopup" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
  <div style="background: white; padding: 30px; border-radius: 10px; max-width: 400px; text-align: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h3 style="margin-bottom: 20px; color: #333;">Become a Contributor</h3>
    <p style="margin-bottom: 30px; color: #666;">Are you sure you want to become a contributor? You'll be able to upload and share your own books!</p>
    <div style="display: flex; gap: 15px; justify-content: center;">
      <button onclick="becomeContributor()" style="background: #ed563b; color: white; border: none; padding: 12px 30px; border-radius: 5px; cursor: pointer; font-weight: bold;">Yes</button>
      <button onclick="closeContributorPopup()" style="background: #666; color: white; border: none; padding: 12px 30px; border-radius: 5px; cursor: pointer; font-weight: bold;">No</button>
    </div>
  </div>
</div>

<script>
function showContributorPopup(event) {
  event.preventDefault();
  document.getElementById('contributorPopup').style.display = 'flex';
}

function closeContributorPopup() {
  document.getElementById('contributorPopup').style.display = 'none';
}

function becomeContributor() {
  // Send AJAX request to update user role
  fetch('{{ route("user.become-contributor") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({})
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Congratulations! You are now a contributor!');
      window.location.reload();
    } else {
      alert('Something went wrong. Please try again.');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alert('An error occurred. Please try again.');
  });
}

// Toggle user dropdown menu
function toggleUserDropdown(event) {
  event.stopPropagation();
  const dropdown = document.getElementById('userDropdown');
  if (dropdown) {
    dropdown.classList.toggle('show');
    console.log('Dropdown toggled:', dropdown.classList.contains('show'));
  } else {
    console.error('Dropdown element not found');
  }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
  const dropdown = document.getElementById('userDropdown');
  const avatarWrapper = document.querySelector('.user-avatar-wrapper');
  
  if (dropdown && avatarWrapper && !avatarWrapper.contains(event.target)) {
    dropdown.classList.remove('show');
  }
});
</script>

<!-- Logout Form for Dropdown (Hidden) -->
<form id="logout-form-dropdown" action="{{ route('logout') }}" method="POST" style="display: none;">
  @csrf
</form>