<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $category->name }} | TechnoDom</title>
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

                    <a href="#" class="header-icon">
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
        <div class="products-page">
            <div class="catalog-header products-header">
                <div class="products-heading">
                    <div class="breadcrumb">
                        <a href="{{ route('home') }}">Home</a>
                        <span>/</span>
                        <span>{{ $category->name }}</span>
                    </div>
                    <h1 class="catalog-title">{{ $category->name }}</h1>
                    <p class="products-subtitle">{{ $category->description }}</p>
                </div>
            </div>

            <section class="products-toolbar">
                <div class="toolbar-text">
                    <p>Showing {{ $products->count() }} of {{ $products->total() }} products from the {{ $category->name }} category</p>
                </div>

                <form class="sort-form" method="GET" action="{{ route('categories.show', $category) }}">
                    <input type="hidden" name="price_from" value="{{ $filters['price_from'] }}">
                    <input type="hidden" name="price_to" value="{{ $filters['price_to'] }}">

                    @foreach ($filters['brands'] as $brandSlug)
                        <input type="hidden" name="brands[]" value="{{ $brandSlug }}">
                    @endforeach

                    @foreach ($filters['colors'] as $color)
                        <input type="hidden" name="colors[]" value="{{ $color }}">
                    @endforeach

                    @foreach ($filters['ram'] as $ramValue)
                        <input type="hidden" name="ram[]" value="{{ $ramValue }}">
                    @endforeach

                    <label for="sort">Sort by</label>
                    <select id="sort" name="sort" onchange="this.form.submit()">
                        <option value="" @selected($filters['sort'] === '')>Featured</option>
                        <option value="price_asc" @selected($filters['sort'] === 'price_asc')>Price: low to high</option>
                        <option value="price_desc" @selected($filters['sort'] === 'price_desc')>Price: high to low</option>
                    </select>
                </form>
            </section>

            <section class="products-layout">
                <aside class="filters-sidebar">
                    <form class="filters-card" method="GET" action="{{ route('categories.show', $category) }}">
                        <h2 class="filters-title">Filters</h2>

                        <div class="filter-group">
                            <h3>Price range</h3>
                            <div class="filter-field">
                                <label for="price_from">From</label>
                                <input id="price_from" type="number" min="0" step="0.01" name="price_from" value="{{ $filters['price_from'] }}">
                            </div>
                            <div class="filter-field">
                                <label for="price_to">To</label>
                                <input id="price_to" type="number" min="0" step="0.01" name="price_to" value="{{ $filters['price_to'] }}">
                            </div>
                        </div>

                        <div class="filter-group">
                            <h3>Brand</h3>
                            @foreach ($availableBrands as $brand)
                                <label class="filter-option">
                                    <input type="checkbox" name="brands[]" value="{{ $brand->slug }}" @checked(in_array($brand->slug, $filters['brands'], true))>
                                    {{ $brand->name }}
                                </label>
                            @endforeach
                        </div>

                        <div class="filter-group">
                            <h3>Color</h3>
                            @foreach ($availableColors as $color)
                                <label class="filter-option">
                                    <input type="checkbox" name="colors[]" value="{{ $color }}" @checked(in_array($color, $filters['colors'], true))>
                                    {{ $color }}
                                </label>
                            @endforeach
                        </div>

                        <div class="filter-group">
                            <h3>RAM</h3>
                            @foreach ($availableRam as $ramValue)
                                <label class="filter-option">
                                    <input type="checkbox" name="ram[]" value="{{ $ramValue }}" @checked(in_array((int) $ramValue, $filters['ram'], true))>
                                    {{ $ramValue }} GB
                                </label>
                            @endforeach
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="product-button filter-submit">Apply filters</button>
                            <a href="{{ route('categories.show', $category) }}" class="filter-reset">Reset filters</a>
                        </div>
                    </form>
                </aside>

                <div class="products-content">
                    @if ($products->isEmpty())
                        <div class="filters-card">
                            <h2 class="filters-title">No products yet</h2>
                            <p class="products-subtitle">No products match the current filter combination in this category.</p>
                        </div>
                    @else
                        <div class="products-grid products-grid-page">
                            @foreach ($products as $product)
                                <article class="product-card product-card-page">
                                    <img src="{{ asset($product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $product->name }}">
                                    <div class="product-card-content">
                                        <h3>{{ $product->name }}</h3>
                                        <p class="product-specs">
                                            {{ $product->brand->name }} | {{ $product->color }} | {{ $product->ram_gb }} GB RAM | Stock: {{ $product->stock }}
                                        </p>
                                        <p class="product-price">{{ number_format((float) $product->price, 2, '.', ' ') }} EUR</p>
                                    </div>
                                    <div class="product-card-actions">
                                        <span class="product-link">Product detail comes next</span>
                                        <a href="#" class="product-button">Add to cart</a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        @if ($products->hasPages())
                            <nav class="pagination" aria-label="Products pagination">
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
                    @endif
                </div>
            </section>
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
