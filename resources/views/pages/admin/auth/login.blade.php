<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Afandina') }} | Admin Login</title>

    <link rel="shortcut icon" href="{{ asset('website/assets/img/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('website/assets/css/style.css') }}">

    <style>
        .main-wrapper.login-body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-wrapper.login-body .login-wrapper {
            flex: 1;
        }

        .login-wrapper .loginbox .login-auth {
            width: 100%;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
<div class="main-wrapper login-body">
    <header class="log-header">
        <a href="{{ url('/') }}">
            <img class="img-fluid logo-dark" src="{{ asset('website/assets/img/logo.svg') }}" alt="{{ config('app.name', 'Afandina') }}">
        </a>
    </header>

    <div class="login-wrapper">
        <div class="loginbox">
            <div class="login-auth">
                <div class="login-auth-wrap">
                    <div class="sign-group">
                        <a href="{{ url('/') }}" class="btn sign-up">
                            <span><i class="fe feather-corner-down-left" aria-hidden="true"></i></span>
                            Back To Home
                        </a>
                    </div>

                    <h1>Admin Sign In</h1>
                    <p class="account-subtitle">Sign in to access the admin dashboard.</p>

                    <form action="{{ route('admin.login.post') }}" method="POST">
                        @csrf

                        <div class="input-block">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}"
                                autocomplete="email"
                                required
                                autofocus
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-block">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="pass-group">
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control pass-input @error('password') is-invalid @enderror"
                                    autocomplete="current-password"
                                    required
                                >
                                <span class="fas fa-eye-slash toggle-password"></span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="input-block m-0">
                            <label class="custom_check d-inline-flex"><span>Remember me</span>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                <span class="checkmark"></span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-outline-light w-100 btn-size mt-3">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="log-footer">
        <div class="container-fluid">
            <div class="copyright">
                <div class="copyright-text">
                    <p>&copy; {{ now()->year }} {{ config('app.name', 'Afandina') }}. All Rights Reserved.</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<script src="{{ asset('website/assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('website/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('website/assets/js/script.js') }}"></script>
</body>
</html>
