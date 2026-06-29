
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
            //
            $table->timestamp('sales_approved_at')->nullable()->after('sales_approver');
            $table->timestamp('ppc_approved_at')->nullable()->after('ppc_approver');
            $table->timestamp('quality_approved_at')->nullable()->after('quality_approver');
            $table->timestamp('dev_engineering_approved_at')->nullable()->after('dev_engineering_approver');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            //
            $table->dropColumn([
            'sales_approved_at',
            'ppc_approved_at',
            'quality_approved_at',
            'dev_engineering_approved_at',
            ]);
        });
    }
};
