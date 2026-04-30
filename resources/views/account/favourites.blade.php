<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TechnoDom - Favourites</title>
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
      <section class="featured-products">
        <div class="section-header">
          <h2>Favourites</h2>
        </div>

        <div class="products-grid">

          {{-- Если список пустой — показываем сообщение --}}
          @if ($favourites->isEmpty())
            <p>You have no favourites yet.</p>

          @else
            {{-- Перебираем каждую запись из таблицы favourites --}}
            @foreach ($favourites as $favourite)
              <article class="product-card">

                {{-- $favourite->product — это связанный товар через belongsTo --}}
                <a href="{{ route('products.show', $favourite->product) }}" class="product-image-link">
                  <img src="{{ asset($favourite->product->image_path) }}" alt="{{ $favourite->product->name }}">
                </a>

                <h3>{{ $favourite->product->name }}</h3>
                <p class="product-price">{{ number_format($favourite->product->price, 2) }} EUR</p>

                <a href="{{ route('products.show', $favourite->product) }}" class="product-button">
                  View product
                </a>

                {{-- Форма для удаления из избранного --}}
                <form action="{{ route('favourites.destroy', $favourite->product) }}" method="POST">
                  @csrf              {{-- защита от CSRF атак --}}
                  @method('DELETE')  {{-- HTML не умеет DELETE, Laravel подменяет метод --}}
                  <button type="submit" class="remove-favourite">Remove</button>
                </form>

              </article>
            @endforeach
          @endif

        </div>
      </section>
    </main>

    <footer class="site-footer">
      <ul class="footer-list">
        <li class="footer-item"><a href="#">About us</a></li>
        <li class="footer-item"><a href="#">Contacts</a></li>
        <li class="footer-item"><a href="tac.txt">Terms &amp; Conditions</a></li>
        <li class="footer-item"><a href="#">FAQ</a></li>
        <li class="footer-item"><a href="#">Support</a></li>
      </ul>
    </footer>

  </div>
  <script src="{{ asset('js/hamurger-menu.js') }}"></script>
</body>

</html>
