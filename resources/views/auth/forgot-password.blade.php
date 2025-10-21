@extends('front.layouts.app')

@section('title', 'Forgot Password - BookVerse')

@section('content')

<style>
.forgot-password-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px 20px;
  position: relative;
  overflow: hidden;
}

.forgot-password-container::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
  opacity: 0.3;
}

.forgot-password-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.3);
  max-width: 500px;
  width: 100%;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.forgot-password-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 40px;
  text-align: center;
  color: white;
}

.forgot-password-icon {
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

.forgot-password-icon i {
  font-size: 40px;
  color: #667eea;
}

.forgot-password-header h1 {
  font-size: 28px;
  font-weight: 700;
  margin: 0 0 10px;
}

.forgot-password-header p {
  font-size: 15px;
  opacity: 0.9;
  margin: 0;
}

.forgot-password-body {
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

.back-to-login {
  text-align: center;
  margin-top: 20px;
}

.back-to-login a {
  color: #667eea;
  text-decoration: none;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 5px;
  transition: all 0.3s;
}

.back-to-login a:hover {
  color: #764ba2;
  gap: 8px;
}

.alert {
  border-radius: 10px;
  border: none;
  padding: 14px 18px;
  margin-bottom: 20px;
}

.alert-success {
  background: #d4edda;
  color: #155724;
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

@media (max-width: 576px) {
  .forgot-password-header {
    padding: 30px 20px;
  }
  
  .forgot-password-body {
    padding: 30px 20px;
  }
  
  .forgot-password-header h1 {
    font-size: 24px;
  }
}
</style>

<div class="forgot-password-container">
  <div class="forgot-password-card">
    <!-- Header -->
    <div class="forgot-password-header">
      <div class="forgot-password-icon">
        <i class="bx bx-lock-alt"></i>
      </div>
      <h1>Forgot Password?</h1>
      <p>No worries! Enter your email and we'll send you reset instructions.</p>
    </div>

    <!-- Body -->
    <div class="forgot-password-body">
      @if(session('success'))
        <div class="alert alert-success">
          <i class="bx bx-check-circle me-2"></i>
          {{ session('success') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <i class="bx bx-error-circle me-2"></i>
          @foreach($errors->all() as $error)
            {{ $error }}
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
          <label for="email" class="form-label">
            <i class="bx bx-envelope"></i>
            Email Address
          </label>
          <input type="email" 
                 class="form-control @error('email') is-invalid @enderror" 
                 id="email" 
                 name="email" 
                 value="{{ old('email') }}" 
                 placeholder="Enter your email address"
                 required 
                 autofocus>
          @error('email')
            <span class="invalid-feedback">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="btn-reset">
          <i class="bx bx-send me-2"></i>
          Send Reset Link
        </button>
      </form>

      <div class="back-to-login">
        <a href="{{ route('auth') }}">
          <i class="bx bx-arrow-back"></i>
          Back to Login
        </a>
      </div>
    </div>
  </div>
</div>

@endsection
