<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | TechnoDom</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/registration.css') }}">
</head>
<body>
    <div class="page-container">
        <header class="site-header">
            <div class="header-inner-elements">
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="TechnoDom logo">
                </a>

                <div class="header-actions">
                    <div class="account-block">
                        <span class="account-name">My account</span>
                        <a href="{{ route('login') }}" class="header-icon">
                            <img src="{{ asset('images/user.png') }}" alt="User profile icon">
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="site-main">
            <div class="registration-container">
                <h1 class="page-title">Registration</h1>

                @if ($errors->any())
                    <div style="margin-bottom: 16px; color: #b42318;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="registration-form" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="registration-grid">
                        <div class="info-column">
                            <h3>Account Information</h3>

                            <div class="input-group">
                                <label for="name">Full Name</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                            </div>

                            <div class="input-group">
                                <label for="email">Sign-In Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="input-group">
                                <label for="password">Password</label>
                                <input id="password" name="password" type="password" required>
                            </div>

                            <div class="input-group">
                                <label for="password_confirmation">Password Confirmation</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required>
                            </div>
                        </div>

                        <div class="info-column">
                            <h3>What happens next?</h3>
                            <p>After registration we will sign you in automatically and redirect you to your account page.</p>
                            <p>You can extend this form later with delivery address, phone number, and profile details once those database fields exist.</p>
                        </div>

                        <div class="info-column">
                            <h3>Already registered?</h3>
                            <p>Use your existing credentials to sign in instead of creating a second account.</p>
                            <div class="form-actions" style="justify-content: flex-start; padding-top: 8px;">
                                <a href="{{ route('login') }}" class="btn-signup" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Go to Login</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-signup">Sign Up</button>
                    </div>
                </form>
            </div>
        </main>

        <footer class="site-footer">
            <ul class="footer-list">
                <li class="footer-item"><a href="#">About us</a></li>
                <li class="footer-item"><a href="#">Contacts</a></li>
                <li class="footer-item"><a href="#">Terms &amp; Conditions</a></li>
                <li class="footer-item"><a href="#">FAQ</a></li>
                <li class="footer-item"><a href="#">Support</a></li>
            </ul>
        </footer>
    </div>
</body>
</html>
