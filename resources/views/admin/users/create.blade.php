@extends('layouts/contentNavbarLayout')

@section('title', 'Create User - BookShare')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Page Header -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h4 class="mb-1">Create New User</h4>
          <p class="text-muted">Add a new user to the system</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back me-1"></i> Back to Users
        </a>
      </div>
    </div>
  </div>

  <!-- Create User Form -->
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">User Information</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                       id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                       id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password" required>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation" required>
              </div>
            </div>

            <div class="mb-4">
              <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
              <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                <option value="">Select Role</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="contributor" {{ old('role') == 'contributor' ? 'selected' : '' }}>Contributor</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
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
                <i class="bx bx-save me-1"></i> Create User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Help Card -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="bx bx-help-circle me-1"></i> User Roles
          </h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <h6 class="text-danger">Admin</h6>
            <p class="small text-muted mb-0">Full access to all features including user management and system settings.</p>
          </div>

          <div class="mb-3">
            <h6 class="text-warning">Contributor</h6>
            <p class="small text-muted mb-0">Can create libraries, upload books, and manage their own content.</p>
          </div>

          <div class="mb-0">
            <h6 class="text-info">User</h6>
            <p class="small text-muted mb-0">Can browse books, participate in discussions, and borrow books.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
