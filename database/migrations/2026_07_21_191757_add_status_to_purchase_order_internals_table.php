<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_order_internals', function (Blueprint $table) {
            // Tambahkan kolom status (sesuaikan posisi 'after' jika perlu)
            $table->string('status')->default('draft')->after('id'); 
        });
    }

    public function down(): void
    {
        Schema::table('purchase_order_internals', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
