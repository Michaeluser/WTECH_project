<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location Details | TechnoDom</title>

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
                @if (auth()->user()->is_staff)
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
            <h1 class="cart-title">Location Details</h1>

            @if (session('success'))
                <div class="cart-message cart-message-success">{{ session('success') }}</div>
            @endif

            <div class="checkout-steps">
                <ul class="checkout-steps-list">
                    <li>Shopping cart</li>
                    <li>Shipping and Payment</li>
                    <li class="active">Location Details</li>
                </ul>
            </div>

            <div class="checkout-step3-layout">

                <div class="checkout-block">
                    <h2>Delivery Information</h2>

                    <form class="details-form" action="{{ route('checkout.confirmation') }}" method="GET">
                        <input type="hidden" name="delivery_method" value="{{ $deliveryMethod }}">
                        <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">

                        <div class="details-form-grid">
                            <div class="form-field">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name" type="text" placeholder="Enter your first name">
                            </div>

                            <div class="form-field">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name" type="text" placeholder="Enter your last name">
                            </div>

                            <div class="form-field">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" placeholder="Enter your email address">
                            </div>

                            <div class="form-field">
                                <label for="phone_number">Phone Number</label>
                                <input id="phone_number" name="phone_number" type="text" placeholder="Enter your phone number">
                            </div>

                            <div class="form-field">
                                <label for="city">City</label>
                                <input id="city" name="city" type="text" placeholder="Enter your city">
                            </div>

                            <div class="form-field">
                                <label for="postal_code">Postal Code</label>
                                <input id="postal_code" name="postal_code" type="text" placeholder="Enter your postal code">
                            </div>

                            <div class="form-field form-field-full">
                                <label for="street_address">Street Address</label>
                                <input id="street_address" name="street_address" type="text" placeholder="Enter your street address">
                            </div>

                            <div class="form-field form-field-full">
                                <label for="notes">Order Notes</label>
                                <textarea id="notes" name="notes" rows="4" placeholder="Add delivery instructions or extra information here"></textarea>
                            </div>
                        </div>

                        <div class="cart-actions cart-actions-between checkout-details-actions">
                            <a href="{{ route('checkout.show') }}" class="cart-back-button">Back</a>
                            <button type="submit" class="cart-next-button">Place Order</button>
                        </div>
                    </form>
                </div>

                <aside class="checkout-summary">
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
                </aside>

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
