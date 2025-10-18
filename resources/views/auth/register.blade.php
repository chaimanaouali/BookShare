@extends('front.layouts.app')

@section('title', 'Registration')

@section('content')
<div class="auth-container">
    <div class="forms-auth-container">
        <div class="signin-signup">
            <!-- Sign In Form -->
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form action="{{ route('auth.login') }}" method="POST" class="sign-in-form">
    @csrf
    <h2 class="title">Sign in</h2>
    <div class="input-field">
        <i class="fas fa-user"></i>
        <input type="email" name="email" placeholder="Email" required />
    </div>
    <div class="input-field">
        <i class="fas fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required />
    </div>

    <input type="submit" value="Login" class="btn solid" />
    <p class="social-text">Or Sign in with social platforms</p>
    <div class="social-media">
        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
    </div>
</form>

            <!-- Sign Up Form -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('auth.register') }}" method="POST" class="sign-up-form">
                @csrf
                <h2 class="title">Sign up</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="Name" required />
                </div>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required />
                </div>
                <input type="submit" class="btn" value="Sign up" />
                <p class="social-text">Or Sign up with social platforms</p>
                <div class="social-media">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <div id="signup-message" style="margin-top:10px;"></div>
                <div id="signup-errors" style="margin-top:10px; color:red;"></div>
            </form>
        </div>
    </div>

    <div class="panels-auth-container">
        <div class="panel left-panel">
            <div class="content">
                <h3>New here?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Debitis, ex ratione. Aliquid!</p>
                <button class="btn transparent" id="sign-up-btn">Sign up</button>
            </div>
            <img src="{{ asset('assets/images/log.svg') }}" class="image" alt="Sign Up Illustration" />
        </div>
        <div class="panel right-panel">
            <div class="content">
                <h3>One of us?</h3>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nostrum laboriosam ad deleniti.</p>
                <button class="btn transparent" id="sign-in-btn">Sign in</button>
            </div>
            <img src="{{ asset('assets/images/register.svg') }}" class="image" alt="Sign In Illustration" />
        </div>
    </div>
</div>
@endsection

@section('extra-css')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('extra-js')
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".auth-container");
        const signupForm = document.querySelector('.sign-up-form');
        const signinForm = document.querySelector('.sign-in-form');

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });

    });
</script>
@endsection
