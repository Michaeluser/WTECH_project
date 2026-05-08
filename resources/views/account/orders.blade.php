<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | TechnoDom</title>
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
            <div class="site-main orders-page-main">
                <div class="account-state">
                    <div class="account-part">
                        <img src="{{ asset('images/user.png') }}" alt="User account image">
                        <h2>{{ auth()->user()->name }}</h2>
                    </div>

                    <div class="membership-part">
                        <h4 class="membership-label">Orders Overview</h4>
                        <div class="status-container">
                            <img src="{{ asset('images/black-check.png') }}" alt="icon">
                            <h4 class="membership-status">{{ $orders->count() }} orders</h4>
                        </div>
                    </div>
                </div>

                <div class="orders-info">
                    <h3 class="orders-title">My Orders</h3>

                    @if ($orders->isEmpty())
                        <p class="orders-empty">You do not have any orders yet.</p>
                    @else
                        <div class="orders-list">
                            @foreach ($orders as $order)
                                <article class="order-card">
                                    <div class="order-card-header">
                                        <h4>{{ $order->order_number }}</h4>
                                    </div>

                                    <p class="order-meta">
                                        {{ $order->created_at?->format('d.m.Y H:i') }} |
                                        {{ $order->delivery_method }} |
                                        {{ $order->payment_method }}
                                    </p>

                                    <ul class="order-items-list">
                                        @foreach ($order->items as $item)
                                            <li>{{ $item->product_name }} x{{ $item->quantity }}</li>
                                        @endforeach
                                    </ul>

                                    <p class="order-total">Total: {{ number_format((float) $order->total, 2, '.', ' ') }} EUR</p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="link-layout">
                    <h4>Useful links</h4>
                    <ul class="link-menu">
                        <li class="menu-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="menu-item"><a href="{{ route('account') }}">Account</a></li>
                        <li class="menu-item"><a href="{{ route('account.orders') }}">My Orders</a></li>
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
