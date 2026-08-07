<!DOCTYPE html>
<html>
<head>

<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
.otp-input {
    width: 3rem;
    height: 3.25rem;
    text-align: center;
    font-size: 1.5rem;
}
.d-none-important {
    display: none !important;
}
</style>

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card shadow">

<div class="card-header">

<h4 id="cardTitle">Create Account</h4>

</div>

<div class="card-body">

<div id="alertBox" class="alert d-none-important" role="alert"></div>

<!-- STEP 1: Registration form -->
<form id="registerForm">

<div class="mb-3">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password</label>

<input
type="password"
name="password"
class="form-control"
minlength="8"
required>

</div>

<div class="mb-3">

<label>Confirm Password</label>

<input
type="password"
name="password_confirmation"
class="form-control"
minlength="8"
required>

</div>

<button type="submit" id="registerBtn" class="btn btn-success w-100">
<span id="registerBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none-important" role="status" aria-hidden="true"></span>
<span id="registerBtnText">Register</span>
</button>

</form>

<!-- STEP 2: OTP verification -->
<form id="otpForm" class="d-none-important">

<p class="mb-3">
Enter the 6-digit code sent to <strong id="otpEmailDisplay"></strong>.
</p>

<div class="d-flex justify-content-between mb-3" id="otpDigits">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="0">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="1">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="2">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="3">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="4">
<input type="text" inputmode="numeric" maxlength="1" class="form-control otp-input" data-index="5">
</div>

<button type="submit" id="verifyBtn" class="btn btn-success w-100 mb-2">
<span id="verifyBtnSpinner" class="spinner-border spinner-border-sm me-2 d-none-important" role="status" aria-hidden="true"></span>
<span id="verifyBtnText">Verify &amp; Continue</span>
</button>

<button type="button" id="resendBtn" class="btn btn-link w-100">
Didn't receive it? Resend OTP
</button>

<button type="button" id="backBtn" class="btn btn-link w-100 text-secondary">
Use a different email
</button>

</form>

<div class="mt-3 text-center" id="loginLink">

<a href="{{ route('login') }}">

Already have account?

</a>

</div>

</div>

</div>

</div>

</div>

</div>

<script>

const registerForm = document.getElementById('registerForm');
const otpForm = document.getElementById('otpForm');
const registerBtn = document.getElementById('registerBtn');
const verifyBtn = document.getElementById('verifyBtn');
const resendBtn = document.getElementById('resendBtn');
const backBtn = document.getElementById('backBtn');
const alertBox = document.getElementById('alertBox');
const cardTitle = document.getElementById('cardTitle');
const loginLink = document.getElementById('loginLink');
const otpDigits = Array.from(document.querySelectorAll('#otpDigits .otp-input'));

let lastPayload = null;
let resendCooldownInterval = null;

function showAlert(type, message) {
    alertBox.className = 'alert alert-' + type;
    alertBox.textContent = message;
    alertBox.classList.remove('d-none-important');
}

function hideAlert() {
    alertBox.classList.add('d-none-important');
}

function setButtonLoading(btn, spinnerEl, textEl, loadingText, isLoading) {
    btn.disabled = isLoading;
    if (isLoading) {
        spinnerEl.classList.remove('d-none-important');
        btn.dataset.originalText = textEl.textContent;
        textEl.textContent = loadingText;
    } else {
        spinnerEl.classList.add('d-none-important');
        textEl.textContent = btn.dataset.originalText || textEl.textContent;
    }
}

function showOtpStep(email) {
    registerForm.classList.add('d-none-important');
    loginLink.classList.add('d-none-important');
    otpForm.classList.remove('d-none-important');
    document.getElementById('otpEmailDisplay').textContent = email;
    cardTitle.textContent = 'Verify Your Email';
    otpDigits.forEach(input => input.value = '');
    otpDigits[0].focus();
    startResendCooldown();
}

function showRegisterStep() {
    otpForm.classList.add('d-none-important');
    registerForm.classList.remove('d-none-important');
    loginLink.classList.remove('d-none-important');
    cardTitle.textContent = 'Create Account';
    hideAlert();
    if (resendCooldownInterval) {
        clearInterval(resendCooldownInterval);
        resendCooldownInterval = null;
    }
}

function startResendCooldown() {
    let seconds = 60;
    resendBtn.disabled = true;
    resendBtn.textContent = 'Resend in ' + seconds + 's';

    if (resendCooldownInterval) clearInterval(resendCooldownInterval);

    resendCooldownInterval = setInterval(() => {
        seconds--;
        if (seconds <= 0) {
            clearInterval(resendCooldownInterval);
            resendCooldownInterval = null;
            resendBtn.disabled = false;
            resendBtn.textContent = "Didn't receive it? Resend OTP";
        } else {
            resendBtn.textContent = 'Resend in ' + seconds + 's';
        }
    }, 1000);
}

async function sendRegistration(payload) {
    const response = await fetch('/api/register', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    });

    return response.json();
}

// ─── STEP 1: Register / send OTP ───────────────────────────────────────────
registerForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (registerBtn.disabled) return;

    hideAlert();
    setButtonLoading(registerBtn, document.getElementById('registerBtnSpinner'), document.getElementById('registerBtnText'), 'Sending OTP...', true);

    const form = new FormData(registerForm);
    const payload = {
        name: form.get('name'),
        email: form.get('email'),
        password: form.get('password'),
        password_confirmation: form.get('password_confirmation'),
    };

    try {
        const data = await sendRegistration(payload);

        if (data.success) {
            lastPayload = payload;
            showOtpStep(payload.email);
        } else {
            showAlert('danger', data.message || 'Registration failed. Please try again.');
        }
    } catch (err) {
        showAlert('danger', 'Something went wrong. Please try again.');
    } finally {
        setButtonLoading(registerBtn, document.getElementById('registerBtnSpinner'), document.getElementById('registerBtnText'), 'Sending OTP...', false);
    }
});

// ─── OTP digit auto-advance ─────────────────────────────────────────────────
otpDigits.forEach((input, i) => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/\D/g, '').slice(0, 1);
        if (input.value && i < otpDigits.length - 1) {
            otpDigits[i + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !input.value && i > 0) {
            otpDigits[i - 1].focus();
        }
    });

    input.addEventListener('paste', (e) => {
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
        if (!pasted) return;
        e.preventDefault();
        otpDigits.forEach((digitInput, idx) => {
            digitInput.value = pasted[idx] || '';
        });
        const nextIndex = Math.min(pasted.length, otpDigits.length - 1);
        otpDigits[nextIndex].focus();
    });
});

// ─── STEP 2: Verify OTP ──────────────────────────────────────────────────────
otpForm.addEventListener('submit', async function (e) {
    e.preventDefault();

    if (verifyBtn.disabled) return;

    const otp = otpDigits.map(input => input.value).join('');

    if (otp.length !== 6) {
        showAlert('danger', 'Please enter the full 6-digit code.');
        return;
    }

    hideAlert();
    setButtonLoading(verifyBtn, document.getElementById('verifyBtnSpinner'), document.getElementById('verifyBtnText'), 'Verifying...', true);

    try {
        const response = await fetch('/api/verify-registration-otp', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: lastPayload ? lastPayload.email : document.getElementById('otpEmailDisplay').textContent,
                otp: otp
            })
        });

        const data = await response.json();

        if (data.success) {
            localStorage.setItem('token', data.token);
            showAlert('success', 'Account verified! Redirecting...');
            setTimeout(() => window.location = '/dashboard', 800);
        } else {
            showAlert('danger', data.message || 'Invalid or expired OTP.');
            otpDigits.forEach(input => input.value = '');
            otpDigits[0].focus();
        }
    } catch (err) {
        showAlert('danger', 'Something went wrong. Please try again.');
    } finally {
        setButtonLoading(verifyBtn, document.getElementById('verifyBtnSpinner'), document.getElementById('verifyBtnText'), 'Verifying...', false);
    }
});

// ─── Resend OTP (re-triggers /api/register, which regenerates the OTP) ─────
resendBtn.addEventListener('click', async function () {
    if (resendBtn.disabled || !lastPayload) return;

    resendBtn.disabled = true;
    const originalText = resendBtn.textContent;
    resendBtn.textContent = 'Resending...';

    try {
        const data = await sendRegistration(lastPayload);

        if (data.success) {
            showAlert('success', 'A new OTP has been sent to your email.');
            startResendCooldown();
        } else {
            showAlert('danger', data.message || 'Could not resend OTP.');
            resendBtn.disabled = false;
            resendBtn.textContent = originalText;
        }
    } catch (err) {
        showAlert('danger', 'Something went wrong. Please try again.');
        resendBtn.disabled = false;
        resendBtn.textContent = originalText;
    }
});

// ─── Back to registration form ──────────────────────────────────────────────
backBtn.addEventListener('click', showRegisterStep);

</script>

</body>

</html>
