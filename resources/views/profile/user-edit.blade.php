@extends('front.layouts.app')

@section('title', 'Edit Profile - BookVerse')

@section('content')
@include('front.partials.header')

<style>
.edit-profile-container {
  min-height: calc(100vh - 200px);
  padding: 60px 0;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.edit-profile-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 40px rgba(0,0,0,0.1);
  overflow: hidden;
  max-width: 700px;
  margin: 0 auto;
}

.edit-profile-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 30px 40px;
  color: white;
}

.edit-profile-header h1 {
  font-size: 28px;
  font-weight: 700;
  margin: 0;
}

.edit-profile-header p {
  margin: 8px 0 0;
  opacity: 0.9;
}

.edit-profile-body {
  padding: 40px;
}

.form-group {
  margin-bottom: 25px;
}

.form-label {
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 8px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.form-label i {
  color: #667eea;
  font-size: 18px;
}

.form-control {
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  padding: 12px 16px;
  font-size: 15px;
  transition: all 0.3s;
}

.form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  outline: none;
}

.form-text {
  color: #718096;
  font-size: 13px;
  margin-top: 6px;
}

.btn-save {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 14px 40px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-save:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  color: white;
}

.btn-cancel {
  background: #e2e8f0;
  color: #4a5568;
  border: none;
  padding: 14px 40px;
  border-radius: 25px;
  font-weight: 600;
  font-size: 16px;
  transition: all 0.3s;
  text-decoration: none;
  display: inline-block;
}

.btn-cancel:hover {
  background: #cbd5e0;
  color: #2d3748;
}

.password-section {
  background: #f7fafc;
  padding: 25px;
  border-radius: 15px;
  margin-top: 30px;
}

.password-section-title {
  font-size: 18px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}

.password-section-title i {
  color: #667eea;
}

.alert {
  border-radius: 12px;
  border: none;
  padding: 16px 20px;
}

.invalid-feedback {
  color: #e53e3e;
  font-size: 13px;
  margin-top: 6px;
}

@media (max-width: 768px) {
  .edit-profile-body {
    padding: 30px 20px;
  }
  
  .btn-save, .btn-cancel {
    width: 100%;
    margin-bottom: 10px;
  }
}
</style>

<div class="edit-profile-container">
  <div class="container">
    <div class="edit-profile-card">
      <!-- Header -->
      <div class="edit-profile-header">
        <h1><i class="bx bx-edit"></i> Edit Profile</h1>
        <p>Update your account information</p>
      </div>

      <!-- Body -->
      <div class="edit-profile-body">
        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong><i class="bx bx-error-circle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
          @csrf
          @method('PUT')

          <!-- Name -->
          <div class="form-group">
            <label for="name" class="form-label">
              <i class="bx bx-user"></i>
              Full Name
            </label>
            <input type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $user->name) }}" 
                   required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Email -->
          <div class="form-group">
            <label for="email" class="form-label">
              <i class="bx bx-envelope"></i>
              Email Address
            </label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $user->email) }}" 
                   required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password Section -->
          <div class="password-section">
            <h3 class="password-section-title">
              <i class="bx bx-lock"></i>
              Change Password
            </h3>
            <p class="form-text mb-3">Leave blank if you don't want to change your password</p>

            <!-- New Password -->
            <div class="form-group">
              <label for="password" class="form-label">
                <i class="bx bx-key"></i>
                New Password
              </label>
              <input type="password" 
                     class="form-control @error('password') is-invalid @enderror" 
                     id="password" 
                     name="password">
              <small class="form-text">Minimum 8 characters</small>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-0">
              <label for="password_confirmation" class="form-label">
                <i class="bx bx-check-shield"></i>
                Confirm Password
              </label>
              <input type="password" 
                     class="form-control" 
                     id="password_confirmation" 
                     name="password_confirmation">
            </div>
          </div>

          <!-- Buttons -->
          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn-save">
              <i class="bx bx-save me-2"></i>
              Save Changes
            </button>
            <a href="{{ route('profile.show') }}" class="btn-cancel">
              <i class="bx bx-x me-2"></i>
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
