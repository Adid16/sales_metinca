<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('contracts', function (Blueprint $table) {
        $table->longText('manager_sales_signature')->nullable();
        $table->longText('manager_quality_signature')->nullable();
        $table->longText('manager_ppc_signature')->nullable();
        $table->longText('manager_de_signature')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
        });
    }
};
