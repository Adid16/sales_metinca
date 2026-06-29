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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_no')->unique();
            $table->date('date_expired');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('material')->nullable();
            $table->integer('quantity_required_pcs')->nullable();
            $table->string('die_cavities')->nullable();
            $table->string('grade_type')->nullable();
            $table->string('form_of_supply')->nullable();
            $table->integer('qty_per_mould_pcs')->nullable();
            $table->string('pattern_wax')->nullable();
            $table->string('metal')->nullable();
            $table->string('soluble_wax')->nullable();
            $table->string('ceramic')->nullable();
            $table->string('runner_wax')->nullable();
            $table->integer('total_raw_material_cost')->nullable();
            $table->string('injection')->nullable();
            $table->string('cut_off')->nullable();
            $table->string('cleaning')->nullable();
            $table->string('scut_off')->nullable();
            $table->string('assembly')->nullable();
            $table->string('finishing')->nullable();
            $table->string('dipping')->nullable();
            $table->string('heat_treatment')->nullable();
            $table->string('dewaxing')->nullable();
            $table->string('straight')->nullable();
            $table->string('burnout')->nullable();
            $table->string('repair')->nullable();
            $table->string('melting')->nullable();
            $table->string('blasting')->nullable();
            $table->string('knockout')->nullable();
            $table->string('inspect')->nullable();
            $table->string('w_blast')->nullable();
            $table->integer('casting_weight')->nullable();
            $table->integer('total_minutes_per_mould')->nullable();
            $table->integer('total_minutes_mould')->nullable();
            $table->string('machining_add')->nullable();
            $table->string('x_ray')->nullable();
            $table->string('crack_det')->nullable();
            $table->string('polish')->nullable();
            $table->string('total_minutes_mould_add')->nullable();
            $table->string('total_add')->nullable();
            $table->string('wax')->nullable();
            $table->string('fixed_overheads_usd')->nullable();
            $table->string('straightening')->nullable();
            $table->string('scrap_usd')->nullable();
            $table->string('machining_fix')->nullable();
            $table->string('total_mould_cost')->nullable();
            $table->string('piece_price_usd')->nullable();
            $table->string('sub_contracting')->nullable();
            $table->string('director_comment_approval')->nullable();
            $table->enum('status',['created','sent','accepted','po'])->default('created');
            $table->text('notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
