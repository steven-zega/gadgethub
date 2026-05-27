<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Menggunakan enum agar pilihan kategori kaku & aman (Handphone, Laptop, Tablet)
            $table->enum('category', ['Handphone', 'Laptop', 'Tablet'])->after('name')->default('Handphone');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};