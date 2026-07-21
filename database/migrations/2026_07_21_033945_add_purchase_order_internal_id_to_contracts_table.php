<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->foreignId('purchase_order_internal_id')
                ->nullable()
                ->after('quotation_id')
                ->constrained('purchase_order_internals')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_internal_id']);
            $table->dropColumn('purchase_order_internal_id');
        });
    }
};