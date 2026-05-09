<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed | TechnoDom</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
</head>
<body>

<div class="page-container">

    <header class="site-header">
        <div class="header-inner-elements">

            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="TechnoDom logo">
            </a>

            <form class="search-form" action="{{ route('search') }}" method="GET">
                <input type="text" class="search-input" placeholder="Search products" name="q">
            </form>

            @auth
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="staff-dashboard-link">Back to Admin Dashboard</a>
                @endif
            @endauth

            <div class="header-actions">
                <div class="account-block">
                    <span class="account-name">
                        @auth
                            {{ auth()->user()->name }}
                        @else
                            My account
                        @endauth
                    </span>
                    <a href="{{ auth()->check() ? route('account') : route('login') }}" class="header-icon">
                        <img src="{{ asset('images/user.png') }}" alt="User profile icon">
                    </a>
                </div>

                <div class="shop-icons">
                    <a href="{{ route('cart.show') }}" class="header-icon">
                        <img src="{{ asset('images/cart.png') }}" alt="Shopping cart icon">
                    </a>
                </div>
            </div>

        </div>
    </header>

    <main class="site-main">
        <section class="cart-page">
            <h1 class="cart-title">Order Confirmed</h1>

            @if (session('success'))
                <div class="cart-message cart-message-success">{{ session('success') }}</div>
            @endif

            <div class="order-confirmation-layout">
                <div class="checkout-block order-confirmation-message">
                    <h2>Thank you for your purchase!</h2>
                    <p>Your order has been successfully placed.</p>
                    <div class="order-number">
                        Order number: <strong>#{{ $orderNumber }}</strong>
                    </div>
                </div>

                <div class="checkout-summary">
                    <h2>Order Summary</h2>

                    @foreach ($cartItems as $item)
                        <div class="summary-item">
                            <div class="summary-product">
                                <img src="{{ asset($item->product->image_path ?? 'images/default_laptop.png') }}" alt="{{ $item->product_name }}">
                                <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                            </div>
                            <span>{{ number_format((float) ($item->product_price * $item->quantity), 2, '.', ' ') }} EUR</span>
                        </div>
                    @endforeach

                    <div class="summary-item">
                        <span>Delivery</span>
                        <span>
                            {{ $deliveryMethodLabel }}
                            @if ($deliveryPrice == 0)
                                (Free)
                            @else
                                ({{ number_format((float) $deliveryPrice, 2, '.', ' ') }} EUR)
                            @endif
                        </span>
                    </div>

                    <div class="summary-item">
                        <span>Payment</span>
                        <span>{{ $paymentMethodLabel }}</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span class="cart-total-price">{{ number_format($total, 2, '.', ' ') }} EUR</span>
                    </div>
                </div>
            </div>

            <div class="cart-actions">
                <a href="{{ route('home') }}" class="cart-next-button">Back to homepage</a>
            </div>
        </section>
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
