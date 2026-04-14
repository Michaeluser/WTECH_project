<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
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
        // cart items
        if (auth()->check()) {
            $cartItems = CartItem::with(['product', 'product.category', 'product.brand'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $cartItems = CartItem::with(['product', 'product.category', 'product.brand'])
                ->where('session_id', session()->getId())
                ->get();
        }

        // calculate
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.cart', [
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
}
