@extends('layouts/contentNavbarLayout')

@section('title', 'Edit User - BookShare')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">Edit User</h4>
          <p class="text-muted">Update user information and permissions</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back me-1"></i> Back to Users
        </a>
      </div>
    </div>
  </div>

  <!-- Edit User Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">User Information</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.users.update', $user) }}" method="POST">
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

            <div class="mb-4">
              <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
              <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="contributor" {{ old('role', $user->role) == 'contributor' ? 'selected' : '' }}>Contributor</option>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
              </select>
              @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                Cancel
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bx bx-save me-1"></i> Update User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- User Stats Card -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-user me-1"></i> User Statistics
          </h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-6 mb-3">
              <div class="border-end">
                <h4 class="mb-1 text-primary">{{ $user->bibliotheques_count }}</h4>
                <small class="text-muted">Libraries</small>
              </div>
            </div>
            <div class="col-6 mb-3">
              <h4 class="mb-1 text-success">{{ $user->livres_count }}</h4>
              <small class="text-muted">Books</small>
            </div>
          </div>
          <hr>
          <div class="mb-2">
            <strong>Member Since:</strong><br>
            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
          </div>
          <div class="mb-0">
            <strong>Last Updated:</strong><br>
            <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
