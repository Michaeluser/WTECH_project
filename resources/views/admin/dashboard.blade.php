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
        @if (session('success'))
          <div class="admin-auth-success">
            {{ session('success') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="admin-auth-error">
            <ul class="admin-error-list">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

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
                  <a href="{{ route('admin.dashboard', ['product' => $product->id]) }}#edit-product" class="admin-link">Edit</a>
                  <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-link admin-link-danger">Delete product</button>
                  </form>
                </div>
              </article>
            @empty
              <p>No products in the catalog yet.</p>
            @endforelse
          </div>

          @if ($products->hasPages())
            <nav class="pagination" aria-label="Admin products pagination">
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
        </section>

        <section class="admin-panel-card" id="create-product">
          <h2>Create New Product</h2>
          <p class="admin-panel-text">Use this form to add a new product to the catalog. Upload from 2 to 5 product photos.</p>

          <form class="admin-form admin-product-form" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-grid">
              <div class="form-field">
                <label for="new-product-name">Name of the product</label>
                <input id="new-product-name" name="name" type="text" value="{{ old('name') }}" placeholder="For example Lenovo IdeaPad Slim 5" required>
              </div>

              <div class="form-field">
                <label for="new-product-price">Price</label>
                <input id="new-product-price" name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" placeholder="899" required>
              </div>

              <div class="form-field">
                <label for="new-product-amount">Amount</label>
                <input id="new-product-amount" name="stock" type="number" min="0" value="{{ old('stock') }}" placeholder="7" required>
              </div>

              <div class="form-field">
                <label for="new-product-category">Category</label>
                <select id="new-product-category" name="category_id" data-category-select required>
                  <option value="">Select category</option>
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}" data-category-slug="{{ $category->slug }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-line">Subcategory</label>
                <select id="new-product-line" name="line_key" data-line-select required>
                  <option value="">Select subcategory</option>
                  @foreach ($categories as $category)
                    @if (isset($lineOptions[$category->slug]))
                      @foreach ($lineOptions[$category->slug] as $lineKey => $lineLabel)
                        <option value="{{ $lineKey }}" data-category-slug="{{ $category->slug }}" @selected(old('line_key') === $lineKey)>{{ $lineLabel }}</option>
                      @endforeach
                    @endif
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-ram">RAM</label>
                <select id="new-product-ram" name="ram_gb" required>
                  <option value="">Select RAM</option>
                  @foreach ($ramOptions as $ramOption)
                    <option value="{{ $ramOption }}" @selected((string) old('ram_gb') === (string) $ramOption)>{{ $ramOption }} GB</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-color">Color</label>
                <select id="new-product-color" name="color" required>
                  <option value="">Select color</option>
                  @foreach ($colorOptions as $colorOption)
                    <option value="{{ $colorOption }}" @selected(old('color') === $colorOption)>{{ $colorOption }}</option>
                  @endforeach
                </select>
              </div>

              <div class="form-field">
                <label for="new-product-brand">Brand</label>
                <select id="new-product-brand" name="brand_id" required>
                  <option value="">Select brand</option>
                  @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" @selected((string) old('brand_id') === (string) $brand->id)>{{ $brand->name }}</option>
                  @endforeach
                </select>
              </div>

            </div>

            <div class="form-field">
              <label for="new-product-description">Short description</label>
              <textarea id="new-product-description" name="description" rows="4" placeholder="Write a short product description." required>{{ old('description') }}</textarea>
            </div>

            <div class="admin-dynamic-upload" data-image-inputs data-max-images="5">
              <div class="admin-form-grid" data-image-inputs-list>
                <div class="form-field">
                  <label for="new-product-image-1">Photo 1</label>
                  <input id="new-product-image-1" name="images[]" type="file" accept="image/*" required>
                </div>

                <div class="form-field">
                  <label for="new-product-image-2">Photo 2</label>
                  <input id="new-product-image-2" name="images[]" type="file" accept="image/*" required>
                </div>
              </div>

              <button
                type="button"
                class="admin-link admin-add-photo-button"
                data-add-image-input
                data-input-prefix="new-product-image"
              >
                Add another photo
              </button>
            </div>

            <div class="admin-form-actions">
              <button type="submit" class="admin-button">Save product</button>
            </div>
          </form>
        </section>

        <section class="admin-panel-card" id="edit-product">
          <h2>Edit Existing Product</h2>
          <p class="admin-panel-text">Update the selected product information below. To replace gallery photos, upload a new set from 2 to 5 images.</p>

          @if ($selectedProduct)
            <form class="admin-form admin-product-form" method="POST" action="{{ route('admin.products.update', $selectedProduct) }}" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="admin-form-grid">
                <div class="form-field">
                  <label for="edit-product-name">Name of the product</label>
                  <input id="edit-product-name" name="name" type="text" value="{{ old('name', $selectedProduct->name) }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-price">Price</label>
                  <input id="edit-product-price" name="price" type="number" min="0" step="0.01" value="{{ old('price', $selectedProduct->price) }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-amount">Amount</label>
                  <input id="edit-product-amount" name="stock" type="number" min="0" value="{{ old('stock', $selectedProduct->stock) }}" required>
                </div>

                <div class="form-field">
                  <label for="edit-product-category">Category</label>
                  <select id="edit-product-category" name="category_id" data-category-select required>
                    @foreach ($categories as $category)
                      <option value="{{ $category->id }}" data-category-slug="{{ $category->slug }}" @selected((string) old('category_id', $selectedProduct->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-line">Subcategory</label>
                  <select id="edit-product-line" name="line_key" data-line-select required>
                    <option value="">Select subcategory</option>
                    @foreach ($categories as $category)
                      @if (isset($lineOptions[$category->slug]))
                        @foreach ($lineOptions[$category->slug] as $lineKey => $lineLabel)
                          <option value="{{ $lineKey }}" data-category-slug="{{ $category->slug }}" @selected(old('line_key', $selectedProductLineKey) === $lineKey)>{{ $lineLabel }}</option>
                        @endforeach
                      @endif
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-ram">RAM</label>
                  <select id="edit-product-ram" name="ram_gb" required>
                    @foreach ($ramOptions as $ramOption)
                      <option value="{{ $ramOption }}" @selected((string) old('ram_gb', $selectedProduct->ram_gb) === (string) $ramOption)>{{ $ramOption }} GB</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-color">Color</label>
                  <select id="edit-product-color" name="color" required>
                    @foreach ($colorOptions as $colorOption)
                      <option value="{{ $colorOption }}" @selected(old('color', $selectedProduct->color) === $colorOption)>{{ $colorOption }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-brand">Brand</label>
                  <select id="edit-product-brand" name="brand_id" required>
                    @foreach ($brands as $brand)
                      <option value="{{ $brand->id }}" @selected((string) old('brand_id', $selectedProduct->brand_id) === (string) $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-field">
                  <label for="edit-product-featured">Featured on home page</label>
                  <select id="edit-product-featured" name="is_featured" required>
                    <option value="0" @selected((string) old('is_featured', (int) $selectedProduct->is_featured) === '0')>No</option>
                    <option value="1" @selected((string) old('is_featured', (int) $selectedProduct->is_featured) === '1')>Yes</option>
                  </select>
                </div>
              </div>

              <div class="form-field">
                <label for="edit-product-description">Short description</label>
                <textarea id="edit-product-description" name="description" rows="4" required>{{ old('description', $selectedProduct->description) }}</textarea>
              </div>

              <div class="admin-current-images">
                <h3>Current Photos</h3>

                <div class="admin-image-grid">
                  @foreach ($selectedProduct->galleryImages() as $imagePath)
                    <article class="admin-image-card">
                      <img src="{{ asset($imagePath) }}" alt="Current product photo">
                    </article>
                  @endforeach
                </div>
              </div>

              <div class="admin-dynamic-upload" data-image-inputs data-max-images="5" data-start-empty="true">
                <div class="admin-form-grid" data-image-inputs-list></div>

                <div class="admin-form-actions">
                  <button
                    type="button"
                    class="admin-link"
                    data-start-image-replace
                    data-input-prefix="replace-image"
                  >
                    Replace current photos
                  </button>

                  <button
                    type="button"
                    class="admin-link admin-add-photo-button"
                    data-add-image-input
                    data-input-prefix="replace-image"
                    hidden
                  >
                    Add another photo
                  </button>
                </div>
              </div>

              <div class="admin-form-actions">
                <button type="submit" class="admin-button">Update product</button>
              </div>
            </form>

            <form method="POST" action="{{ route('admin.products.destroy', $selectedProduct) }}" class="admin-standalone-danger-form">
              @csrf
              @method('DELETE')
              <button type="submit" class="admin-link admin-link-danger">Delete this product</button>
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

  <script src="{{ asset('js/admin-product-images.js') }}"></script>
</body>

</html>
