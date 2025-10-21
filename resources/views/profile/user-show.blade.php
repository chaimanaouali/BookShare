@extends('front.layouts.app')

@section('title', 'My Profile - BookVerse')

@section('content')
@include('front.partials.header')

<style>
.profile-container {
  min-height: calc(100vh - 200px);
  padding: 60px 0;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.profile-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  overflow: hidden;
  max-width: 800px;
  margin: 0 auto;
}

.profile-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px;
  text-align: center;
  color: white;
  position: relative;
}

.profile-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
  opacity: 0.3;
}

.profile-avatar {
  width: 120px;
  height: 120px;
  background: white;
  border-radius: 50%;
  margin: 0 auto 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  position: relative;
  z-index: 1;
}

.profile-avatar i {
  font-size: 60px;
  color: #667eea;
}

.profile-name {
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 8px;
  position: relative;
  z-index: 1;
}

.profile-email {
  font-size: 16px;
  opacity: 0.9;
  position: relative;
  z-index: 1;
}

.profile-body {
  padding: 40px;
}

.profile-section-title {
  font-size: 20px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 25px;
  padding-bottom: 12px;
  border-bottom: 2px solid #e2e8f0;
}

.profile-info-item {
  display: flex;
  align-items: center;
  padding: 18px 0;
  border-bottom: 1px solid #f7fafc;
}

.profile-info-item:last-child {
  border-bottom: none;
}

.profile-info-label {
  font-weight: 600;
  color: #4a5568;
  min-width: 150px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.profile-info-label i {
  font-size: 20px;
  color: #667eea;
}

.profile-info-value {
  color: #718096;
  flex: 1;
}

.role-badge {
  display: inline-block;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.role-badge.user {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.role-badge.contributor {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
}

.edit-profile-btn {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 12px 30px;
  border-radius: 25px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.edit-profile-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  color: white;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.stat-card {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 20px;
  border-radius: 15px;
  text-align: center;
  transition: all 0.3s;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 12px;
}

.stat-icon i {
  font-size: 24px;
  color: #667eea;
}

.stat-value {
  font-size: 24px;
  font-weight: 700;
  color: #2d3748;
  margin-bottom: 5px;
}

.stat-label {
  font-size: 13px;
  color: #718096;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

@media (max-width: 768px) {
  .profile-body {
    padding: 30px 20px;
  }
  
  .profile-info-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
  
  .profile-info-label {
    min-width: auto;
  }
}
</style>

<div class="profile-container">
  <div class="container">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="max-width: 800px; margin: 0 auto 30px;">
        <i class="bx bx-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <div class="profile-card">
      <!-- Profile Header -->
      <div class="profile-header">
        <div class="profile-avatar">
          <i class="bx bx-user"></i>
        </div>
        <h1 class="profile-name">{{ $user->name }}</h1>
        <p class="profile-email">{{ $user->email }}</p>
      </div>

      <!-- Profile Body -->
      <div class="profile-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="profile-section-title mb-0">Profile Information</h2>
          <a href="{{ route('profile.edit') }}" class="edit-profile-btn">
            <i class="bx bx-edit"></i>
            Edit Profile
          </a>
        </div>

        <!-- Profile Information -->
        <div class="profile-info">
          <div class="profile-info-item">
            <div class="profile-info-label">
              <i class="bx bx-shield"></i>
              Role:
            </div>
            <div class="profile-info-value">
              @if($user->role === 'admin')
                <span class="role-badge admin">Admin</span>
              @elseif($user->role === 'contributor')
                <span class="role-badge contributor">Contributor</span>
              @else
                <span class="role-badge user">User</span>
              @endif
            </div>
          </div>

          <div class="profile-info-item">
            <div class="profile-info-label">
              <i class="bx bx-calendar"></i>
              Member Since:
            </div>
            <div class="profile-info-value">
              {{ $user->created_at->format('F d, Y') }}
            </div>
          </div>

          <div class="profile-info-item">
            <div class="profile-info-label">
              <i class="bx bx-time"></i>
              Last Updated:
            </div>
            <div class="profile-info-value">
              {{ $user->updated_at->diffForHumans() }}
            </div>
          </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-icon">
              <i class="bx bx-library"></i>
            </div>
            <div class="stat-value">{{ $user->bibliotheques_count ?? 0 }}</div>
            <div class="stat-label">Libraries</div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="bx bx-book"></i>
            </div>
            <div class="stat-value">{{ $user->livres_count ?? 0 }}</div>
            <div class="stat-label">Books</div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="bx bx-star"></i>
            </div>
            <div class="stat-value">{{ $user->avis_count ?? 0 }}</div>
            <div class="stat-label">Reviews</div>
          </div>

          <div class="stat-card">
            <div class="stat-icon">
              <i class="bx bx-book-open"></i>
            </div>
            <div class="stat-value">{{ $user->emprunts_count ?? 0 }}</div>
            <div class="stat-label">Emprunts</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
