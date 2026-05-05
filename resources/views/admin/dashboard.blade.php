<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width = device-width, initial-scale = 1.0">
  <title>Admin Dashboard | TechnoDom</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>
  <div class="admin-page-shell">
    <header class="admin-header">
      <div class="admin-header-inner">
        <div class="admin-brand">
          <img src="{{ asset('images/logo.png') }}" alt="TechnoDom logo">
          <div class="admin-brand-copy">
            <h1>TechnoDom Admin</h1>
          </div>
        </div>

        <div class="admin-header-actions">
          <a href="{{ route('home') }}" class="admin-link">Back to Store</a>
          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="admin-link admin-link-danger">Log Out</button>
          </form>
        </div>
      </div>
    </header>

    <main class="admin-main admin-dashboard-main">
      <div class="admin-dashboard-layout">
        <section class="admin-panel-card">
          <h2>Existing Products</h2>
          <div class="admin-products-list">
            @forelse ($products as $product)
              <article class="admin-product-row">
                <div class="admin-product-summary">
                  <img src="{{ asset($product->image_path ?? 'images/product-1.jpg') }}" alt="{{ $product->name }}">
                  <div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <span>Price: {{ number_format((float) $product->price, 2, '.', ' ') }} EUR | Amount left: {{ $product->stock }} | Category: {{ $product->category->name }}</span>
                  </div>
                </div>

                <div class="admin-product-actions">
                  <a href="#edit-product" class="admin-link">Edit</a>
                  <button type="button" class="admin-link admin-link-danger">Delete product</button>
                </div>
              </article>
            @empty
              <p>No products in the catalog yet.</p>
            @endforelse
          </div>
        </section>

        <section class="admin-panel-card" id="create-product">
          <h2>Create New Product</h2>
          <p class="admin-panel-text">Use this form to add a new product to the catalog. Save logic comes next.</p>

          <form class="admin-form admin-product-form">
            <div class="admin-form-grid">
              <div class="form-field">
                <label for="new-product-name">Name of the product</label>
                <input id="new-product-name" type="text" placeholder="For example Lenovo IdeaPad Slim 5" required>
              </div>

              <div class="form-field">
                <label for="new-product-price">Price</label>
                <input id="new-product-price" type="number" min="0" placeholder="899" required>
              </div>

              <div class="form-field">
                <label for="new-product-amount">Amount</label>
                <input id="new-product-amount" type="number" min="0" placeholder="7" required>
              </div>

              <div class="form-field">
                <label for="new-product-category">Category</label>
                <select id="new-product-category" required>
                  <option value="">Select category</option>
                  @foreach ($categories as $category)
                    <option>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-ram">RAM</label>
                <select id="new-product-ram" required>
                  <option value="">Select RAM</option>
                  @foreach ($ramOptions as $ramOption)
                    <option>{{ $ramOption }} GB</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-color">Color</label>
                <select id="new-product-color" required>
                  <option value="">Select color</option>
                  @foreach ($colorOptions as $colorOption)
                    <option>{{ $colorOption }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-brand">Brand</label>
                <select id="new-product-brand" required>
                  <option value="">Select brand</option>
                  @foreach ($brands as $brand)
                    <option>{{ $brand->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-field">
              <label for="new-product-description">Short description</label>
              <textarea id="new-product-description" rows="4" placeholder="Write a short product description." required></textarea>
            </div>

            <div class="admin-form-grid">
              <div class="form-field">
                <label for="new-product-image-1">Photo 1</label>
                <input id="new-product-image-1" type="file" accept="image/*" required>
              </div>

              <div class="form-field">
                <label for="new-product-image-2">Photo 2</label>
                <input id="new-product-image-2" type="file" accept="image/*" required>
              </div>
            </div>

            <div class="admin-form-actions">
              <button type="button" class="admin-button">Save product</button>
            </div>
          </form>
        </section>

        <section class="admin-panel-card" id="edit-product">
          <h2>Edit Existing Product</h2>
          <p class="admin-panel-text">Update the selected product information using the form below. Update logic comes next.</p>

          @if ($selectedProduct)
            <form class="admin-form admin-product-form">
              <div class="admin-form-grid">
                <div class="form-field">
                  <label for="edit-product-name">Name of the product</label>
                  <input id="edit-product-name" type="text" value="{{ $selectedProduct->name }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-price">Price</label>
                  <input id="edit-product-price" type="number" min="0" value="{{ $selectedProduct->price }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-amount">Amount</label>
                  <input id="edit-product-amount" type="number" min="0" value="{{ $selectedProduct->stock }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-category">Category</label>
                  <select id="edit-product-category" required>
                    @foreach ($categories as $category)
                      <option @selected($selectedProduct->category_id === $category->id)>{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-ram">RAM</label>
                  <select id="edit-product-ram" required>
                    @foreach ($ramOptions as $ramOption)
                      <option @selected($selectedProduct->ram_gb === $ramOption)>{{ $ramOption }} GB</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-color">Color</label>
                  <select id="edit-product-color" required>
                    @foreach ($colorOptions as $colorOption)
                      <option @selected($selectedProduct->color === $colorOption)>{{ $colorOption }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-brand">Brand</label>
                  <select id="edit-product-brand" required>
                    @foreach ($brands as $brand)
                      <option @selected($selectedProduct->brand_id === $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-field">
                <label for="edit-product-description">Short description</label>
                <textarea id="edit-product-description" rows="4" required>{{ $selectedProduct->description }}</textarea>
              </div>

              <div class="admin-current-images">
                <h3>Current Photos</h3>

                <div class="admin-image-grid">
                  <article class="admin-image-card">
                    <img src="{{ asset($selectedProduct->image_path ?? 'images/product-1.jpg') }}" alt="Current product photo 1">
                  </article>

                  <article class="admin-image-card">
                    <img src="{{ asset($selectedProduct->image_path ?? 'images/product-1.jpg') }}" alt="Current product photo 2">
                  </article>
                </div>
              </div>

              <div class="admin-form-grid">
                <div class="form-field">
                  <label for="replace-image-1">Replace photo 1</label>
                  <input id="replace-image-1" type="file" accept="image/*">
                </div>

                <div class="form-field">
                  <label for="replace-image-2">Replace photo 2</label>
                  <input id="replace-image-2" type="file" accept="image/*">
                </div>
              </div>

              <div class="admin-form-actions">
                <button type="button" class="admin-button">Update product</button>
                <button type="button" class="admin-link admin-link-danger">Delete this product</button>
              </div>
            </form>
          @else
            <p>No product available for editing yet.</p>
          @endif
        </section>
      </div>
    </main>

    <footer class="admin-footer">
      <p>Authorized staff only.</p>
    </footer>
  </div>
</body>

</html>
