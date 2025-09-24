@extends('front.layouts.app')

@section('title', 'Registration')

@section('content')
<div class="auth-container">
    <div class="forms-auth-container">
        <div class="signin-signup">
            <!-- Sign In Form -->
            <form action="#" class="sign-in-form">
                <h2 class="title">Sign in</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="email" name="email" placeholder="Email" />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" />
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
            <form action="#" class="sign-up-form">
                <h2 class="title">Sign up</h2>
                <div class="input-field">
                    <i class="fas fa-user"></i>
                    <input type="text" name="name" placeholder="Name" />
                </div>
                <div class="input-field">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" />
                </div>
                <div class="input-field">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password_confirmation" placeholder="Confirm Password" />
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

        async function api(url = '', method = 'GET', data = null) {
            const token = localStorage.getItem('auth_token');
            const headers = { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' };
            if (token) headers['Authorization'] = `Bearer ${token}`;
            const opts = { method, headers };
            if (data) opts.body = JSON.stringify(data);
            const res = await fetch(`/api${url}`, opts);
            const text = await res.text();
            try { return { ok: res.ok, status: res.status, json: JSON.parse(text) }; } catch { return { ok: res.ok, status: res.status, json: { message: text } }; }
        }

        if (signupForm) {
            signupForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const form = e.currentTarget;
                const payload = {
                    name: form.querySelector('input[name="name"]').value,
                    email: form.querySelector('input[name="email"]').value,
                    password: form.querySelector('input[name="password"]').value,
                    password_confirmation: form.querySelector('input[name="password_confirmation"]').value
                };
                const { ok, status, json } = await api('/auth/signup', 'POST', payload);
                const msg = document.getElementById('signup-message');
                const errs = document.getElementById('signup-errors');
                errs.innerHTML = '';
                if (ok) {
                    msg.style.color = 'green';
                    msg.textContent = 'Signed up!';
                    if (json?.data?.token) localStorage.setItem('auth_token', json.data.token);
                    const role = json?.data?.user?.role || 'user';
                    window.location.href = role === 'admin' ? '/dashboard' : '/';
                } else {
                    msg.style.color = 'red';
                    if (status === 422 && json?.errors) {
                        msg.textContent = 'Please fix the errors below:';
                        const list = document.createElement('ul');
                        for (const [field, messages] of Object.entries(json.errors)) {
                            const li = document.createElement('li');
                            li.textContent = `${field}: ${messages.join(', ')}`;
                            list.appendChild(li);
                        }
                        errs.appendChild(list);
                    } else {
                        msg.textContent = json?.message || 'Signup failed';
                    }
                }
            });
        }

        if (signinForm) {
            signinForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const form = e.currentTarget;
                const payload = {
                    email: form.querySelector('input[name="email"]').value,
                    password: form.querySelector('input[name="password"]').value
                };
                const { ok, json } = await api('/auth/login', 'POST', payload);
                if (ok && json?.data?.token) {
                    localStorage.setItem('auth_token', json.data.token);
                    const role = json?.data?.user?.role || 'user';
                    window.location.href = role === 'admin' ? '/dashboard' : '/';
                } else {
                    alert(json?.message || 'Login failed');
                }
            });
        }
    });
</script>
@endsection
