<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechnoDom</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
        @foreach ($categories as $category)
          <li class="nav-item">
            <a href="{{ $category->navUrl() }}">{{ $category->name }}</a>
          </li>
        @endforeach
      </ul>
    </nav>

    <main class="site-main">
      <section class="hero-section">

        <div class="hero-left">
          <article class="main-banner">
            <img src="{{ asset('images/banner-main.jpg') }}" alt="Main homepage banner">
            <div class="main-banner-content">
              <h2>Check out our new VR headset!</h2>
              <p>Discover the latest technology at a great price.</p>
              <a href="{{ route('categories.show', $categories->firstWhere('slug', 'gaming')) }}" class="main-banner-button">Shop now</a>
            </div>
          </article>
        </div>

        <div class="hero-right">
          <article class="promo-card">
            <img src="{{ asset('images/promo-monitor.jpg') }}" alt="Monitor promo">
            <div class="promo-text">
              <h3>Monitors up to -60%</h3>
              <p>Mega offer</p>
              <a href="{{ route('categories.show', $categories->firstWhere('slug', 'monitors')) }}" class="promo-button">Shop now</a>
            </div>
          </article>

          <article class="promo-card">
            <img src="{{ asset('images/promo-laptop.jpg') }}" alt="Laptop promo">
            <div class="promo-text">
              <h3>Gaming laptops up to -20%</h3>
              <p>Limited offer</p>
              <a href="{{ route('categories.show', $categories->firstWhere('slug', 'gaming')) }}" class="promo-button">Shop now</a>
            </div>
          </article>

          <article class="promo-card">
            <img src="{{ asset('images/promo-tablet.jpg') }}" alt="Tablet promo">
            <div class="promo-text">
              <h3>Tablets up to -25%</h3>
              <p>Limited offer</p>
              <a href="{{ route('categories.show', $categories->firstWhere('slug', 'sale')) }}" class="promo-button">Shop now</a>
            </div>
          </article>

          <article class="promo-card">
            <img src="{{ asset('images/promo-gpu.jpg') }}" alt="Graphics card promo">
            <div class="promo-text">
              <h3>Graphics cards up to -40%</h3>
              <p>Superb offer</p>
              <a href="{{ route('categories.show', $categories->firstWhere('slug', 'pc-components')) }}" class="promo-button">Shop now</a>
            </div>
          </article>
        </div>
      </section>

      <section class="featured-products">
        <div class="section-header">
          <h2>Recommended products</h2>
          <a href="{{ route('categories.show', $categories->first()) }}" class="section-link">View all</a>
        </div>

        <div class="products-grid">
          @foreach ($featuredProducts as $product)
            <article class="product-card">
              <a href="{{ route('products.show', $product) }}" class="product-image-link">
                <img src="{{ asset($product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $product->name }}">
              </a>
              <h3>{{ $product->name }}</h3>
              <p class="product-price">{{ number_format((float) $product->price, 2, '.', ' ') }} EUR</p>
                <div class="product-actions">
                    @if (in_array($product->id, $favouriteIds))
                        <form action="{{ route('favourites.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="product-button-heart active">♥</button>
                        </form>
                    @else
                        <form action="{{ route('favourites.store', $product) }}" method="POST">
                            @csrf
                            <button type="submit" class="product-button-heart">♡</button>
                        </form>
                    @endif

                    <a href="{{ route('products.show', $product) }}" class="product-button">
                        View product
                    </a>
                </div>

            </article>
          @endforeach
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
