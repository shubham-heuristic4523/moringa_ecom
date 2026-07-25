/**
 * LUXE — Auth Module
 * Handles: Register → OTP → Complete | Login | Logout | Forgot Password
 * Calls existing APIs: POST /api/register, /api/verify-registration-otp, /api/login
 */

import { api, setToken, clearToken, showToast, displayFieldErrors, clearFieldErrors, setButtonLoading, resetButton } from '../api/client.js';

document.addEventListener('DOMContentLoaded', () => {

    // ─── REGISTER FORM ──────────────────────────────────────────────────────
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFieldErrors();

            const btn = document.getElementById('register-btn');
            setButtonLoading(btn, 'Creating Account...');

            const payload = {
                name:                  registerForm.querySelector('[name="name"]')?.value,
                email:                 registerForm.querySelector('[name="email"]')?.value,
                password:              registerForm.querySelector('[name="password"]')?.value,
                password_confirmation: registerForm.querySelector('[name="password_confirmation"]')?.value,
                phone:                 registerForm.querySelector('[name="phone"]')?.value ?? undefined,
            };

            const res = await api.post('/register', payload);
            resetButton(btn);

            if (res.status === 422) {
                displayFieldErrors(res.errors ?? {});
                return;
            }

            if (res.success) {
                // Show OTP verification step
                showOtpStep(payload.email);
            } else {
                showToast('error', res.message ?? 'Registration failed. Please try again.');
            }
        });
    }

    // ─── OTP VERIFICATION ───────────────────────────────────────────────────
    const otpForm = document.getElementById('otp-verify-form');
    if (otpForm) {
        otpForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFieldErrors();

            const btn = otpForm.querySelector('button[type="submit"]');
            setButtonLoading(btn, 'Verifying...');

            const otpInputs = otpForm.querySelectorAll('input[name="otp_digit"]');
            const otp = Array.from(otpInputs).map(i => i.value).join('');

            const res = await api.post('/verify-registration-otp', {
                email: otpForm.querySelector('[name="email"]')?.value,
                otp,
            });

            resetButton(btn);

            if (res.success) {
                setToken(res.token);
                showToast('success', 'Account verified! Welcome to LUXE.');
                // Redirect after brief delay so toast is visible
                setTimeout(() => window.location.href = '/', 1000);
            } else {
                showToast('error', res.message ?? 'Invalid or expired OTP.');
            }
        });

        // OTP resend
        const resendBtn = document.getElementById('resend-otp-btn');
        if (resendBtn) {
            resendBtn.addEventListener('click', async () => {
                const email = otpForm.querySelector('[name="email"]')?.value;
                const res = await api.post('/auth/resend-otp', { email, purpose: 'register' });
                if (res.success) {
                    showToast('success', 'New OTP sent to your email.');
                    startResendCooldown(resendBtn);
                } else {
                    showToast('error', res.message ?? 'Could not resend OTP.');
                }
            });
        }
    }

    // ─── LOGIN FORM ─────────────────────────────────────────────────────────
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            clearFieldErrors();

            const btn = document.getElementById('login-btn');
            setButtonLoading(btn, 'Signing In...');

            const res = await api.post('/login', {
                email:    loginForm.querySelector('[name="email"]')?.value,
                password: loginForm.querySelector('[name="password"]')?.value,
            });

            resetButton(btn);

            if (res.status === 422) {
                displayFieldErrors(res.errors ?? {});
                return;
            }

            if (res.status === 401) {
                showToast('error', 'Invalid email or password.');
                return;
            }

            if (res.status === 403) {
                showToast('error', 'Your account has been deactivated. Please contact support.');
                return;
            }

            if (res.success && res.token) {
                setToken(res.token);

                // Redirect based on role
                const role = res.user?.role;
                if (role === 'admin' || role === 'super_admin') {
                    window.location.href = '/admin';
                } else {
                    window.location.href = document.getElementById('redirect-after-login')?.value ?? '/';
                }
            } else {
                showToast('error', res.message ?? 'Login failed. Please try again.');
            }
        });
    }

    // ─── ADMIN LOGIN ─────────────────────────────────────────────────────────
    const adminLoginForm = document.getElementById('login-form');
    if (adminLoginForm && document.body.dataset.page === 'admin-login') {
        adminLoginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = document.getElementById('signin-btn');
            const original = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = `<div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div><span class="opacity-70">Authenticating...</span>`;

            const res = await api.post('/login', {
                email:    adminLoginForm.querySelector('[name="email"]')?.value,
                password: adminLoginForm.querySelector('[name="password"]')?.value,
            });

            btn.disabled = false;
            btn.innerHTML = original;

            if (res.success && (res.user?.role === 'admin' || res.user?.role === 'super_admin')) {
                setToken(res.token);
                // Show 2FA step
                toggleView('otp-view');
            } else if (res.success) {
                showToast('error', 'Access denied. Admin credentials required.');
            } else {
                showToast('error', res.message ?? 'Authentication failed.');
            }
        });
    }

    // ─── FORGOT PASSWORD FORM ────────────────────────────────────────────────
    const forgotForm = document.getElementById('forgot-form');
    if (forgotForm) {
        forgotForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = forgotForm.querySelector('button[type="submit"]');
            setButtonLoading(btn, 'Sending...');

            const res = await api.post('/forgot-password', {
                email: forgotForm.querySelector('[name="email"]')?.value,
            });

            resetButton(btn);

            if (res.success) {
                // Admin auth page uses toggleView()
                if (typeof toggleView === 'function') {
                    toggleView('success-view');
                } else {
                    showToast('success', 'Password reset link sent to your email.');
                }
            } else {
                showToast('error', res.message ?? 'Could not send reset link.');
            }
        });
    }

    // ─── OTP DISPLAY STEP (Customer Registration) ────────────────────────────
    function showOtpStep(email) {
        const regView = document.getElementById('register-view');
        let otpView = document.getElementById('otp-verify-step');

        if (!otpView) {
            // Inject OTP step dynamically
            otpView = document.createElement('div');
            otpView.id = 'otp-verify-step';
            otpView.className = 'fade-in-up';
            otpView.innerHTML = `
                <header class="mb-5xl">
                    <h2 class="font-h3 text-h3 text-on-background mb-xs">Verify Your Email</h2>
                    <p class="font-body-default text-on-surface-variant">We sent a 6-digit code to <strong>${email}</strong></p>
                </header>
                <form id="otp-verify-form" class="space-y-4xl">
                    <input type="hidden" name="email" value="${email}"/>
                    <div class="flex justify-center gap-sm">
                        ${[1,2,3,4,5,6].map(i => `
                            <input name="otp_digit"
                                   type="text" maxlength="1" inputmode="numeric"
                                   class="w-11 h-14 bg-surface border border-outline-variant rounded-lg text-center font-h4 focus:border-primary focus:ring-2 focus:ring-primary/10 transition-shadow"
                                   aria-label="OTP digit ${i}"/>`).join('')}
                    </div>
                    <button type="submit"
                            class="w-full bg-primary-container text-on-primary font-button py-md rounded-xl transition-all active:scale-[0.98] shadow-md flex items-center justify-center gap-xs">
                        Verify &amp; Continue
                    </button>
                </form>
                <div class="text-center mt-xl">
                    <button id="resend-otp-btn" class="font-body-sm text-primary hover:underline">
                        Didn't receive it? Resend OTP
                    </button>
                </div>`;
            regView.parentNode.insertBefore(otpView, regView);
        }

        if (regView) regView.classList.add('hidden-flow');
        otpView.classList.remove('hidden-flow');

        // Rebind submit and resend on newly injected form
        const newOtpForm = document.getElementById('otp-verify-form');
        if (newOtpForm) {
            newOtpForm.addEventListener('submit', otpForm?.onsubmit ?? (() => {}));
            setupOtpAutoAdvance(newOtpForm);
        }

        const resendBtn = document.getElementById('resend-otp-btn');
        if (resendBtn) {
            resendBtn.addEventListener('click', async () => {
                const res = await api.post('/auth/resend-otp', { email, purpose: 'register' });
                showToast(res.success ? 'success' : 'error', res.success ? 'New OTP sent!' : res.message);
                if (res.success) startResendCooldown(resendBtn);
            });
        }
    }

    // ─── OTP Auto-advance ────────────────────────────────────────────────────
    function setupOtpAutoAdvance(form) {
        const inputs = form.querySelectorAll('input[name="otp_digit"]');
        inputs.forEach((input, i) => {
            input.addEventListener('input', e => {
                if (e.target.value.length === 1 && i < inputs.length - 1) inputs[i+1].focus();
            });
            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && e.target.value === '' && i > 0) inputs[i-1].focus();
            });
            input.addEventListener('paste', e => {
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
                inputs.forEach((inp, idx) => { inp.value = pasted[idx] ?? ''; });
                inputs[Math.min(pasted.length, inputs.length - 1)].focus();
                e.preventDefault();
            });
        });
    }

    // ─── Resend cooldown (60s) ───────────────────────────────────────────────
    function startResendCooldown(btn) {
        let seconds = 60;
        btn.disabled = true;
        const interval = setInterval(() => {
            btn.textContent = `Resend in ${seconds--}s`;
            if (seconds < 0) {
                clearInterval(interval);
                btn.disabled = false;
                btn.textContent = "Didn't receive it? Resend OTP";
            }
        }, 1000);
    }
});
