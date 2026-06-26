@extends('layouts.auth')

@section('content')
@php
    $register_bg_image_id = setting_item('register_bg_image');
    $register_bg_url = null;
    if ($register_bg_image_id) {
        $register_bg_url = get_file_url($register_bg_image_id, 'full');
    }

    $logo_id = setting_item('logo_id');
    $logo_url = null;
    if ($logo_id) {
        $logo_url = get_file_url($logo_id, 'full');
    }

    $site_title = setting_item_with_lang('site_title') ?? config('app.name');
    $hero_title    = setting_item_with_lang('register_hero_title') ?: __('Join Us Now');
    $hero_subtitle = setting_item_with_lang('register_hero_subtitle') ?: __('Create your account to start your journey and explore amazing experiences with us.');
@endphp

<div class="bravo-auth-page">
    {{-- Left Panel: Background Image --}}
    <div class="auth-left-panel" @if($register_bg_url) style="background-image: url('{{ $register_bg_url }}')" @endif>
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

    {{-- Right Panel: Register Form --}}
    <div class="auth-right-panel">
        <div class="auth-form-wrapper">
            @include('Layout::admin.message')

            <div class="auth-form-header">
                <h1>{{ __('Register') }}</h1>
                <p>{{ __('Create your account to explore our premium features.') }}</p>
            </div>

            <div class="auth-form-body">
                @include('Layout::auth.register-form', ['captcha_action' => 'register_normal', 'is_page' => true])
            </div>
        </div>
    </div>
</div>

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
    margin-bottom: 30px;
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

/* Form inputs & styling */
.bravo-form-register .form-group {
    position: relative;
    margin-bottom: 20px;
}

.bravo-form-register .form-group label {
    display: block;
    font-size: 13.5px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 7px;
}

.bravo-form-register .form-control {
    width: 100%;
    height: 48px;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 0 16px 0 46px;
    font-size: 14.5px;
    color: #1f2937;
    transition: all 0.25s ease;
    font-family: 'Inter', sans-serif;
}

.bravo-form-register .form-control:focus {
    background: #ffffff;
    border-color: #c5a880;
    outline: none;
    box-shadow: 0 0 0 4px rgba(197, 168, 128, 0.15);
}

.bravo-form-register .form-group .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 18px;
    transition: color 0.25s ease;
    pointer-events: none;
}

.bravo-form-register .form-control:focus + .input-icon {
    color: #c5a880;
}

/* Submit Button */
.bravo-form-register .form-submit {
    width: 100%;
    height: 50px;
    background: #1a1a2e;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 600;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 10px;
    font-family: 'Inter', sans-serif;
}

.bravo-form-register .form-submit:hover {
    background: #2e2e4f;
    transform: translateY(-1px);
}

.bravo-form-register .form-submit:active {
    transform: translateY(0);
}

.bravo-form-register .form-submit .icon-loading {
    display: none;
}

.bravo-form-register .form-submit.loading .icon-loading {
    display: inline-block;
}

/* Social options */
.bravo-form-register .advanced {
    margin-top: 28px;
    margin-bottom: 24px;
}

.bravo-form-register .advanced p {
    position: relative;
    text-align: center;
    font-size: 13.5px;
    color: #9ca3af;
    margin-bottom: 20px;
}

.bravo-form-register .advanced p::before,
.bravo-form-register .advanced p::after {
    content: '';
    position: absolute;
    top: 50%;
    width: calc(50% - 70px);
    height: 1px;
    background: #e5e7eb;
}

.bravo-form-register .advanced p::before { left: 0; }
.bravo-form-register .advanced p::after { right: 0; }

.bravo-form-register .advanced .row {
    display: flex !important;
    gap: 12px !important;
    margin: 0 !important;
}

.bravo-form-register .advanced .row > div {
    flex: 1 1 0% !important;
    max-width: 100% !important;
    padding: 0 !important;
}

.bravo-form-register .btn_login_fb_link,
.bravo-form-register .btn_login_gg_link,
.bravo-form-register .btn_login_tw_link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    height: 46px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #ffffff;
    color: #4b5563;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    font-family: 'Inter', sans-serif;
}

.bravo-form-register .btn_login_fb_link:hover,
.bravo-form-register .btn_login_gg_link:hover,
.bravo-form-register .btn_login_tw_link:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #1f2937;
}

/* Checkbox */
.bravo-form-register label[for="term"] {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 13.5px;
    color: #4b5563;
    cursor: pointer;
    line-height: 1.5;
    margin-top: 15px;
}

.bravo-form-register label[for="term"] input[type="checkbox"] {
    margin-top: 3px;
}

.bravo-form-register label[for="term"] a {
    color: #c5a880;
    text-decoration: none;
    font-weight: 500;
}

.bravo-form-register label[for="term"] a:hover {
    text-decoration: underline;
}

/* Register footer link */
.bravo-form-register .c-grey {
    font-size: 14.5px;
    color: #6b7280;
    margin-top: 24px;
}

.bravo-form-register .c-grey a {
    color: #c5a880;
    text-decoration: none;
    font-weight: 600;
    margin-left: 4px;
}

.bravo-form-register .c-grey a:hover {
    text-decoration: underline;
}

/* Responsive */
@media(max-width: 991px) {
    html, body {
        height: auto !important;
        overflow: auto !important;
    }
    .bravo_wrap {
        height: auto !important;
        overflow: auto !important;
    }
    .bravo-auth-page {
        height: auto !important;
        min-height: 100vh !important;
        overflow: auto !important;
    }
    .auth-left-panel {
        display: none;
    }
    .auth-right-panel {
        flex: 0 0 100%;
        max-width: 100%;
        padding: 60px 24px;
        height: auto !important;
        overflow-y: visible !important;
    }
}
</style>
@endpush
