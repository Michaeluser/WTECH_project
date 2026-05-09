<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegister(): View
    {
        return view('account.registration');
    }

    public function register(Request $request): RedirectResponse
    {
        $guestSessionId = $request->session()->getId();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['required', 'string', 'max:20'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create($validated);
        $customerRoleId = Role::query()->where('role', 'customer')->value('id');

        if ($customerRoleId !== null) {
            $user->roles()->syncWithoutDetaching([$customerRoleId]);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $this->mergeGuestCartIntoUserCart($user, $guestSessionId);

        return redirect()->route('account');
    }

    public function showLogin(): View
    {
        return view('account.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $guestSessionId = $request->session()->getId();

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $this->mergeGuestCartIntoUserCart($request->user(), $guestSessionId);

            return redirect()->intended(route('account'));
        }

        return back()
            ->withErrors(['email' => 'Incorrect email or password.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function mergeGuestCartIntoUserCart(User $user, ?string $guestSessionId): void
    {
        if (! is_string($guestSessionId) || $guestSessionId === '') {
            return;
        }

        $guestCartItems = CartItem::with('product')
            ->where('session_id', $guestSessionId)
            ->get();

        foreach ($guestCartItems as $guestCartItem) {
            $userCartItem = CartItem::where('user_id', $user->id)
                ->where('product_id', $guestCartItem->product_id)
                ->first();

            if ($userCartItem) {
                $mergedQuantity = min(
                    $guestCartItem->product->stock,
                    $userCartItem->quantity + $guestCartItem->quantity
                );

                $userCartItem->update([
                    'quantity' => $mergedQuantity,
                ]);

                $guestCartItem->delete();

                continue;
            }

            $guestCartItem->update([
                'user_id' => $user->id,
                'session_id' => null,
                'quantity' => min($guestCartItem->product->stock, $guestCartItem->quantity),
            ]);
        }
    }
}
