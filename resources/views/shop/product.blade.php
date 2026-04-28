<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} | TechnoDom</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/products.css') }}">
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
                    <a href="#" class="header-icon">
                        <img src="{{ asset('images/heart.png') }}" alt="Wishlist icon">
                    </a>

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
            @foreach ($categories as $navCategory)
                <li class="nav-item">
                    <a href="{{ $navCategory->navUrl() }}">{{ $navCategory->name }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    <main class="site-main">
        <div class="product-detail-page">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>/</span>
                <a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
                <span>/</span>
                <span>{{ $product->name }}</span>
            </div>

            @if (session('success'))
                <div class="detail-message detail-message-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="product-detail-card">
                <div class="product-detail-image">
                    <img src="{{ asset($product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $product->name }}">
                </div>

                <div class="product-detail-info">
                    <p class="product-detail-category">{{ $product->category->name }}</p>
                    <h1>{{ $product->name }}</h1>
                    <p class="product-detail-brand">Brand: {{ $product->brand->name }}</p>
                    <p class="product-detail-price">{{ number_format((float) $product->price, 2, '.', ' ') }} EUR</p>

                    <div class="product-detail-specs">
                        <h2>Product details</h2>
                        <p><strong>Color:</strong> {{ $product->color ?: 'Not specified' }}</p>
                        <p><strong>RAM:</strong> {{ $product->ram_gb ? $product->ram_gb . ' GB' : 'Not specified' }}</p>
                        <p><strong>Stock:</strong> {{ $product->amount }}</p>
                    </div>

                    <div class="product-detail-description">
                        <h2>Description</h2>
                        <p>{{ $product->description ?: 'Description will be added later.' }}</p>
                    </div>

                    @if ($product->amount > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="product-detail-form">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            <label for="quantity">Quantity</label>
                            <input
                                id="quantity"
                                type="number"
                                name="quantity"
                                min="1"
                                max="{{ $product->stock }}"
                                value="{{ old('quantity', 1) }}"
                            >

                            @error('quantity')
                                <p class="detail-message detail-message-error">{{ $message }}</p>
                            @enderror

                            <button type="submit" class="product-button">Add to cart</button>
                        </form>
                    @else
                        <p class="detail-message detail-message-error">This product is currently out of stock.</p>
                    @endif

                    <a href="{{ route('categories.show', $product->category) }}" class="product-link">
                        Back to {{ $product->category->name }}
                    </a>
                </div>
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

<script src="{{ asset('js/hamurger-menu.js') }}"></script>
</body>
</html>
