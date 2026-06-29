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
        Schema::table('purchase_order_items', function (Blueprint $table) {
            //
            $table->string('company')->nullable()->after('notes');
            $table->string('pic_buyer')->nullable()->after('company');
            $table->string('supplier')->nullable()->after('pic_buyer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            //
            $table->dropColumn(['company', 'pic_buyer', 'supplier']);
        });
    }
};
