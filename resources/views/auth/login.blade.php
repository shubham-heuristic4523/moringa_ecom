<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow">

                <div class="card-header">
                    <h4>Login</h4>
                </div>

                <div class="card-body">

                    <form id="loginForm">

                        <button type="button" class="btn btn-outline-secondary w-100 mb-2" disabled title="Coming soon">
                            Log in with Google
                            <span class="badge bg-secondary ms-1">Coming soon</span>
                        </button>

                        <div class="text-center text-muted mb-3" style="font-size:0.85rem;">— or log in with email —</div>

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
                                required>

                        </div>

                        <button type="submit" id="loginBtn" class="btn btn-primary w-100">
                            <span id="loginBtnText">Login</span>
                        </button>

                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('register') }}" id="createAccountLink">
                            Create Account
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

// Carry the redirect (and, if it points back at a specific store, that
// store's slug) through to "Create Account" too.
(function () {
    const redirect = new URLSearchParams(window.location.search).get('redirect');
    if (!redirect) return;

    const link = document.getElementById('createAccountLink');
    const params = new URLSearchParams({ redirect });

    const storeMatch = redirect.match(/^\/store\/([^/?]+)/);
    if (storeMatch) params.set('store', storeMatch[1]);

    link.href = '{{ route('register') }}?' + params.toString();
})();

document.getElementById('loginForm').addEventListener('submit',async function(e){

    e.preventDefault();

    const btn = document.getElementById('loginBtn');
    if (btn.disabled) return;
    btn.disabled = true;
    document.getElementById('loginBtnText').textContent = 'Logging in…';

    let form=new FormData(this);

    try {

        let response=await fetch('/api/login',{

            method:'POST',

            headers:{
                'Accept':'application/json'
            },

            body:form

        });

        let data=await response.json();

        if(data.success){

            localStorage.setItem('token',data.token);

            if (data.user?.role === 'admin' || data.user?.role === 'super_admin') {
                window.location = '/dashboard';
                return;
            }

            const redirect = new URLSearchParams(window.location.search).get('redirect');
            window.location = redirect || '/';

        }else{

            alert(data.message);

        }

    } finally {

        btn.disabled = false;
        document.getElementById('loginBtnText').textContent = 'Login';

    }

});

</script>

</body>
</html>