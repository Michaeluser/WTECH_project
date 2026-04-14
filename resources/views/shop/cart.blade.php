<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart - TechnoDom</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .cart-container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 20px;
    }

    .cart-title {
      font-size: 32px;
      margin-bottom: 30px;
      color: #333;
    }

    .success-message {
      background: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .empty-cart {
      background: #f5f5f5;
      border-radius: 8px;
      padding: 60px 20px;
      text-align: center;
    }

    .empty-cart p {
      color: #666;
      font-size: 18px;
      margin-bottom: 20px;
    }

    .empty-cart a {
      color: #0066cc;
      text-decoration: none;
      font-weight: 600;
    }

    .empty-cart a:hover {
      text-decoration: underline;
    }

    .cart-grid {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 30px;
      margin-bottom: 30px;
    }

    .cart-items {
      background: white;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .cart-item-header {
      display: none;
      grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
      gap: 20px;
      background: #f5f5f5;
      padding: 15px;
      font-weight: 600;
      border-bottom: 1px solid #eee;
    }

    @media (min-width: 768px) {
      .cart-item-header {
        display: grid;
      }
    }

    .cart-item {
      border-bottom: 1px solid #eee;
      padding: 20px;
    }

    .cart-item:last-child {
      border-bottom: none;
    }

    .cart-item-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 15px;
    }

    @media (min-width: 768px) {
      .cart-item-grid {
        grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
        gap: 20px;
        align-items: center;
      }
    }

    .item-product {
      display: flex;
      gap: 15px;
    }

    .item-image {
      width: 80px;
      height: 80px;
      background: #f5f5f5;
      border-radius: 4px;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }

    .item-image img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .item-details h4 {
      margin: 0 0 5px 0;
      font-size: 16px;
    }

    .item-details a {
      color: #0066cc;
      text-decoration: none;
      font-weight: 600;
    }

    .item-details a:hover {
      text-decoration: underline;
    }

    .item-details p {
      margin: 3px 0;
      font-size: 14px;
      color: #666;
    }

    .quantity-form {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .quantity-form input {
      width: 70px;
      padding: 6px 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-align: center;
      font-size: 14px;
    }

    .quantity-form button {
      padding: 6px 12px;
      background: #0066cc;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.3s;
    }

    .quantity-form button:hover {
      background: #0052a3;
    }

    .item-price {
      font-weight: 600;
      min-width: 80px;
    }

    .item-subtotal {
      font-weight: 600;
      min-width: 100px;
    }

    .item-actions {
      display: flex;
      gap: 15px;
      align-items: center;
      justify-content: space-between;
    }

    @media (min-width: 768px) {
      .item-actions {
        justify-content: flex-end;
      }
    }

    .remove-form {
      margin: 0;
    }

    .remove-btn {
      color: #dc3545;
      background: none;
      border: none;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      padding: 0;
    }

    .remove-btn:hover {
      text-decoration: underline;
    }

    .error-message {
      color: #dc3545;
      font-size: 12px;
      margin-top: 5px;
    }

    .cart-summary {
      background: white;
      border-radius: 8px;
      padding: 25px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      position: sticky;
      top: 20px;
    }

    .summary-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .summary-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eee;
      color: #666;
    }

    .summary-row:last-of-type {
      border-bottom: none;
      font-size: 18px;
      font-weight: bold;
      color: #333;
      padding-top: 15px;
      padding-bottom: 0;
    }

    .summary-value {
      font-weight: 500;
    }

    .summary-total {
      color: #0066cc;
      font-weight: bold;
    }

    .checkout-btn {
      width: 100%;
      padding: 15px;
      background: #0066cc;
      color: white;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
      margin-top: 20px;
    }

    .checkout-btn:hover {
      background: #0052a3;
    }

    .continue-shopping {
      text-align: center;
      margin-top: 15px;
    }

    .continue-shopping a {
      color: #0066cc;
      text-decoration: none;
      font-size: 14px;
    }

    .continue-shopping a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .cart-grid {
        grid-template-columns: 1fr;
      }

      .cart-summary {
        position: static;
      }

      .item-product {
        flex-direction: column;
      }
    }
  </style>
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

    <main class="cart-container">
      <h1 class="cart-title">Shopping Cart</h1>

      {{-- Success Messages --}}
      @if(session('success'))
      <div class="success-message">
        {{ session('success') }}
      </div>
      @endif

      @if($cartItems->isEmpty())
        {{-- Empty Cart Message --}}
        <div class="empty-cart">
          <p>Your cart is empty</p>
          <a href="{{ route('home') }}">Continue Shopping</a>
        </div>
      @else
        <div class="cart-grid">
          {{-- Cart Items --}}
          <div>
            <div class="cart-items">
              {{-- Cart Table Header --}}
              <div class="cart-item-header">
                <div>Product</div>
                <div style="text-align: center;">Quantity</div>
                <div style="text-align: right;">Price</div>
                <div style="text-align: right;">Subtotal</div>
                <div style="text-align: right;">Action</div>
              </div>

              {{-- Cart Items List --}}
              @foreach($cartItems as $item)
              <div class="cart-item">
                <div class="cart-item-grid">
                  {{-- Product Info --}}
                  <div class="item-product">
                    <div class="item-image">
                      @if($item->product->image_path)
                        <img src="{{ $item->product->image_path }}" alt="{{ $item->product->name }}">
                      @else
                        <span style="color: #999; font-size: 12px;">No image</span>
                      @endif
                    </div>
                    <div class="item-details">
                      <h4>
                        <a href="{{ route('products.show', $item->product) }}">
                          {{ $item->product->name }}
                        </a>
                      </h4>
                      <p>{{ $item->product->brand->name }}</p>
                      @if($item->product->color)
                        <p>Color: {{ $item->product->color }}</p>
                      @endif
                    </div>
                  </div>

                  {{-- Quantity Update --}}
                  <div>
                    <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-form">
                      @csrf
                      @method('PUT')
                      <input
                        type="number"
                        name="quantity"
                        value="{{ $item->quantity }}"
                        min="1"
                        max="{{ $item->product->stock }}"
                      >
                      <button type="submit">Update</button>
                    </form>
                    @error('quantity')
                      <p class="error-message">{{ $message }}</p>
                    @enderror
                  </div>

                  {{-- Unit Price --}}
                  <div class="item-price">
                    €{{ number_format($item->product->price, 2, '.', ' ') }}
                  </div>

                  {{-- Subtotal --}}
                  <div class="item-subtotal">
                    €{{ number_format($item->product->price * $item->quantity, 2, '.', ' ') }}
                  </div>

                  {{-- Remove Button --}}
                  <div class="item-actions">
                    <form action="{{ route('cart.remove', $item) }}" method="POST" class="remove-form" onsubmit="return confirm('Remove this item from cart?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="remove-btn">Remove</button>
                    </form>
                  </div>
                </div>
              </div>
              @endforeach
            </div>

            {{-- Continue Shopping Link --}}
            <div style="margin-top: 20px;">
              <a href="{{ route('home') }}" style="color: #0066cc; text-decoration: none; font-weight: 600;">
                ← Continue Shopping
              </a>
            </div>
          </div>

          {{-- Cart Summary Sidebar --}}
          <div>
            <div class="cart-summary">
              <div class="summary-title">Order Summary</div>

              {{-- Items Count --}}
              <div class="summary-row">
                <span>Items:</span>
                <span class="summary-value">{{ $cartItems->count() }}</span>
              </div>

              {{-- Subtotal --}}
              <div class="summary-row">
                <span>Subtotal:</span>
                <span class="summary-value">€{{ number_format($subtotal, 2, '.', ' ') }}</span>
              </div>

              {{-- Shipping --}}
              <div class="summary-row">
                <span>Shipping:</span>
                <span class="summary-value">TBD</span>
              </div>

              {{-- Total --}}
              <div class="summary-row">
                <span>Total:</span>
                <span class="summary-total">€{{ number_format($total, 2, '.', ' ') }}</span>
              </div>

              {{-- Checkout Button --}}
              <button class="checkout-btn">Proceed to Checkout</button>

              {{-- Continue Shopping Link --}}
              <div class="continue-shopping">
                <a href="{{ route('home') }}">Continue Shopping</a>
              </div>
            </div>
          </div>
        </div>
      @endif
    </main>

    <footer class="site-footer">
      <p>&copy; 2024 TechnoDom. All rights reserved.</p>
    </footer>

  </div>
</body>

</html>
