{{--
 | Page: auth/admin/login.blade.php
 | Layout: layouts/auth
 | Source: admin_portal_authentication_desktop/code.html
--}}
@extends('layouts.auth')

@php
    $title = 'LUXE Admin | Secure Access';
@endphp

@section('content')

{{-- ── Login Card ───────────────────────────────── --}}
<div class="admin-glass-panel rounded-xl shadow-sm p-4xl transition-all duration-500 ease-in-out" id="auth-card">

    {{-- Login View --}}
    <div id="login-view">
        <header class="mb-3xl">
            <h2 class="font-h5 text-[#111827] mb-xs">Administrator Login</h2>
            <p class="font-body-sm text-on-surface-variant">Enter your credentials to access the console.</p>
        </header>

        <form class="space-y-2xl" id="login-form" method="POST" action="{{ route('admin.auth.login.post') }}">
            @csrf

            <div>
                <label class="block font-body-sm font-medium text-[#111827] mb-sm" for="email">Work Email</label>
                <input class="w-full h-11 px-md bg-surface-container-lowest border border-outline-variant rounded-lg font-body-default input-focus-ring transition-all placeholder:text-on-surface-variant/40"
                       id="email" name="email" type="email" placeholder="name@luxe.com" required
                       value="{{ old('email') }}" autocomplete="email"/>
                @error('email')
                    <p class="mt-xs font-caption text-caption text-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between items-center mb-sm">
                    <label class="block font-body-sm font-medium text-[#111827]" for="password">Security Key</label>
                    <button class="text-caption text-primary hover:underline font-semibold"
                            onclick="toggleView('forgot-view')" type="button">Forgot Key?</button>
                </div>
                <input class="w-full h-11 px-md bg-surface-container-lowest border border-outline-variant rounded-lg font-body-default input-focus-ring transition-all placeholder:text-on-surface-variant/40"
                       id="password" name="password" type="password" placeholder="••••••••" required
                       autocomplete="current-password"/>
                @error('password')
                    <p class="mt-xs font-caption text-caption text-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-sm">
                <div class="flex items-start gap-md p-md bg-surface-container-low rounded-lg border border-outline-variant/30 mb-2xl">
                    <span class="material-symbols-outlined text-primary text-[20px]">verified_user</span>
                    <div>
                        <p class="text-caption text-[#111827] leading-tight mb-1">Multi-Factor Authentication</p>
                        <p class="text-[11px] text-on-surface-variant">2FA verification will be required after this step.</p>
                    </div>
                </div>
            </div>

            <button class="w-full h-11 bg-[#F97316] hover:bg-[#EA580C] text-white font-button rounded-lg transition-all active:scale-[0.98] flex items-center justify-center gap-sm shadow-md shadow-primary/10"
                    id="signin-btn" type="submit">
                <span>Sign in to Console</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </button>
        </form>
    </div>

    {{-- Forgot Password View --}}
    <div class="hidden" id="forgot-view">
        <header class="mb-3xl">
            <button class="flex items-center gap-xs text-on-surface-variant hover:text-primary transition-colors mb-md group"
                    onclick="toggleView('login-view')" type="button">
                <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                <span class="text-caption font-semibold">Back to Login</span>
            </button>
            <h2 class="font-h5 text-[#111827] mb-xs">Reset Access</h2>
            <p class="font-body-sm text-on-surface-variant">Provide your administrator email to receive a recovery link.</p>
        </header>

        <form class="space-y-2xl" id="forgot-form" method="POST" action="{{ route('admin.auth.password.email') }}">
            @csrf
            <div>
                <label class="block font-body-sm font-medium text-[#111827] mb-sm" for="recovery-email">Email Address</label>
                <input class="w-full h-11 px-md bg-surface-container-lowest border border-outline-variant rounded-lg font-body-default input-focus-ring transition-all"
                       id="recovery-email" name="email" type="email" placeholder="admin@luxe.com" required/>
            </div>
            <button class="w-full h-11 bg-[#F97316] hover:bg-[#EA580C] text-white font-button rounded-lg transition-all active:scale-[0.98]" type="submit">
                Request Recovery Link
            </button>
        </form>
    </div>

    {{-- Success State --}}
    <div class="hidden text-center py-2xl" id="success-view">
        <div class="w-16 h-16 bg-secondary-container/20 rounded-full flex items-center justify-center mx-auto mb-3xl">
            <span class="material-symbols-outlined text-secondary text-[32px]">check_circle</span>
        </div>
        <h2 class="font-h5 text-[#111827] mb-sm">Link Dispatched</h2>
        <p class="font-body-sm text-on-surface-variant mb-4xl">
            If that account exists in our system, you'll receive a secure recovery token within the next 2 minutes.
        </p>
        <button class="w-full h-11 border border-outline-variant text-[#111827] hover:bg-surface-container-low font-button rounded-lg transition-all"
                onclick="toggleView('login-view')" type="button">
            Return to Portal
        </button>
    </div>

</div>

{{-- 2FA OTP View --}}
<div class="hidden admin-glass-panel rounded-xl shadow-lg p-4xl border border-primary/20" id="otp-view">
    <header class="text-center mb-3xl">
        <div class="inline-flex p-md bg-primary-container/10 rounded-full mb-md">
            <span class="material-symbols-outlined text-primary text-[28px]">shield_person</span>
        </div>
        <h2 class="font-h5 text-[#111827] mb-xs">Verification Required</h2>
        <p class="font-body-sm text-on-surface-variant">Enter the 6-digit code from your authenticator app.</p>
    </header>

    <form method="POST" action="{{ route('admin.auth.2fa.verify') }}">
        @csrf
        <div class="flex justify-center gap-sm mb-4xl" id="otp-inputs">
            @for($i = 0; $i < 6; $i++)
                <input class="w-11 h-14 bg-surface-container-lowest border border-outline-variant rounded-lg text-center font-h4 input-focus-ring"
                       maxlength="1" type="text" inputmode="numeric" name="otp[]"
                       aria-label="OTP digit {{ $i+1 }}"/>
            @endfor
        </div>
        <button class="w-full h-11 bg-[#111827] hover:bg-black text-white font-button rounded-lg transition-all" type="submit">
            Verify Identity
        </button>
    </form>

    <p class="text-center mt-3xl text-caption text-on-surface-variant">
        Lost your device? <a class="text-primary font-semibold hover:underline" href="#">Use backup codes</a>
    </p>
</div>

@endsection

@push('scripts')
<script>
    function toggleView(viewId) {
        ['login-view','forgot-view','success-view','otp-view'].forEach(v => {
            const el = document.getElementById(v);
            if (el) el.classList.add('hidden');
        });
        const target = document.getElementById(viewId);
        if (target) target.classList.remove('hidden');
        const card = document.getElementById('auth-card');
        if (card) card.classList.toggle('hidden', viewId === 'otp-view');
    }

    // OTP auto-advance
    document.querySelectorAll('#otp-inputs input').forEach((input, index, inputs) => {
        input.addEventListener('input', e => {
            if (e.target.value.length === 1 && index < inputs.length - 1) inputs[index + 1].focus();
        });
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && e.target.value.length === 0 && index > 0) inputs[index - 1].focus();
        });
    });
</script>
@endpush
