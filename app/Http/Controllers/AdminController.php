<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminController extends Controller
{
    private const RAM_OPTIONS = [8, 16, 32, 64];
    private const COLOR_OPTIONS = ['Gray', 'Black', 'Silver', 'White', 'Blue'];

    public function showLogin(): View
    {
        return view('admin.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Incorrect staff email or password.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $this->isStaff()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account does not have staff access.'])
                ->onlyInput('email');
        }

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request): View
    {
        $this->authorizeStaff();

        $products = Product::query()
            ->with(['category', 'brand', 'images'])
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        $selectedProduct = $this->resolveSelectedProduct($request, $products);

        return view('admin.dashboard', [
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'selectedProductLineKey' => $selectedProduct?->resolvedLineKey(),
            'categories' => $this->categories(),
            'brands' => $this->brands(),
            'ramOptions' => self::RAM_OPTIONS,
            'colorOptions' => self::COLOR_OPTIONS,
            'lineOptions' => Product::lineOptionsByCategory(),
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $this->validateProduct($request, true);

        $product = Product::create([
            'category_id' => (int) $validated['category_id'],
            'brand_id' => (int) $validated['brand_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name']),
            'description' => $validated['description'],
            'price' => (float) $validated['price'],
            'color' => $validated['color'],
            'ram_gb' => (int) $validated['ram_gb'],
            'stock' => (int) $validated['stock'],
            'line_key' => $validated['line_key'],
            'is_featured' => false,
        ]);

        $this->syncProductImages($product, $this->storeUploadedImages($request));

        return redirect()
            ->route('admin.dashboard', ['product' => $product->id])
            ->with('success', 'Product created successfully.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $this->authorizeStaff();

        $validated = $this->validateProduct($request, false);

        $product->update([
            'category_id' => (int) $validated['category_id'],
            'brand_id' => (int) $validated['brand_id'],
            'name' => $validated['name'],
            'slug' => $this->generateUniqueSlug($validated['name'], $product->id),
            'description' => $validated['description'],
            'price' => (float) $validated['price'],
            'color' => $validated['color'],
            'ram_gb' => (int) $validated['ram_gb'],
            'stock' => (int) $validated['stock'],
            'line_key' => $validated['line_key'],
            'is_featured' => $request->boolean('is_featured'),
        ]);

        $uploadedImages = $this->storeUploadedImages($request);

        if ($uploadedImages !== []) {
            $this->syncProductImages($product, $uploadedImages);
        }

        return redirect()
            ->route('admin.dashboard', ['product' => $product->id])
            ->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $this->authorizeStaff();

        $product->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Product deleted successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function authorizeStaff(): void
    {
        abort_unless($this->isStaff(), 403, 'Staff access only.');
    }

    private function isStaff(): bool
    {
        return auth()->check() && auth()->user()->is_staff;
    }

    private function resolveSelectedProduct(Request $request, LengthAwarePaginator $products): ?Product
    {
        $selectedProductId = $request->integer('product');

        if ($selectedProductId > 0) {
            $selectedProduct = Product::query()
                ->with(['category', 'brand', 'images'])
                ->find($selectedProductId);

            if ($selectedProduct !== null) {
                return $selectedProduct;
            }
        }

        return $products->getCollection()->first();
    }

    private function validateProduct(Request $request, bool $isCreate): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'line_key' => ['required', 'string', 'max:255'],
            'ram_gb' => ['required', 'integer', 'in:' . implode(',', self::RAM_OPTIONS)],
            'color' => ['required', 'string', 'in:' . implode(',', self::COLOR_OPTIONS)],
            'brand_id' => ['required', 'exists:brands,id'],
            'description' => ['required', 'string'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        $this->validateLineKey($validated['category_id'], $validated['line_key']);
        $this->validateImageCount($request, $isCreate);

        return $validated;
    }

    private function validateLineKey(int|string $categoryId, string $lineKey): void
    {
        $category = Category::query()->find((int) $categoryId);

        if ($category === null) {
            return;
        }

        $allowedLineKeys = Product::allowedLineKeysForCategorySlug($category->slug);

        if (! in_array($lineKey, $allowedLineKeys, true)) {
            throw ValidationException::withMessages([
                'line_key' => 'Please select a valid subcategory for this category.',
            ]);
        }
    }

    private function validateImageCount(Request $request, bool $isCreate): void
    {
        $images = $request->file('images', []);
        $uploadedImages = array_values(array_filter($images));
        $imageCount = count($uploadedImages);

        if ($isCreate && $imageCount < 2) {
            throw ValidationException::withMessages([
                'images' => 'Please upload at least 2 images.',
            ]);
        }

        if ($imageCount > 5) {
            throw ValidationException::withMessages([
                'images' => 'You can upload at most 5 images.',
            ]);
        }

        if (! $isCreate && $imageCount > 0 && $imageCount < 2) {
            throw ValidationException::withMessages([
                'images' => 'Please upload at least 2 images when replacing photos.',
            ]);
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreProductId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($ignoreProductId !== null, fn ($query) => $query->where('id', '!=', $ignoreProductId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function storeUploadedImages(Request $request): array
    {
        $images = array_values(array_filter($request->file('images', [])));

        if ($images === []) {
            return [];
        }

        $uploadPath = public_path('images/uploads');

        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $storedImagePaths = [];

        foreach ($images as $image) {
            $fileName = Str::uuid()->toString() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $fileName);
            $storedImagePaths[] = 'images/uploads/' . $fileName;
        }

        return $storedImagePaths;
    }

    private function syncProductImages(Product $product, array $imagePaths): void
    {
        if ($imagePaths === []) {
            return;
        }

        $product->images()->delete();

        foreach ($imagePaths as $imagePath) {
            $product->images()->create([
                'image_path' => $imagePath,
            ]);
        }

        $product->update([
            'image_path' => $imagePaths[0],
        ]);
    }

    private function categories()
    {
        return Category::query()->orderBy('nav_order')->orderBy('id')->get();
    }

    private function brands()
    {
        return Brand::query()->orderBy('name')->get();
    }
}
