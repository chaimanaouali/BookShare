@extends('front.layouts.app')

@section('title', 'Reset Password - BookVerse')

@section('content')

<style>
.reset-password-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px 20px;
  position: relative;
  overflow: hidden;
}

.reset-password-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
  opacity: 0.3;
}

.reset-password-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
  max-width: 500px;
  width: 100%;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.reset-password-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px;
  text-align: center;
  color: white;
}

.reset-password-icon {
  width: 80px;
  height: 80px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 20px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

.reset-password-icon i {
  font-size: 40px;
  color: #667eea;
}

.reset-password-header h1 {
  font-size: 28px;
  font-weight: 700;
  margin: 0 0 10px;
}

.reset-password-header p {
  font-size: 15px;
  opacity: 0.9;
  margin: 0;
}

.reset-password-body {
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
  padding: 14px 16px;
  font-size: 15px;
  transition: all 0.3s;
  width: 100%;
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

.btn-reset {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 14px;
  border-radius: 10px;
  font-weight: 600;
  font-size: 16px;
  width: 100%;
  transition: all 0.3s;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  cursor: pointer;
}

.btn-reset:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
}

.alert {
  border-radius: 10px;
  border: none;
  padding: 14px 18px;
  margin-bottom: 20px;
}

.alert-danger {
  background: #f8d7da;
  color: #721c24;
}

.invalid-feedback {
  color: #e53e3e;
  font-size: 13px;
  margin-top: 6px;
  display: block;
}

.password-requirements {
  background: #f7fafc;
  border-radius: 10px;
  padding: 15px;
  margin-top: 20px;
}

.password-requirements h6 {
  font-size: 14px;
  font-weight: 600;
  color: #2d3748;
  margin-bottom: 10px;
}

.password-requirements ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.password-requirements li {
  font-size: 13px;
  color: #718096;
  padding: 4px 0;
  display: flex;
  align-items: center;
  gap: 8px;
}

.password-requirements li i {
  color: #667eea;
  font-size: 16px;
}

@media (max-width: 576px) {
  .reset-password-header {
    padding: 30px 20px;
  }
  
  .reset-password-body {
    padding: 30px 20px;
  }
  
  .reset-password-header h1 {
    font-size: 24px;
  }
}
</style>

<div class="reset-password-container">
  <div class="reset-password-card">
    <!-- Header -->
    <div class="reset-password-header">
      <div class="reset-password-icon">
        <i class="bx bx-key"></i>
      </div>
      <h1>Reset Password</h1>
      <p>Create a new secure password for your account</p>
    </div>

    <!-- Body -->
    <div class="reset-password-body">
      @if($errors->any())
        <div class="alert alert-danger">
          <i class="bx bx-error-circle me-2"></i>
          <strong>Please fix the following errors:</strong>
          <ul class="mb-0 mt-2" style="list-style: none; padding-left: 0;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <!-- Email (read-only) -->
        <div class="form-group">
          <label for="email" class="form-label">
            <i class="bx bx-envelope"></i>
            Email Address
          </label>
          <input type="email" 
                 class="form-control" 
                 id="email" 
                 value="{{ $email }}" 
                 readonly>
        </div>

        <!-- New Password -->
        <div class="form-group">
          <label for="password" class="form-label">
            <i class="bx bx-lock"></i>
            New Password
          </label>
          <input type="password" 
                 class="form-control @error('password') is-invalid @enderror" 
                 id="password" 
                 name="password" 
                 placeholder="Enter new password"
                 required>
          @error('password')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
          <label for="password_confirmation" class="form-label">
            <i class="bx bx-check-shield"></i>
            Confirm Password
          </label>
          <input type="password" 
                 class="form-control" 
                 id="password_confirmation" 
                 name="password_confirmation" 
                 placeholder="Confirm new password"
                 required>
        </div>

        <!-- Password Requirements -->
        <div class="password-requirements">
          <h6><i class="bx bx-info-circle"></i> Password Requirements:</h6>
          <ul>
            <li><i class="bx bx-check"></i> At least 8 characters long</li>
            <li><i class="bx bx-check"></i> Both passwords must match</li>
          </ul>
        </div>

        <button type="submit" class="btn-reset mt-4">
          <i class="bx bx-save me-2"></i>
          Reset Password
        </button>
      </form>
    </div>
  </div>
</div>

@endsection
