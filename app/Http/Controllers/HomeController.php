<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role == 'admin') {
            return redirect('/admin/dashboard');
        }

        // Ambil data filter kategori dari URL (contoh: ?category=Laptop)
        $categoryFilter = $request->query('category');

        // Mulai base query untuk mengambil produk yang stoknya masih ada
        $query = Product::where('stock', '>', 0);

        // Jika customer memilih salah satu kategori, tambahkan filter di query
        if ($categoryFilter) {
            $query->where('category', $categoryFilter);
        }

        // Urutkan dari yang terbaru dan ambil datanya
        $products = $query->latest()->get();
        
        // Return ke view customer lama kamu 'user.index'
        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $seller = User::where('role', 'admin')->first();

        return view('user.show', compact('product', 'seller'));
    }

    /**
     * Tampilkan Halaman Profile User
     */
    public function profile()
    {
        return view('user.profile');
    }

    /**
     * Proses Update Data Akun, Avatar, dan Alamat Default
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'address' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Handle upload foto profil/avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            // Hapus foto profile lama dari storage jika sebelumnya sudah ada
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            
            // Simpan foto baru ke folder public/avatars
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Update data teks lainnya
        $user->name = $request->name;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();

        return redirect()->back()->with('success', 'Profil dan alamat default Anda berhasil diperbarui!');
    }
}