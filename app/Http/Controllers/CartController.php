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
    private const DELIVERY_METHODS = [
        'courier' => [
            'label' => 'Courier Delivery',
            'price' => 0.0,
        ],
        'express' => [
            'label' => 'Express Delivery',
            'price' => 15.0,
        ],
    ];

    private const PAYMENT_METHODS = [
        'card' => 'Credit Card',
        'bank' => 'Bank Transfer',
    ];

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
        $selectedDelivery = 'courier';
        $selectedPayment = 'card';
        $deliveryPrice = $this->getDeliveryPrice($selectedDelivery);

        return view('payment.checkout', [
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $this->calculateTotal($subtotal, $deliveryPrice),
            'deliveryMethods' => self::DELIVERY_METHODS,
            'paymentMethods' => self::PAYMENT_METHODS,
            'selectedDelivery' => $selectedDelivery,
            'selectedPayment' => $selectedPayment,
            'deliveryPrice' => $deliveryPrice,
            'deliveryMethodLabel' => $this->getDeliveryLabel($selectedDelivery),
        ]);
    }

    public function details(Request $request): View|RedirectResponse
    {
        $cartItems = $this->getCurrentCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')
                ->withErrors(['cart' => 'Your cart is empty. Add a product before checkout.']);
        }

        $deliveryMethod = $request->input('delivery_method', 'courier');
        $paymentMethod = $request->input('payment_method', 'card');

        if (! array_key_exists($deliveryMethod, self::DELIVERY_METHODS)) {
            $deliveryMethod = 'courier';
        }

        if (! array_key_exists($paymentMethod, self::PAYMENT_METHODS)) {
            $paymentMethod = 'card';
        }

        $subtotal = $this->calculateSubtotal($cartItems);
        $deliveryPrice = $this->getDeliveryPrice($deliveryMethod);

        return view('payment.details', [
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $this->calculateTotal($subtotal, $deliveryPrice),
            'deliveryMethod' => $deliveryMethod,
            'paymentMethod' => $paymentMethod,
            'deliveryMethodLabel' => $this->getDeliveryLabel($deliveryMethod),
            'deliveryPrice' => $deliveryPrice,
            'paymentMethodLabel' => self::PAYMENT_METHODS[$paymentMethod],
        ]);
    }

    public function confirmation(Request $request): View|RedirectResponse
    {
        $cartItems = $this->getCurrentCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.show')
                ->withErrors(['cart' => 'Your cart is empty. Add a product before checkout.']);
        }

        $deliveryMethod = $request->input('delivery_method', 'courier');
        $paymentMethod = $request->input('payment_method', 'card');

        if (! array_key_exists($deliveryMethod, self::DELIVERY_METHODS)) {
            $deliveryMethod = 'courier';
        }

        if (! array_key_exists($paymentMethod, self::PAYMENT_METHODS)) {
            $paymentMethod = 'card';
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:50'],
            'street_address' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $subtotal = $this->calculateSubtotal($cartItems);
        $deliveryPrice = $this->getDeliveryPrice($deliveryMethod);

        return view('payment.confirmation', [
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'total' => $this->calculateTotal($subtotal, $deliveryPrice),
            'deliveryMethodLabel' => $this->getDeliveryLabel($deliveryMethod),
            'deliveryPrice' => $deliveryPrice,
            'paymentMethodLabel' => self::PAYMENT_METHODS[$paymentMethod],
            'orderNumber' => 'TD-' . strtoupper(substr(session()->getId(), 0, 8)),
            'customer' => $validated,
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

    private function getDeliveryLabel(string $deliveryMethod): string
    {
        return self::DELIVERY_METHODS[$deliveryMethod]['label'];
    }

    private function getDeliveryPrice(string $deliveryMethod): float
    {
        return (float) self::DELIVERY_METHODS[$deliveryMethod]['price'];
    }

    private function calculateTotal(float $subtotal, float $deliveryPrice): float
    {
        return $subtotal + $deliveryPrice;
    }
}
