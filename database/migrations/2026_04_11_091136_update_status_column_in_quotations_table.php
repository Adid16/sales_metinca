<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE quotations MODIFY COLUMN status ENUM('created','sent','accepted','po','negotiating') DEFAULT 'created'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            //
            DB::statement("ALTER TABLE quotations MODIFY COLUMN status ENUM('created','sent','accepted','po') DEFAULT 'created'");
        });
    }
};
