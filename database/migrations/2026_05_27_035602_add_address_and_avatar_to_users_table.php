<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function run(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom address setelah kolom email
            $table->text('address')->nullable()->after('email');
            
            // Menambahkan kolom avatar setelah kolom address untuk foto profile
            $table->string('avatar')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['address', 'avatar']);
        });
    }
};