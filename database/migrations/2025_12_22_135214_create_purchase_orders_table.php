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
    Schema::create('purchase_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('customer_id')->constrained('users');
        $table->foreignId('quotation_id')->constrained('quotations');
        $table->string('po_no')->unique();
        $table->string('attachment')->nullable();
        $table->date('delivery_request');
        
        // ISI BAGIAN STATUS SEPERTI INI:
        $table->enum('status', [
            'sent', 
            'review', 
            'contract', 
            'amandement', 
            'production', 
            'ship'
        ])->default('sent');
        
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
