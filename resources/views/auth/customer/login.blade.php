{{--
 | Page: auth/customer/login.blade.php
 | Layout: layouts/guest
 | Source: customer_login_registration_desktop/code.html + customer_authentication_mobile/code.html
--}}
@extends('layouts.guest')

@php
    $title = 'LUXE | Secure Access';
@endphp

@section('content')

{{-- ── LOGIN VIEW ───────────────────────────────── --}}
<div class="fade-in-up" id="login-view">

    <header class="mb-5xl">
        <h1 class="font-h3 text-h3 text-on-background mb-xs">Welcome back</h1>
        <p class="font-body-default text-on-surface-variant">Please enter your details to access your account.</p>
    </header>

    {{-- Social Logins --}}
    <div class="flex gap-md mb-4xl">
        <a href="{{ route('auth.social', 'google') }}"
           aria-label="Sign in with Google"
           class="flex-1 flex items-center justify-center gap-sm py-md border border-outline-variant hover:bg-surface-container-low transition-all rounded-xl">
            <img src="{{ asset('images/google-icon.svg') }}" alt="Google" class="w-5 h-5"/>
            <span class="font-button text-button">Google</span>
        </a>
        <a href="{{ route('auth.social', 'apple') }}"
           aria-label="Sign in with Apple"
           class="flex-1 flex items-center justify-center gap-sm py-md border border-outline-variant hover:bg-surface-container-low transition-all rounded-xl">
            <span class="material-symbols-outlined text-xl">apple</span>
            <span class="font-button text-button">Apple</span>
        </a>
    </div>

    <div class="relative flex items-center gap-md mb-4xl">
        <div class="flex-grow border-t border-outline-variant/50"></div>
        <span class="font-caption text-on-surface-variant uppercase tracking-widest px-xs">or email</span>
        <div class="flex-grow border-t border-outline-variant/50"></div>
    </div>

    {{-- Login Form --}}
    <form class="space-y-4xl" id="login-form" method="POST" action="{{ route('auth.login.post') }}">
        @csrf

        <x-forms.input
            id="login-email"
            name="email"
            type="email"
            label="Email address"
            :value="old('email')"
            :required="true"
            :floatingLabel="true"
        />

        <div class="input-group relative">
            <input class="peer w-full h-11 bg-transparent border-0 border-b border-outline focus:border-primary focus:ring-0 px-0 transition-all font-body-default"
                   id="login-password" name="password" placeholder=" " required type="password"/>
            <label class="absolute left-0 top-3 text-on-surface-variant/60 font-body-default" for="login-password">Password</label>
            <button class="absolute right-0 top-3 text-on-surface-variant hover:text-primary transition-colors font-button text-caption uppercase"
                    onclick="togglePassword('login-password', this)" type="button">Show</button>
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-sm cursor-pointer group">
                <input class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 transition-all"
                       name="remember" type="checkbox"/>
                <span class="font-body-sm text-on-surface-variant group-hover:text-on-surface">Remember me</span>
            </label>
            <a class="font-body-sm text-primary hover:underline underline-offset-4"
               href="{{ route('auth.password.request') }}">Forgot password?</a>
        </div>

        <button class="relative overflow-hidden w-full bg-primary-container hover:bg-primary-container/90 text-on-primary font-button py-md rounded-xl transition-all active:scale-[0.98] shadow-md"
                id="login-btn" type="submit">
            Sign In
        </button>

    </form>

    <footer class="mt-6xl">
        <p class="text-center font-body-default text-on-surface-variant">
            Don't have an account?
            <button class="text-primary font-semibold hover:underline underline-offset-4" onclick="toggleFlow('register')" type="button">
                Create Account
            </button>
        </p>
    </footer>

</div>

{{-- ── REGISTRATION VIEW ────────────────────────── --}}
<div class="fade-in-up hidden-flow" id="register-view">

    <header class="mb-5xl">
        <button class="flex items-center gap-xs text-on-surface-variant hover:text-primary mb-xl transition-all"
                onclick="toggleFlow('login')" type="button">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            <span class="font-button text-caption uppercase">Back to Login</span>
        </button>
        <h1 class="font-h3 text-h3 text-on-background mb-xs">Join LUXE</h1>
        <p class="font-body-default text-on-surface-variant">Complete your profile to unlock exclusive benefits.</p>
    </header>

    <form class="space-y-xl" id="register-form" method="POST" action="{{ route('auth.register.post') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
            <x-forms.input id="reg-name"    name="name"   type="text"  label="Full Name"    :required="true" :floatingLabel="true"/>
            <x-forms.input id="reg-mobile"  name="phone"  type="tel"   label="Mobile Number" :required="true" :floatingLabel="true"/>
        </div>
        <x-forms.input id="reg-email" name="email" type="email" label="Email address" :required="true" :floatingLabel="true"/>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
            <x-forms.input id="reg-password" name="password"              type="password" label="Password"         :required="true" :floatingLabel="true"/>
            <x-forms.input id="reg-confirm"  name="password_confirmation"  type="password" label="Confirm Password"  :required="true" :floatingLabel="true"/>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
            <x-forms.input id="reg-location" name="location" type="text" label="Country / City" :required="true" :floatingLabel="true"/>
            <x-forms.input id="reg-referral" name="referral"  type="text" label="Referral Code (Optional)" :floatingLabel="true"/>
        </div>

        <div class="pt-xl">
            <label class="flex items-start gap-sm cursor-pointer group">
                <input class="mt-1 w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 transition-all"
                       name="terms" required type="checkbox"/>
                <span class="font-body-sm text-on-surface-variant group-hover:text-on-surface">
                    I agree to the
                    <a class="text-primary hover:underline" href="#">Terms of Service</a>
                    and
                    <a class="text-primary hover:underline" href="#">Privacy Policy</a>.
                </span>
            </label>
        </div>

        <button class="relative overflow-hidden w-full bg-primary-container hover:bg-primary-container/90 text-on-primary font-button py-md rounded-xl transition-all active:scale-[0.98] shadow-md mt-4xl"
                id="register-btn" type="submit">
            Create Account
        </button>

    </form>

</div>

{{-- Trust Bar --}}
<div class="mt-8xl pt-5xl border-t border-outline-variant/30 flex flex-col gap-4xl">
    <div class="flex items-center gap-sm text-green-700 bg-green-50 px-md py-sm rounded-lg border border-green-100">
        <span class="material-symbols-outlined text-xl fill">verified_user</span>
        <span class="font-caption">AES-256 Bit Secure Encryption. Your data is protected.</span>
    </div>
    <div class="flex justify-between items-center opacity-40 grayscale contrast-125" aria-hidden="true">
        <span class="material-symbols-outlined text-4xl">verified</span>
        <span class="material-symbols-outlined text-4xl">security</span>
        <span class="material-symbols-outlined text-4xl">shield_with_heart</span>
        <span class="material-symbols-outlined text-4xl">award_star</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function toggleFlow(flow) {
        const loginView    = document.getElementById('login-view');
        const registerView = document.getElementById('register-view');
        if (flow === 'register') {
            loginView.classList.add('hidden-flow');
            registerView.classList.remove('hidden-flow');
        } else {
            registerView.classList.add('hidden-flow');
            loginView.classList.remove('hidden-flow');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        if (input.type === 'password') { input.type = 'text'; btn.textContent = 'Hide'; }
        else { input.type = 'password'; btn.textContent = 'Show'; }
    }

    // If registration errors, show registration view
    @if($errors->has('name') || $errors->has('phone') || $errors->has('password_confirmation'))
        document.addEventListener('DOMContentLoaded', () => toggleFlow('register'));
    @endif
</script>
@endpush
