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
        Schema::table('purchase_order_internals', function (Blueprint $table) {
            //
            $table->renameColumn('satuan', 'article');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_internals', function (Blueprint $table) {
            //
            $table->renameColumn('article', 'satuan');
        });
    }
};
