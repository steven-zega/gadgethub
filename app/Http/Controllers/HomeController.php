<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        $products = Product::where('stock', '>', 0)->latest()->get();
        
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $seller = User::where('role', 'admin')->first();

        return view('user.show', compact('product', 'seller'));
    }
}