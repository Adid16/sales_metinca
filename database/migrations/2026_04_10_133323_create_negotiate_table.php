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
        Schema::create('negotiate', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('from_customer')->default(false);
            $table->text('message');
            $table->decimal('negotiated_total', 15, 2)->nullable();
            $table->string('payment_terms')->nullable();
            $table->date('target_delivery_date')->nullable();
            $table->string('support_document')->nullable();
            $table->string('action')->default('negotiate'); // negotiate | accept
            $table->json('negotiated_items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negotiate');
    }
};
