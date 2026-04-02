<!DOCTYPE html>
<html lang = "en">

<head>
  <meta charset = "UTF-8">
  <meta name = "viewport" content = "width = device-width, initial-scale = 1.0">
  <title>TechnoDom</title>
  <link rel = "stylesheet" href = "{{ asset('css/style.css') }}">
</head>

<body>
  <div class = "page-container">

    <header class = "site-header">
      <div class = "header-inner-elements">

        <a href = "{{ route('home') }}" class = "logo">
          <img src = "{{ asset('images/logo.png') }}" alt = "TechnoDom logo">
        </a>

        <form class = "search-form" action = "#" method = "GET">
          <input type = "text" class = "search-input" placeholder = "Search products" name = "q">
        </form>

        <div class = "header-actions">

          <div class = "account-block">
            <span class = "account-name">My account</span>
            <a href = "#" class = "header-icon">
              <img src = "{{ asset('images/user.png') }}" alt = "User profile icon">
            </a>
          </div>

          <div class = "shop-icons">
            <a href = "#" class = "header-icon">
              <img src = "{{ asset('images/heart.png') }}" alt = "Wishlist icon">
            </a>

            <a href = "#" class = "header-icon">
              <img src = "{{ asset('images/cart.png') }}" alt = "Shopping cart icon">
            </a>
          </div>

        </div>

      </div>
    </header>

    <nav class = "main-nav">
      <div class = "nav-top">
        <span class = "nav-mobile-title">Categories</span>
        <button class = "nav-toggle" type = "button" aria-label = "Open categories" aria-expanded = "false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <ul class = "nav-list">
        <li class = "nav-item"><a href = "#">Laptops</a></li>
        <li class = "nav-item"><a href = "#">Phones</a></li>
        <li class = "nav-item"><a href = "#">PC components</a></li>
        <li class = "nav-item"><a href = "#">Monitors</a></li>
        <li class = "nav-item"><a href = "#">TV&amp;Audio</a></li>
        <li class = "nav-item"><a href = "#">Gaming</a></li>
        <li class = "nav-item"><a href = "#">Accessories</a></li>
        <li class = "nav-item"><a href = "#">Sale</a></li>
      </ul>
    </nav>

    <main class = "site-main">
      <section class = "hero-section">

        <div class = "hero-left">
          <article class = "main-banner">
            <img src = "{{ asset('images/banner-main.jpg') }}" alt = "Main homepage banner">
            <div class = "main-banner-content">
              <h2>Check out our new VR headset!</h2>
              <p>Discover the latest technology at a great price.</p>
              <a href = "#" class = "main-banner-button">Shop now</a>
            </div>
          </article>
        </div>

        <div class = "hero-right">
          <article class = "promo-card">
            <img src = "{{ asset('images/promo-monitor.jpg') }}" alt = "Monitor promo">
            <div class = "promo-text">
              <h3>Monitors up to -60%</h3>
              <p>Mega offer</p>
              <a href = "#" class = "promo-button">Shop now</a>
            </div>
          </article>

          <article class = "promo-card">
            <img src = "{{ asset('images/promo-laptop.jpg') }}" alt = "Laptop promo">
            <div class = "promo-text">
              <h3>Gaming laptops up to -20%</h3>
              <p>Limited offer</p>
              <a href = "#" class = "promo-button">Shop now</a>
            </div>
          </article>

          <article class = "promo-card">
            <img src = "{{ asset('images/promo-tablet.jpg') }}" alt = "Tablet promo">
            <div class = "promo-text">
              <h3>Tablets up to -25%</h3>
              <p>Limited offer</p>
              <a href = "#" class = "promo-button">Shop now</a>
            </div>
          </article>

          <article class = "promo-card">
            <img src = "{{ asset('images/promo-gpu.jpg') }}" alt = "Graphics card promo">
            <div class = "promo-text">
              <h3>Graphics cards up to -40%</h3>
              <p>Superb offer</p>
              <a href = "#" class = "promo-button">Shop now</a>
            </div>
          </article>
        </div>
      </section>

      <section class = "featured-products">
        <div class = "section-header">
          <h2>Recommended products</h2>
          <a href = "#" class = "section-link">View all</a>
        </div>

        <div class = "products-grid">

          <article class = "product-card">
            <img src = "{{ asset('images/product-1.jpg') }}" alt = "iPhone 17 Pro">
            <h3>iPhone 17 Pro 256 GB</h3>
            <p class = "product-price">1 339 €</p>
            <a href = "#" class = "product-button">Add to cart</a>
          </article>

          <article class = "product-card">
            <img src = "{{ asset('images/product-2.jpg') }}" alt = "iPhone 17 Pro Max">
            <h3>iPhone 17 Pro Max 256 GB</h3>
            <p class = "product-price">1 489 €</p>
            <a href = "#" class = "product-button">Add to cart</a>
          </article>

          <article class = "product-card">
            <img src = "{{ asset('images/product-3.jpg') }}" alt = "iPad Pro M5">
            <h3>iPad Pro M5 13&quot; 256 GB</h3>
            <p class = "product-price">1 479 €</p>
            <a href = "#" class = "product-button">Add to cart</a>
          </article>

          <article class = "product-card">
            <img src = "{{ asset('images/product-4.jpg') }}" alt = "iPad Pro 11">
            <h3>iPad Pro M5 11&quot; 256 GB</h3>
            <p class = "product-price">1 129 €</p>
            <a href = "#" class = "product-button">Add to cart</a>
          </article>

          <article class = "product-card">
            <img src = "{{ asset('images/product-5.jpg') }}" alt = "MacBook Air 13">
            <h3>MacBook Air 13&quot; M4</h3>
            <p class = "product-price">999 €</p>
            <a href = "#" class = "product-button">Add to cart</a>
          </article>

        </div>
      </section>
    </main>

    <footer class = "site-footer">
      <ul class = "footer-list">
        <li class = "footer-item"><a href = "#">About us</a></li>
        <li class = "footer-item"><a href = "#">Contacts</a></li>
        <li class = "footer-item"><a href = "#">Terms &amp; Conditions</a></li>
        <li class = "footer-item"><a href = "#">FAQ</a></li>
        <li class = "footer-item"><a href = "#">Support</a></li>
      </ul>
    </footer>

  </div>

  <script src = "{{ asset('js/hamurger-menu.js') }}"></script>
</body>

</html>