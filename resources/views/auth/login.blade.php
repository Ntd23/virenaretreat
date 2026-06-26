@extends('layouts.auth')

@section('content')
@php
    $login_bg_image_id = setting_item('login_bg_image');
    $login_bg_url = null;
    if ($login_bg_image_id) {
        $login_bg_url = get_file_url($login_bg_image_id, 'full');
    }

    $logo_id = setting_item('logo_id');
    $logo_url = null;
    if ($logo_id) {
        $logo_url = get_file_url($logo_id, 'full');
    }

    $site_title = setting_item_with_lang('site_title') ?? config('app.name');
    $hero_title    = setting_item_with_lang('login_hero_title') ?: __('Welcome Back');
    $hero_subtitle = setting_item_with_lang('login_hero_subtitle') ?: __('Sign in to continue your journey and explore amazing experiences with us.');
@endphp

<div class="bravo-auth-page">
    {{-- Left Panel: Background Image --}}
    <div class="auth-left-panel" @if($login_bg_url) style="background-image: url('{{ $login_bg_url }}')" @endif>
        <div class="auth-left-overlay"></div>
        <div class="auth-left-content">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="auth-logo">
                @if($logo_url)
                    <img src="{{ $logo_url }}" alt="{{ $site_title }}">
                @else
                    <span class="auth-logo-text">{{ $site_title }}</span>
                @endif
            </a>

            {{-- Hero Text --}}
            <div class="auth-hero-text">
                <h2>{{ $hero_title }}</h2>
                <p>{{ $hero_subtitle }}</p>
            </div>

            {{-- Trust Badge --}}
            <div class="auth-trust-badge">
                <div class="trust-icon">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="trust-text">
                    <strong>{{ __('Trusted Experience') }}</strong>
                    <span>{{ __('Thousands of happy customers') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel: Login Form --}}
    <div class="auth-right-panel">
        <div class="auth-form-wrapper">
            @include('Layout::admin.message')

            <div class="auth-form-header">
                <h1>{{ __('Sign In') }}</h1>
                <p>{{ __('Welcome back! Please enter your details.') }}</p>
            </div>

            <form class="auth-form" method="POST" action="{{ route('login') }}" id="bravo-login-form">
                <input type="hidden" name="redirect" value="{{ request()->query('redirect') }}">
                @csrf

                {{-- Email Field --}}
                <div class="auth-field-group">
                    <label for="login-email">{{ __('Email address') }}</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><i class="fa fa-envelope-o"></i></span>
                        <input
                            type="text"
                            id="login-email"
                            class="auth-input"
                            name="email"
                            autocomplete="off"
                            placeholder="{{ __('Enter your email') }}"
                        >
                    </div>
                    <span class="auth-error error error-email"></span>
                </div>

                {{-- Password Field --}}
                <div class="auth-field-group">
                    <label for="login-password">{{ __('Password') }}</label>
                    <div class="auth-input-wrapper">
                        <span class="auth-input-icon"><i class="fa fa-lock"></i></span>
                        <input
                            type="password"
                            id="login-password"
                            class="auth-input"
                            name="password"
                            autocomplete="off"
                            placeholder="{{ __('Enter your password') }}"
                        >
                        <button type="button" class="auth-toggle-password" id="toggle-password" aria-label="Toggle password">
                            <i class="fa fa-eye-slash" id="toggle-password-icon"></i>
                        </button>
                    </div>
                    <span class="auth-error error error-password"></span>
                </div>

                {{-- Remember & Forgot --}}
                <div class="auth-options-row">
                    <label class="auth-checkbox-label" for="remember-me">
                        <input type="checkbox" name="remember" id="remember-me" value="1">
                        <span class="auth-checkmark"></span>
                        {{ __('Remember me') }}
                    </label>
                    <a href="{{ route('password.request') }}" class="auth-forgot-link">{{ __('Forgot password?') }}</a>
                </div>

                @if(setting_item("user_enable_login_recaptcha"))
                    <div class="auth-field-group">
                        {{ recaptcha_field($captcha_action ?? 'login') }}
                    </div>
                @endif

                <div class="auth-error-message error message-error invalid-feedback"></div>

                {{-- Submit Button --}}
                <button type="submit" class="auth-btn-submit" id="auth-submit-btn">
                    {{ __('Sign In') }}
                    <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
                </button>

                {{-- Social Login --}}
                @if(setting_item('facebook_enable') || setting_item('google_enable') || setting_item('twitter_enable'))
                    <div class="auth-social-divider">
                        <span>{{ __('Or continue with') }}</span>
                    </div>
                    <div class="auth-social-buttons">
                        @if(setting_item('google_enable'))
                            <a href="{{ url('social-login/google') }}" class="auth-social-btn auth-social-google" data-channel="google">
                                <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.875 2.684-6.615z" fill="#4285F4"/><path d="M9 18c2.43 0 4.467-.806 5.956-2.18l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 009 18z" fill="#34A853"/><path d="M3.964 10.71A5.41 5.41 0 013.682 9c0-.593.102-1.17.282-1.71V4.958H.957A8.996 8.996 0 000 9c0 1.452.348 2.827.957 4.042l3.007-2.332z" fill="#FBBC05"/><path d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 00.957 4.958L3.964 6.29C4.672 4.163 6.656 3.58 9 3.58z" fill="#EA4335"/></svg>
                                Google
                            </a>
                        @endif
                        @if(setting_item('facebook_enable'))
                            <a href="{{ url('/social-login/facebook') }}" class="auth-social-btn auth-social-facebook" data-channel="facebook">
                                <i class="fa fa-facebook"></i>
                                Facebook
                            </a>
                        @endif
                        @if(setting_item('twitter_enable'))
                            <a href="{{ url('social-login/twitter') }}" class="auth-social-btn auth-social-twitter" data-channel="twitter">
                                <i class="fa fa-twitter"></i>
                                Twitter
                            </a>
                        @endif
                    </div>
                @endif

                {{-- Register Link --}}
                @if(is_enable_registration())
                    <p class="auth-register-link">
                        {{ __("Don't have an account?") }}
                        <a href="{{ route('auth.register') }}">{{ __('Sign up now') }}</a>
                    </p>
                @endif
            </form>
        </div>
    </div>
</div>

@push('js')
<script>
(function() {
    var toggleBtn = document.getElementById('toggle-password');
    var passwordInput = document.getElementById('login-password');
    var toggleIcon = document.getElementById('toggle-password-icon');

    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'fa fa-eye';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'fa fa-eye-slash';
            }
        });
    }
})();
</script>
@endpush

@push('css')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>

/* ===== AUTH PAGE LAYOUT ===== */
*, *::before, *::after { box-sizing: border-box; }

html, body {
    height: 100vh !important;
    overflow: hidden !important;
    margin: 0;
    padding: 0;
}

.bravo_wrap {
    height: 100vh !important;
    overflow: hidden !important;
}

.bravo-auth-page {
    display: flex;
    height: 100vh !important;
    width: 100%;
    overflow: hidden !important;
    font-family: 'Inter', sans-serif;
}

/* ===== LEFT PANEL ===== */
.auth-left-panel {
    flex: 0 0 50%;
    max-width: 50%;
    position: relative;
    background-color: #1a2435;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.auth-left-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(10, 15, 30, 0.45) 0%,
        rgba(10, 15, 30, 0.25) 40%,
        rgba(10, 15, 30, 0.75) 100%
    );
    z-index: 1;
}

.auth-left-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
    padding: 40px;
}

/* Logo */
.auth-logo {
    display: inline-block;
    text-decoration: none !important;
}

.auth-logo img {
    max-height: 52px;
    width: auto;
    filter: brightness(0) invert(1);
}

.auth-logo-text {
    color: #fff;
    font-size: 22px;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

/* Hero Text */
.auth-hero-text {
    text-align: left;
}

.auth-hero-text h2 {
    color: #ffffff;
    font-size: clamp(30px, 3.2vw, 50px);
    font-weight: 700;
    line-height: 1.18;
    font-family: 'Playfair Display', Georgia, serif;
    margin: 0 0 16px;
    letter-spacing: -0.5px;
}

.auth-hero-text p {
    color: rgba(255, 255, 255, 0.82);
    font-size: 15px;
    line-height: 1.7;
    max-width: 380px;
    margin: 0;
}

/* Trust Badge */
.auth-trust-badge {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 14px;
    padding: 14px 20px;
    width: fit-content;
    max-width: 340px;
}

.trust-icon {
    width: 42px;
    height: 42px;
    background: rgba(197, 168, 128, 0.25);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.trust-icon i {
    color: #c5a880;
    font-size: 18px;
}

.trust-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.trust-text strong {
    color: #ffffff;
    font-size: 13px;
    font-weight: 600;
}

.trust-text span {
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
}

/* ===== RIGHT PANEL ===== */
.auth-right-panel {
    flex: 0 0 50%;
    max-width: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #ffffff;
    padding: 40px 20px;
    overflow-y: auto;
}

.auth-form-wrapper {
    width: 100%;
    max-width: 420px;
}

/* Form Header */
.auth-form-header {
    text-align: center;
    margin-bottom: 36px;
}

.auth-form-header h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0 0 8px;
    letter-spacing: -0.3px;
    font-family: 'Playfair Display', Georgia, serif;
}

.auth-form-header p {
    font-size: 14.5px;
    color: #6b7280;
    margin: 0;
    font-family: 'Inter', sans-serif;
}

/* Field Groups */
.auth-field-group {
    margin-bottom: 20px;
}

.auth-field-group label {
    display: block;
    font-size: 13.5px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 7px;
}

.auth-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.auth-input-icon {
    position: absolute;
    left: 14px;
    color: #9ca3af;
    font-size: 15px;
    z-index: 1;
    pointer-events: none;
    display: flex;
    align-items: center;
}

.auth-input {
    width: 100%;
    height: 50px;
    padding: 0 42px 0 42px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    font-size: 14px;
    color: #1f2937;
    background: #fafafa;
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.auth-input:focus {
    border-color: #c5a880;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.12);
}

.auth-input::placeholder {
    color: #9ca3af;
    font-weight: 400;
}

.auth-toggle-password {
    position: absolute;
    right: 14px;
    background: none;
    border: none;
    cursor: pointer;
    color: #9ca3af;
    font-size: 15px;
    padding: 0;
    display: flex;
    align-items: center;
    transition: color 0.2s;
}

.auth-toggle-password:hover {
    color: #6b7280;
}

.auth-error {
    display: block;
    font-size: 12px;
    color: #ef4444;
    margin-top: 5px;
}

.auth-error-message {
    font-size: 13px;
    color: #ef4444;
    margin-bottom: 12px;
    min-height: 0;
}

/* Options Row */
.auth-options-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.auth-checkbox-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    color: #374151;
    cursor: pointer;
    user-select: none;
}

.auth-checkbox-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #c5a880;
    cursor: pointer;
}

.auth-forgot-link {
    font-size: 13.5px;
    color: #c5a880;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s;
}

.auth-forgot-link:hover {
    color: #b39268;
    text-decoration: underline;
}

/* Submit Button */
.auth-btn-submit {
    width: 100%;
    height: 52px;
    background: linear-gradient(135deg, #c5a880 0%, #b39268 100%);
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'Inter', sans-serif;
    text-transform: uppercase;
    box-shadow: 0 4px 15px rgba(197, 168, 128, 0.35);
}

.auth-btn-submit:hover {
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(197, 168, 128, 0.45);
    background: linear-gradient(135deg, #b39268 0%, #9e7f56 100%);
}

.auth-btn-submit:active {
    transform: translateY(0);
}

.auth-btn-submit .icon-loading {
    display: none;
}

.auth-btn-submit.loading .icon-loading {
    display: inline-block;
}

/* Social Divider */
.auth-social-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: #9ca3af;
    font-size: 13px;
}

.auth-social-divider::before,
.auth-social-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}

/* Social Buttons */
.auth-social-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.auth-social-btn {
    flex: 1;
    min-width: 120px;
    height: 46px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    font-size: 13.5px;
    font-weight: 500;
    color: #374151;
    background: #ffffff;
    text-decoration: none;
    transition: all 0.2s ease;
}

.auth-social-btn:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #111827;
    text-decoration: none;
}

.auth-social-btn i {
    font-size: 16px;
}

.auth-social-facebook i { color: #1877f2; }
.auth-social-twitter i { color: #1da1f2; }

/* Register Link */
.auth-register-link {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 0;
}

.auth-register-link a {
    color: #c5a880;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
}

.auth-register-link a:hover {
    color: #b39268;
    text-decoration: underline;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    html, body {
        height: auto !important;
        overflow: auto !important;
    }
    .bravo_wrap {
        height: auto !important;
        overflow: auto !important;
    }
    .bravo-auth-page {
        flex-direction: column;
        height: auto !important;
        min-height: 100vh !important;
        overflow: auto !important;
    }

    .auth-left-panel {
        flex: 0 0 260px;
        max-width: 100%;
        min-height: 260px;
        height: 260px !important;
    }

    .auth-left-content {
        padding: 28px 24px;
    }

    .auth-hero-text h2 {
        font-size: 26px;
    }

    .auth-trust-badge {
        display: none;
    }

    .auth-right-panel {
        flex: 1;
        max-width: 100%;
        padding: 32px 20px;
        height: auto !important;
        overflow-y: visible !important;
    }
}
</style>
@endpush
@endsection
