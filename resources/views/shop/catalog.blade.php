<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} | TechnoDom</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog_laptops.css') }}">
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
                    <a href="{{ route('favourites') }}" class="header-icon">
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
        <div class="catalog-header">
            <h1 class="catalog-title">{{ $category->name }}</h1>
        </div>

        <section class="banner-slider">
            <button class="slider-btn slider-btn--prev" type="button">&#8249;</button>
            <div class="slider-image">
                @foreach ($slides as $index => $slide)
                    <a href="{{ $slide->targetCategory ? route('categories.show', $slide->targetCategory) : route('categories.show', $category) }}" class="slide" data-index="{{ $index }}">
                        <img src="{{ asset($slide->image_path) }}" alt="{{ $slide->alt_text }}">
                    </a>
                @endforeach

                <p class="slider-caption">{{ $category->catalog_slider_caption }}</p>
            </div>
            <button class="slider-btn slider-btn--next" type="button">&#8250;</button>
        </section>

        <section class="categories-grid">
            @foreach ($cards as $card)
                <a href="{{ $card->targetCategory ? route('categories.show', $card->targetCategory) : route('categories.show', $category) }}" class="category-card">
                    <img src="{{ asset($card->image_path) }}" alt="{{ $card->alt_text }}">
                    <span class="category-card__name">{{ $card->title }}</span>
                </a>
            @endforeach
        </section>

        <section class="advice-block">
            <h3 class="advice-block__title">{{ $category->catalog_advice_title }}</h3>
            <h4 class="advice-block__subtitle">{{ $category->catalog_advice_subtitle }}</h4>
            <p class="advice-block__text">{{ $category->catalog_advice_text }}</p>
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

<script src="{{ asset('js/catalog.js') }}"></script>
<script src="{{ asset('js/hamurger-menu.js') }}"></script>
</body>
</html>
