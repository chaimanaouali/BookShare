@extends('layouts/contentNavbarLayout')

@section('title', 'My Profile - BookVerse')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1 fw-bold">My Profile</h4>
          <p class="text-muted mb-0">View and manage your account information</p>
        </div>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
          <i class="bx bx-edit me-1"></i> Edit Profile
        </a>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <i class="bx bx-check-circle me-2"></i>
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Profile Information Card -->
  <div class="row">
    <div class="col-lg-8 col-md-10 mx-auto">
      <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="card-title mb-0 fw-semibold">Profile Information</h5>
        </div>
        <div class="card-body p-4">
          <!-- User Avatar and Name -->
          <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
            <div class="avatar avatar-xl bg-primary rounded me-3" style="width: 70px; height: 70px; display: flex; align-items: center; justify-content: center;">
              <i class="bx bx-user" style="font-size: 32px; color: white;"></i>
            </div>
            <div>
              <h5 class="mb-1 fw-semibold">{{ $user->name }}</h5>
              <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
          </div>

          <!-- Profile Details -->
          <div class="row g-3">
            <!-- Role -->
            <div class="col-12">
              <div class="d-flex align-items-center">
                <strong class="text-dark" style="min-width: 140px;">Role:</strong>
                @if($user->role === 'admin')
                  <span class="badge bg-label-danger">Admin</span>
                @elseif($user->role === 'contributor')
                  <span class="badge bg-label-warning">Contributor</span>
                @else
                  <span class="badge bg-label-primary">User</span>
                @endif
              </div>
            </div>

            <!-- Member Since -->
            <div class="col-12">
              <div class="d-flex align-items-start">
                <strong class="text-dark" style="min-width: 140px;">Member Since:</strong>
                <span class="text-muted">{{ $user->created_at->format('M d, Y') }}</span>
              </div>
            </div>

            <!-- Last Updated -->
            <div class="col-12">
              <div class="d-flex align-items-start">
                <strong class="text-dark" style="min-width: 140px;">Last Updated:</strong>
                <span class="text-muted">{{ $user->updated_at->diffForHumans() }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
