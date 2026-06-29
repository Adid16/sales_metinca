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
        Schema::create('request_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('email');
            $table->enum('subject',['quotation','other project','meet and greet'])->default('quotation');
            $table->text('message')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_projects');
    }
};
