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
        // 1. Alter articles table
        Schema::table('articles', function (Blueprint $table) {
            if (!Schema::hasColumn('articles', 'price_list')) {
                $table->decimal('price_list', 15, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('articles', 'floor_price')) {
                $table->decimal('floor_price', 15, 2)->nullable()->after('price_list');
            }
            if (!Schema::hasColumn('articles', 'bottom_price')) {
                $table->decimal('bottom_price', 15, 2)->nullable()->after('floor_price');
            }
        });

        // Populate price_list from price for existing articles
        DB::statement("UPDATE articles SET price_list = price WHERE price_list IS NULL AND price IS NOT NULL");

        // 2. Alter quotation_items table
        Schema::table('quotation_items', function (Blueprint $table) {
            if (!Schema::hasColumn('quotation_items', 'article_id')) {
                $table->unsignedBigInteger('article_id')->nullable()->after('quotation_id');
            }
            if (!Schema::hasColumn('quotation_items', 'original_price')) {
                $table->decimal('original_price', 15, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('quotation_items', 'negotiated_price')) {
                $table->decimal('negotiated_price', 15, 2)->nullable()->after('original_price');
            }
            if (!Schema::hasColumn('quotation_items', 'floor_price')) {
                $table->decimal('floor_price', 15, 2)->nullable()->after('negotiated_price');
            }
            if (!Schema::hasColumn('quotation_items', 'is_below_floor_price')) {
                $table->boolean('is_below_floor_price')->default(false)->after('floor_price');
            }
        });

        // Populate original_price for existing items
        DB::statement("UPDATE quotation_items SET original_price = price WHERE original_price IS NULL AND price IS NOT NULL");

        // 3. Alter quotations table
        Schema::table('quotations', function (Blueprint $table) {
            // Ubah column status menjadi VARCHAR agar fleksibel untuk waiting_manager_approval
            $table->string('status', 50)->default('created')->change();

            if (!Schema::hasColumn('quotations', 'is_below_floor_price')) {
                $table->boolean('is_below_floor_price')->default(false)->after('status');
            }
            if (!Schema::hasColumn('quotations', 'manager_approval_status')) {
                $table->string('manager_approval_status', 30)->default('none')->after('is_below_floor_price');
            }
            if (!Schema::hasColumn('quotations', 'approved_by_manager_id')) {
                $table->unsignedBigInteger('approved_by_manager_id')->nullable()->after('manager_approval_status');
            }
            if (!Schema::hasColumn('quotations', 'manager_approval_note')) {
                $table->text('manager_approval_note')->nullable()->after('approved_by_manager_id');
            }
            if (!Schema::hasColumn('quotations', 'manager_approved_at')) {
                $table->timestamp('manager_approved_at')->nullable()->after('manager_approval_note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn([
                'is_below_floor_price',
                'manager_approval_status',
                'approved_by_manager_id',
                'manager_approval_note',
                'manager_approved_at'
            ]);
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn([
                'article_id',
                'original_price',
                'negotiated_price',
                'floor_price',
                'is_below_floor_price'
            ]);
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'price_list',
                'floor_price',
                'bottom_price'
            ]);
        });
    }
};
