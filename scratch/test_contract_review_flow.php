<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Contract;

echo "=== TESTING CONTRACT REVIEW & 4 MANAGER APPROVAL LOGIC ===\n";

$contracts = Contract::all();
echo "Total Contracts: " . $contracts->count() . "\n";

foreach ($contracts as $c) {
    $sales = (bool) $c->sales_approver;
    $ppc   = (bool) $c->ppc_approver;
    $qual  = (bool) $c->quality_approver;
    $de    = (bool) $c->dev_engineering_approver;

    $all4Approved = $sales && $ppc && $qual && $de;

    echo "Contract #{$c->contract_no} (PO: {$c->order_no}): Status='{$c->status}'\n";
    echo "  - Sales: " . ($sales ? 'OK' : 'PENDING') . "\n";
    echo "  - Quality: " . ($qual ? 'OK' : 'PENDING') . "\n";
    echo "  - PPC: " . ($ppc ? 'OK' : 'PENDING') . "\n";
    echo "  - DE: " . ($de ? 'OK' : 'PENDING') . "\n";
    echo "  - All 4 Approved: " . ($all4Approved ? 'YES' : 'NO') . "\n";
    echo "  - Can Sales Finalize to DONE? " . ($all4Approved && $c->status !== 'done' ? 'YES' : 'NO') . "\n";
}
