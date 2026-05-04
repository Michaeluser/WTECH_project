<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping and Payment | TechnoDom</title>
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

    <nav class="main-nav">
        <div class="nav-top">
            <span class="nav-mobile-title">Categories</span>
            <button class="nav-toggle" type="button" aria-label="Open categories" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <ul class="nav-list">
            @foreach ($categories as $category)
                <li class="nav-item">
                    <a href="{{ $category->navUrl() }}">{{ $category->name }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <main class="site-main">
        <section class="cart-page">
            <h1 class="cart-title">Shipping and Payment</h1>

            <div class="checkout-steps">
                <ul class="checkout-steps-list">
                    <li>Shopping cart</li>
                    <li class="active">Shipping and Payment</li>
                    <li>Location Details</li>
                </ul>
            </div>

            <div class="checkout-step2-layout">
                <div class="shipping-payment-content">
                    <div class="checkout-block">
                        <h2>Delivery Method</h2>

                        <label class="checkout-option">
                            <input type="radio" name="delivery" checked>
                            <span>Courier Delivery</span>
                        </label>

                        <label class="checkout-option">
                            <input type="radio" name="delivery">
                            <span>Express Delivery</span>
                        </label>
                    </div>

                    <div class="checkout-block">
                        <h2>Payment Method</h2>

                        <label class="checkout-option">
                            <input type="radio" name="payment" checked>
                            <span>Credit Card</span>
                        </label>

                        <label class="checkout-option">
                            <input type="radio" name="payment">
                            <span>Bank Transfer</span>
                        </label>
                    </div>
                </div>

                <div class="checkout-summary">
                    <h2>Order Summary</h2>

                    @foreach ($cartItems as $item)
                        <div class="summary-item">
                            <div class="summary-product">
                                <img src="{{ asset($item->product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $item->product->name }}">
                                <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                            </div>

                            <span>{{ number_format((float) ($item->product->price * $item->quantity), 2, '.', ' ') }} EUR</span>
                        </div>
                    @endforeach

                    <div class="summary-item">
                        <span>Delivery</span>
                        <span>Free</span>
                    </div>

                    <div class="summary-total">
                        <span>Total</span>
                        <span class="cart-total-price">{{ number_format($total, 2, '.', ' ') }} EUR</span>
                    </div>
                </div>
            </div>

            <div class="cart-actions cart-actions-between">
                <a href="{{ route('cart.show') }}" class="cart-back-button">Back</a>
                <a href="#" class="cart-next-button">Next</a>
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

<script src="{{ asset('js/hamurger-menu.js') }}"></script>
</body>
</html>
