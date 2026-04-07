<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | TechnoDom</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="page-container">
        <header class="site-header"></header>

        <div class="login-page-wrapper">
            <div class="login-logo-header">
                <div class="logo-box">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="Website logo">
                    </a>
                </div>
            </div>

            <div class="login-container">
                <h2>Logging In</h2>

                @if ($errors->any())
                    <div style="margin-bottom: 16px; color: #b42318;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="input-group">
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required>
                        <div style="margin-top: 8px; display: flex; justify-content: space-between; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                                <input type="checkbox" name="remember" value="1">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login">Log In</button>

                    <div class="login-footer-links">
                        <a href="{{ route('register') }}" class="form-link-small">Register Instead</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
