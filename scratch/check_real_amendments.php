<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

$allPos = PurchaseOrder::with(['internals.contracts', 'contracts'])->get();
echo "=== ALL PURCHASE ORDERS & THEIR AMENDMENT STATUS ===\n";
foreach ($allPos as $p) {
    $hasActiveAmend = in_array($p->status, ['amandement', 'amandement_pending'])
        || $p->internals->contains(fn($i) => in_array($i->status, ['amandement', 'amandement_pending']))
        || $p->contracts->contains(fn($c) => in_array($c->status, ['amandement', 'amandement_pending']))
        || $p->internals->flatMap(fn($i) => $i->contracts ?? collect())->contains(fn($c) => in_array($c->status, ['amandement', 'amandement_pending']));
        
    echo "PO #{$p->po_no} (Status: {$p->status}) => Is Active Amendment: " . ($hasActiveAmend ? "YES (ACTIVE AMENDMENT)" : "NO (PO BARU / PRODUCTION)") . "\n";
    foreach ($p->internals as $i) {
        $c = Contract::where('purchase_order_internal_id', $i->id)->first();
        echo "   - Item {$i->po_no}: item_status='{$i->status}'" . ($c ? ", contract_status='{$c->status}', amend_no={$c->amandement_no}" : "") . "\n";
    }
}
