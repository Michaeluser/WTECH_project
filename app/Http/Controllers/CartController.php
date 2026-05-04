<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function add(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        // check stock
        $product = Product::find($validated['product_id']);

        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors([
                'quantity' => "Only {$product->stock} items available in stock.",
            ]);
        }

        // auth or guest
        if (auth()->check()) {
            // auth user
            $cartItem = CartItem::firstOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'user_id' => auth()->id(),
                ],
                ['quantity' => 0]
            );
        } else {
            // guest
            $cartItem = CartItem::firstOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'session_id' => session()->getId(),
                ],
                ['quantity' => 0]
            );
        }

        // quantity
        $cartItem->quantity += (int) $validated['quantity'];
        $cartItem->save();

        return back()->with('success', "{$product->name} added to cart!");
    }

    public function show(Request $request): View
    {
        $cartItems = $this->getCurrentCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);

        return view('shop.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cartItems = $this->getCurrentCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')
                ->withErrors(['cart' => 'Your cart is empty. Add a product before checkout.']);
        }

        $subtotal = $this->calculateSubtotal($cartItems);

        return view('payment.checkout', [
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }

    public function updateQuantity(Request $request, CartItem $cartItem): RedirectResponse
    {

        if (! $this->authorizeCartItem($cartItem)) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        if ($validated['quantity'] > $cartItem->product->stock) {
            return back()->withErrors([
                'quantity' => "Only {$cartItem->product->stock} items available in stock.",
            ]);
        }

        // update quantity
        $cartItem->update(['quantity' => (int) $validated['quantity']]);

        return back()->with('success', 'Cart updated!');
    }

    public function removeItem(CartItem $cartItem): RedirectResponse
    {

        if (! $this->authorizeCartItem($cartItem)) {
            abort(403, 'Unauthorized');
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return back()->with('success', "{$productName} removed from cart!");
    }

    private function authorizeCartItem(CartItem $cartItem): bool
    {
        if (auth()->check()) {
            return $cartItem->user_id === auth()->id();
        }

        return $cartItem->session_id === session()->getId();
    }

    private function getCurrentCartItems(): Collection
    {
        if (auth()->check()) {
            return CartItem::with(['product', 'product.category', 'product.brand'])
                ->where('user_id', auth()->id())
                ->get();
        }

        return CartItem::with(['product', 'product.category', 'product.brand'])
            ->where('session_id', session()->getId())
            ->get();
    }

    private function calculateSubtotal(Collection $cartItems): float
    {
        return (float) $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }
}
