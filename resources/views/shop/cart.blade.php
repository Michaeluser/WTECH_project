<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - TechnoDom</title>
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

    <main class="site-main">
      <section class="cart-page">
        <h1 class="cart-title">Shopping cart</h1>

        <div class="steps-buttons-bar">
          <div class="checkout-steps">
            <ul class="checkout-steps-list">
              <li class="active">Shopping cart</li>
              <li>Shipping and Payment</li>
              <li>Location Details</li>
            </ul>
          </div>

          <a href="{{ route('home') }}" class="empty-cart-button">Shop</a>
        </div>

        @if(session('success'))
          <div class="cart-message cart-message-success">{{ session('success') }}</div>
        @endif

        @if($errors->has('cart'))
          <div class="cart-message cart-message-error">{{ $errors->first('cart') }}</div>
        @endif

        @if($cartItems->isEmpty())
          <div class="cart-layout">
            <div class="cart-items empty-cart-state">
              <p class="empty-cart-text">Your cart is empty.</p>
              <a href="{{ route('home') }}" class="cart-next-button">Continue shopping</a>
            </div>
          </div>
        @else
          <div class="cart-layout">
            <div class="cart-items">
              @foreach($cartItems as $item)
                <article class="cart-item">
                  <div class="cart-item-image">
                    <a href="{{ route('products.show', $item->product) }}">
                      <img src="{{ asset($item->product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $item->product->name }}">
                    </a>
                  </div>

                  <div class="cart-item-info">
                    <h2 class="cart-product-title">
                      <a href="{{ route('products.show', $item->product) }}" class="cart-product-link">{{ $item->product->name }}</a>
                    </h2>
                    <p class="cart-product-meta">{{ $item->product->brand->name }}</p>
                    @if($item->product->color)
                      <p class="cart-product-meta">Color: {{ $item->product->color }}</p>
                    @endif
                    <p class="cart-product-subtotal">Subtotal: {{ number_format((float) ($item->product->price * $item->quantity), 2, '.', ' ') }} EUR</p>
                  </div>

                  <div class="cart-item-quantity">
                    <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-quantity-form">
                      @csrf
                      @method('PUT')
                      <label for="quantity-{{ $item->id }}" class="cart-quantity-label">Qty</label>
                      <div class="cart-quantity-controls">
                        <input id="quantity-{{ $item->id }}" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}">
                        <button type="submit">Update</button>
                      </div>
                      @error('quantity')
                        <p class="cart-field-error">{{ $message }}</p>
                      @enderror
                    </form>
                  </div>

                  <div class="cart-item-price">
                    <span class="cart-price-label">Price</span>
                    <span>{{ number_format((float) $item->product->price, 2, '.', ' ') }} EUR</span>
                  </div>

                  <form action="{{ route('cart.remove', $item) }}" method="POST" class="cart-remove-form" onsubmit="return confirm('Remove this item from cart?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="cart-remove-button">Remove</button>
                  </form>
                </article>
              @endforeach
            </div>
          </div>

          <div class="cart-total">
            <h3>Order total: <span class="cart-total-price">{{ number_format($total, 2, '.', ' ') }} EUR</span></h3>
          </div>

          <div class="cart-actions">
            <a href="{{ route('checkout.show') }}" class="cart-next-button">Next</a>
          </div>
        @endif
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
