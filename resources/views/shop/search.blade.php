<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ $query !== '' ? 'Search: ' . $query : 'Search' }} | TechnoDom
    </title>
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

            {{-- Search form — prefilled with the current query --}}
            <form class="search-form" action="{{ route('search') }}" method="GET">
                <input
                    type="text"
                    class="search-input"
                    placeholder="Search products"
                    name="q"
                    value="{{ $query }}"
                    autofocus
                >
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
                <span></span><span></span><span></span>
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
        <div class="products-page">

            <div class="catalog-header products-header">
                <div class="products-heading">
                    <div class="breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <span>Search</span>
                    </div>

                    @if ($query === '')
                        <h1 class="catalog-title">Search</h1>
                        <p class="products-subtitle">Enter a product name, brand, or keyword above.</p>
                    @else
                        <h1 class="catalog-title">Results for &ldquo;{{ $query }}&rdquo;</h1>
                        <p class="products-subtitle">
                            {{ $total }} {{ $total === 1 ? 'product' : 'products' }} found
                        </p>
                    @endif
                </div>
            </div>

            {{-- No query entered yet --}}
            @if ($query === '')
                <div class="filters-card" style="margin: 24px 0; padding: 32px; text-align: center;">
                    <p class="products-subtitle">Start typing to search across all products.</p>
                </div>

            {{-- Query entered but nothing found --}}
            @elseif ($products->isEmpty())
                <div class="filters-card" style="margin: 24px 0; padding: 32px; text-align: center;">
                    <h2 class="filters-title">No products found</h2>
                    <p class="products-subtitle">
                        No results for &ldquo;{{ $query }}&rdquo;. Try a different keyword or browse by category.
                    </p>
                </div>

            {{-- Results --}}
            @else
                <div class="products-content">
                    <div class="products-grid products-grid-page">
                        @foreach ($products as $product)
                            <article class="product-card product-card-page">
                                <img
                                    src="{{ asset($product->image_path ?? 'images/product-1.jpg') }}"
                                    alt="{{ $product->name }}"
                                >
                                <div class="product-card-content">
                                    <h3>{{ $product->name }}</h3>
                                    <p class="product-specs">
                                        {{ $product->brand->name }}
                                        | {{ $product->category->name }}
                                        | {{ $product->color }}
                                        @if ($product->ram_gb)
                                            | {{ $product->ram_gb }} GB RAM
                                        @endif
                                    </p>
                                    <p class="product-price">
                                        {{ number_format((float) $product->price, 2, '.', ' ') }} EUR
                                    </p>
                                </div>
                                <div class="product-card-actions">
                                    <a href="{{ route('products.show', $product) }}" class="product-link">
                                        View product
                                    </a>
                                    @if ($product->stock > 0)
                                        <form action="{{ route('cart.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="product-button">Add to cart</button>
                                        </form>
                                    @else
                                        <span class="product-button" style="opacity:.5; cursor:default;">Out of stock</span>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    @if ($products->hasPages())
                        <nav class="pagination" aria-label="Search results pagination">
                            @if ($products->onFirstPage())
                                <span class="pagination-link">Prev</span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="pagination-link">Prev</a>
                            @endif

                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page === $products->currentPage())
                                    <span class="pagination-link pagination-link-active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="pagination-link">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="pagination-link">Next</a>
                            @else
                                <span class="pagination-link">Next</span>
                            @endif
                        </nav>
                    @endif
                </div>
            @endif

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
