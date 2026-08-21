<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== CHECKING / ADDING ALASAN_PENOLAKAN COLUMN TO CONTRACTS TABLE ===\n";

if (!Schema::hasColumn('contracts', 'alasan_penolakan')) {
    Schema::table('contracts', function (Blueprint $table) {
        $table->text('alasan_penolakan')->nullable()->after('alasan_amandemen');
    });
    echo "✓ Column 'alasan_penolakan' successfully added to 'contracts' table.\n";
} else {
    echo "✓ Column 'alasan_penolakan' already exists in 'contracts' table.\n";
}
