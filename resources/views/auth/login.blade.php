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

                        <button class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('register') }}">
                            Create Account
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

document.getElementById('loginForm').addEventListener('submit',async function(e){

    e.preventDefault();

    let form=new FormData(this);

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

        window.location="/dashboard";

    }else{

        alert(data.message);

    }

});

</script>

</body>
</html>