<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_contracts_table.php

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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('quotation_id')->constrained('quotations');
            $table->string('order_no');
            $table->string('contract_no')->nullable();
            $table->integer('amandement_no')->default(0);
            $table->text('alasan_amandemen')->nullable();
            $table->text('others_comment')->nullable(); 
            $table->string('part_no')->nullable();
            $table->string('part_name')->nullable();
            $table->foreignId('article_id')->nullable()->constrained('articles');
            
            // Kolom status dengan opsi lengkap untuk tracking
            $table->enum('status', ['created', 'revision', 'amandement', 'production', 'shipment', 'done'])->default('created');
            
            $table->unsignedBigInteger('sales_approver')->nullable();
            $table->timestamp('sales_approved_at')->nullable();
            $table->unsignedBigInteger('quality_approver')->nullable();
            $table->timestamp('quality_approved_at')->nullable();
            $table->unsignedBigInteger('ppc_approver')->nullable();
            $table->timestamp('ppc_approved_at')->nullable();
            $table->unsignedBigInteger('dev_engineering_approver')->nullable();
            $table->timestamp('dev_engineering_approved_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};