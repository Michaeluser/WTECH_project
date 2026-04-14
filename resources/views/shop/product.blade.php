<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $product->name }} - TechnoDom</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .product-details {
      max-width: 1200px;
      margin: 20px auto;
      padding: 0 20px;
    }

    .breadcrumb {
      margin-bottom: 20px;
      font-size: 14px;
      color: #666;
    }

    .breadcrumb a {
      color: #0066cc;
      text-decoration: none;
    }

    .breadcrumb a:hover {
      text-decoration: underline;
    }

    .product-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      margin-bottom: 40px;
    }

    .product-image {
      background: #f5f5f5;
      border-radius: 8px;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 400px;
    }

    .product-image img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }

    .product-info h1 {
      font-size: 32px;
      margin: 0 0 10px 0;
      color: #333;
    }

    .product-brand {
      color: #666;
      font-size: 16px;
      margin-bottom: 20px;
    }

    .product-price {
      font-size: 28px;
      color: #0066cc;
      font-weight: bold;
      margin-bottom: 30px;
    }

    .specs {
      background: #f9f9f9;
      padding: 20px;
      border-radius: 8px;
      margin-bottom: 30px;
    }

    .specs h3 {
      margin: 0 0 15px 0;
      font-size: 18px;
    }

    .spec-row {
      display: flex;
      justify-content: space-between;
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }

    .spec-row:last-child {
      border-bottom: none;
    }

    .spec-label {
      font-weight: 600;
      color: #555;
    }

    .spec-value {
      color: #333;
    }

    .stock-available {
      color: #28a745;
      font-weight: bold;
    }

    .stock-unavailable {
      color: #dc3545;
      font-weight: bold;
    }

    .description {
      margin-bottom: 30px;
    }

    .description h3 {
      font-size: 18px;
      margin: 0 0 10px 0;
    }

    .description p {
      color: #666;
      line-height: 1.6;
    }

    .add-to-cart-form {
      margin-bottom: 30px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
      color: #333;
      font-size: 14px;
    }

    .quantity-input {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .quantity-input input {
      width: 80px;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }

    .quantity-note {
      color: #666;
      font-size: 14px;
    }

    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }

    .btn-add-cart {
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
    }

    .btn-add-cart:hover {
      background: #0052a3;
    }

    .btn-add-cart:disabled {
      background: #ccc;
      cursor: not-allowed;
    }

    .out-of-stock {
      background: #fff3cd;
      border: 1px solid #ffc107;
      color: #856404;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    .out-of-stock p {
      margin: 0;
    }

    .back-link {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 1px solid #eee;
    }

    .back-link a {
      color: #0066cc;
      text-decoration: none;
      font-weight: 600;
    }

    .back-link a:hover {
      text-decoration: underline;
    }

    .success-message {
      background: #d4edda;
      border: 1px solid #c3e6cb;
      color: #155724;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    @media (max-width: 768px) {
      .product-container {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .product-info h1 {
        font-size: 24px;
      }

      .product-price {
        font-size: 24px;
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

        <form class="search-form" action="#" method="GET">
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

    <main class="product-details">
      {{-- Breadcrumb Navigation --}}
      <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span> / </span>
        <a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a>
        <span> / </span>
        <span>{{ $product->name }}</span>
      </div>

      {{-- Success Message --}}
      @if(session('success'))
      <div class="success-message">
        {{ session('success') }}
      </div>
      @endif

      {{-- Product Details Grid --}}
      <div class="product-container">
        {{-- Product Image --}}
        <div class="product-image">
          @if($product->image_path)
            <img src="{{ $product->image_path }}" alt="{{ $product->name }}">
          @else
            <span style="color: #999;">No image available</span>
          @endif
        </div>

        {{-- Product Information --}}
        <div class="product-info">
          <h1>{{ $product->name }}</h1>
          <p class="product-brand">{{ $product->brand->name }}</p>

          <div class="product-price">
            €{{ number_format($product->price, 2, '.', ' ') }}
          </div>

          {{-- Product Specifications --}}
          <div class="specs">
            <h3>Specifications</h3>
            <div class="spec-row">
              <span class="spec-label">Category:</span>
              <span class="spec-value">{{ $product->category->name }}</span>
            </div>
            <div class="spec-row">
              <span class="spec-label">Brand:</span>
              <span class="spec-value">{{ $product->brand->name }}</span>
            </div>
            @if($product->color)
            <div class="spec-row">
              <span class="spec-label">Color:</span>
              <span class="spec-value">{{ $product->color }}</span>
            </div>
            @endif
            @if($product->ram_gb)
            <div class="spec-row">
              <span class="spec-label">RAM:</span>
              <span class="spec-value">{{ $product->ram_gb }} GB</span>
            </div>
            @endif
            <div class="spec-row">
              <span class="spec-label">Stock:</span>
              <span class="spec-value">
                @if($product->stock > 0)
                  <span class="stock-available">{{ $product->stock }} available</span>
                @else
                  <span class="stock-unavailable">Out of Stock</span>
                @endif
              </span>
            </div>
          </div>

          {{-- Product Description --}}
          @if($product->description)
          <div class="description">
            <h3>Description</h3>
            <p>{{ $product->description }}</p>
          </div>
          @endif

          {{-- Add to Cart Form --}}
          @if($product->stock > 0)
          <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="form-group">
              <label for="quantity">Quantity</label>
              <div class="quantity-input">
                <input
                  type="number"
                  id="quantity"
                  name="quantity"
                  value="{{ old('quantity', 1) }}"
                  min="1"
                  max="{{ $product->stock }}"
                >
                <span class="quantity-note">(Max: {{ $product->stock }} available)</span>
              </div>
              @error('quantity')
                <p class="error-message">{{ $message }}</p>
              @enderror
            </div>

            <button type="submit" class="btn-add-cart">
              Add to Cart
            </button>
          </form>
          @else
          <div class="out-of-stock">
            <p><strong>Out of Stock</strong></p>
            <p>This product is currently unavailable.</p>
          </div>
          @endif

          {{-- Continue Shopping Link --}}
          <div class="back-link">
            <a href="{{ route('categories.show', $product->category) }}">
              ← Back to {{ $product->category->name }}
            </a>
          </div>
        </div>
      </div>
    </main>

    <footer class="site-footer">
      <p>&copy; 2024 TechnoDom. All rights reserved.</p>
    </footer>

  </div>
</body>

</html>
