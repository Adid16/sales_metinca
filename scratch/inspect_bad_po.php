<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

$pos = PurchaseOrder::with(['internals.contracts', 'contracts'])->whereIn('po_no', ['PO20262208', 'PO12345678', 'PO8892'])->get();

foreach ($pos as $po) {
    echo "=== PO: {$po->po_no} ===\n";
    echo "PO status: '{$po->status}', amandement_no: '{$po->amandement_no}'\n";
    echo "PO contracts count: " . $po->contracts->count() . "\n";
    foreach ($po->contracts as $c) {
        echo "  Contract #{$c->id} ({$c->contract_no}, order: {$c->order_no}): status = '{$c->status}', amandement_no = '{$c->amandement_no}'\n";
    }
    echo "PO internals count: " . $po->internals->count() . "\n";
    foreach ($po->internals as $i) {
        echo "  Internal #{$i->id} ({$i->po_no}, item: {$i->item}): status = '{$i->status}'\n";
        $c = Contract::where('purchase_order_internal_id', $i->id)->first();
        if ($c) {
            echo "    -> Contract #{$c->id} ({$c->contract_no}): status = '{$c->status}', amandement_no = '{$c->amandement_no}'\n";
        }
    }
}
