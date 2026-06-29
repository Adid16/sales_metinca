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
    Schema::table('contracts', function (Blueprint $table) {
        // Diubah posisinya setelah order_no (Nomor PO)
        $table->string('alasan_amandemen')->nullable()->after('order_no');
    });
}

public function down(): void
{
    Schema::table('contracts', function (Blueprint $table) {
        $table->dropColumn('alasan_amandemen');
    });
}
};
