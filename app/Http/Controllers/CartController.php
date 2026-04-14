<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    //add a product to the cart
    public function add(Request $request): RedirectResponse
    {
        // Validate input
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        // Get the product to check stock
        $product = Product::find($validated['product_id']);

        // ensure quantity doesn't exceed stock
        if ($validated['quantity'] > $product->stock) {
            return back()->withErrors([
                'quantity' => "Only {$product->stock} items available in stock.",
            ]);
        }

        //determine if user is authenticated or guest
        if (auth()->check()) {
            // Authenticated user
            $cartItem = CartItem::firstOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'user_id' => auth()->id(),
                ],
                ['quantity' => 0]
            );
        } else {
            //guest user
            $cartItem = CartItem::firstOrCreate(
                [
                    'product_id' => $validated['product_id'],
                    'session_id' => session()->getId(),
                ],
                ['quantity' => 0]
            );
        }

        //update quantity
        $cartItem->quantity += (int) $validated['quantity'];
        $cartItem->save();

        return back()->with('success', "{$product->name} added to cart!");
    }

    // Display the shopping cart
    public function show(Request $request): View
    {
        //retrieve cart items
        if (auth()->check()) {
            $cartItems = CartItem::with(['product', 'product.category', 'product.brand'])
                ->where('user_id', auth()->id())
                ->get();
        } else {
            $cartItems = CartItem::with(['product', 'product.category', 'product.brand'])
                ->where('session_id', session()->getId())
                ->get();
        }

        //calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $subtotal, // Can add shipping, taxes, etc. later
        ]);
    }

    // Update the quantity of a cart item
    public function updateQuantity(Request $request, CartItem $cartItem): RedirectResponse
    {
        //check authorization
        if (!$this->authorizeCartItem($cartItem)) {
            abort(403, 'Unauthorized');
        }

        //validate new quantity
        $validated = $request->validate([
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        //check stock
        if ($validated['quantity'] > $cartItem->product->stock) {
            return back()->withErrors([
                'quantity' => "Only {$cartItem->product->stock} items available in stock.",
            ]);
        }

        //update quantity
        $cartItem->update(['quantity' => (int) $validated['quantity']]);

        return back()->with('success', 'Cart updated!');
    }

    //remove an item from the cart
    public function removeItem(CartItem $cartItem): RedirectResponse
    {
        // Check authorization
        if (!$this->authorizeCartItem($cartItem)) {
            abort(403, 'Unauthorized');
        }

        $productName = $cartItem->product->name;
        $cartItem->delete();

        return back()->with('success', "{$productName} removed from cart!");
    }

    //check if current user/session owns the cart item
    private function authorizeCartItem(CartItem $cartItem): bool
    {
        if (auth()->check()) {
            return $cartItem->user_id === auth()->id();
        }

        return $cartItem->session_id === session()->getId();
    }
}
