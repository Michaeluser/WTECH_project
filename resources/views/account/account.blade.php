<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account | TechnoDom</title>
    <link rel="stylesheet" href="{{ asset('css/account.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
                        <span class="account-name">{{ auth()->user()->name }}</span>
                        <a href="{{ route('account') }}" class="header-icon">
                            <img src="{{ asset('images/user.png') }}" alt="User profile icon">
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <div class="site-main">
                <div class="account-state">
                    <div class="account-part">
                        <img src="{{ asset('images/user.png') }}" alt="User account image">
                        <h2>{{ auth()->user()->name }}</h2>
                    </div>

                    <div class="membership-part">
                        <h4 class="membership-label">Account Status</h4>
                        <div class="status-container">
                            <img src="{{ asset('images/black-check.png') }}" alt="icon">
                            <h4 class="membership-status">Authorized</h4>
                        </div>
                    </div>
                </div>

                <div class="location-contact-info">
                    <h3 class="mail-label">Mail:</h3>
                    <h3 class="mail-data">{{ auth()->user()->email }}</h3>

                    <h3 class="number-label">User ID:</h3>
                    <h3 class="number-data">{{ auth()->user()->id }}</h3>

                    <h3 class="street-label">Created:</h3>
                    <h3 class="street-data">{{ auth()->user()->created_at?->format('d.m.Y H:i') }}</h3>

                    <h3 class="country-label">Role:</h3>
                    <h3 class="country-data">Customer</h3>

                    <h3 class="city-label">Email Verification:</h3>
                    <h3 class="city-data">{{ auth()->user()->email_verified_at ? 'Verified' : 'Not verified' }}</h3>
                </div>

                <div class="payment-info">
                    <h3 class="iban-label">Next Step:</h3>
                    <h3 class="iban-data">Extend the `users` table with address, phone, and profile fields when you are ready.</h3>

                    <h3 class="bic-label">Current Auth:</h3>
                    <h3 class="bic-data">Laravel session-based authentication is active.</h3>
                </div>

                <div class="link-layout">
                    <h4>Useful links</h4>
                    <ul class="link-menu">
                        <li class="menu-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="menu-item"><a href="{{ route('account') }}">Account</a></li>
                        <li class="menu-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" style="background: none; border: 0; padding: 0; color: inherit; font: inherit; cursor: pointer; text-decoration: underline;">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
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
