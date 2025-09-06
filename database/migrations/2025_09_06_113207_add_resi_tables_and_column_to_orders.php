<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Buat tabel resi_sources
        Schema::create('resi_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: JNE, J&T, POS
            $table->timestamps();
        });

        // 2. Buat tabel resis
        Schema::create('resis', function (Blueprint $table) {
            $table->id();
            $table->string('resi_number')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignId('resi_source_id')->nullable()->constrained('resi_sources')->cascadeOnDelete();
            $table->string('file_name')->nullable();
            $table->timestamps();
        });

        // 3. Tambah kolom resi_id ke tabel orders
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('resi_id')->nullable()->constrained('resis')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus foreign key dari orders
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('resi_id');
        });

        // Drop tabel resis dan resi_sources
        Schema::dropIfExists('resis');
        Schema::dropIfExists('resi_sources');
    }
};
