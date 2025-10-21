@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Profile - BookShare')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">Edit Profile</h4>
          <p class="text-muted">Update your account information</p>
        </div>
        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back me-1"></i> Back to Profile
        </a>
      </div>
    </div>
  </div>

  <!-- Edit Profile Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Profile Information</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password">
                <div class="form-text">Leave blank to keep current password</div>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation">
              </div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                Cancel
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i> Update Profile
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Profile Info Card -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-user me-1"></i> Profile Information
          </h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <strong>Current Role:</strong><br>
            @if($user->role === 'admin')
              <span class="badge bg-danger">Admin</span>
            @elseif($user->role === 'contributor')
              <span class="badge bg-warning">Contributor</span>
            @else
              <span class="badge bg-info">User</span>
            @endif
          </div>

          <div class="mb-3">
            <strong>Member Since:</strong><br>
            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
          </div>

          <div class="mb-0">
            <strong>Last Updated:</strong><br>
            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
          </div>
        </div>
      </div>

      <!-- Security Tips -->
      <div class="card mt-3">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-shield me-1"></i> Security Tips
          </h5>
        </div>
        <div class="card-body">
          <ul class="list-unstyled mb-0">
            <li class="mb-2">
              <i class="bx bx-check text-success me-2"></i>
              Use a strong password
            </li>
            <li class="mb-2">
              <i class="bx bx-check text-success me-2"></i>
              Keep your email updated
            </li>
            <li class="mb-0">
              <i class="bx bx-check text-success me-2"></i>
              Don't share your credentials
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
