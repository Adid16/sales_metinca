<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('internal_part_no');
        $table->string('article_no'); // Ini pengganti customer_part_no
        $table->string('part_name');
        
        // 1. TARUH DI SINI (KOLOM BARU)
        $table->string('index_no')->nullable(); 
        $table->double('berat')->nullable();
        
        $table->string('die_no')->nullable();
        $table->string('material')->nullable();
        $table->string('drawing_no')->nullable();
        $table->string('drawing_rev')->nullable();
        $table->date('effective_date');
        $table->foreignId('customer_id')->constrained('users');
        $table->string('lokasi_pengerjaan')->nullable();
        $table->text('remark')->nullable();
        
        // 2. TARUH PECAHAN HARGA DI SINI
        $table->decimal('casting_price', 15, 2)->default(0);
        $table->decimal('machining_price', 15, 2)->default(0);
        
        $table->decimal('price', 15, 2); // Kolom total price utama
        
        // 3. TARUH LAMPIRAN PDF DI SINI
        $table->string('pdf_attachment')->nullable();
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
