<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminController extends Controller
{
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

    public function dashboard(): View
    {
        abort_unless($this->isStaff(), 403, 'Staff access only.');

        $products = Product::query()
            ->with(['category', 'brand'])
            ->latest('id')
            ->paginate(6)
            ->withQueryString();

        $selectedProduct = $products->first();

        return view('admin.dashboard', [
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'categories' => Category::query()->orderBy('nav_order')->orderBy('id')->get(),
            'brands' => Brand::query()->orderBy('name')->get(),
            'ramOptions' => [8, 16, 32, 64],
            'colorOptions' => ['Gray', 'Black', 'Silver', 'White', 'Blue'],
        ]);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function isStaff(): bool
    {
        return auth()->check() && auth()->user()->is_staff;
    }
}
