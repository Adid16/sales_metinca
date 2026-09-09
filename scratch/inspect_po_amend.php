<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

$po = PurchaseOrder::with(['internals.contracts', 'contracts'])->where('po_no', 'PO-2026-ADIDWI-007')->first();
if ($po) {
    echo "=== PO-2026-ADIDWI-007 ===\n";
    echo "PO status: '{$po->status}'\n";
    echo "PO amandement_no: '{$po->amandement_no}'\n";
    echo "PO reason: '{$po->reason}'\n";
    foreach ($po->internals as $i) {
        echo "  Internal #{$i->id} ({$i->po_no}, item: {$i->item}) -> status: '{$i->status}'\n";
        $c = Contract::where('purchase_order_internal_id', $i->id)->first();
        if ($c) {
            echo "    Contract #{$c->id} ({$c->contract_no}) -> status: '{$c->status}', amandement_no: {$c->amandement_no}, sales_app: {$c->sales_approver}, q_app: {$c->quality_approver}\n";
        } else {
            echo "    No contract for internal item #{$i->id}\n";
        }
    }
}

$po2 = PurchaseOrder::with(['internals.contracts', 'contracts'])->where('po_no', 'PO-2026-SABILA-012')->first();
if ($po2) {
    echo "\n=== PO-2026-SABILA-012 ===\n";
    echo "PO status: '{$po2->status}'\n";
    echo "PO amandement_no: '{$po2->amandement_no}'\n";
    echo "PO reason: '{$po2->reason}'\n";
    foreach ($po2->internals as $i) {
        echo "  Internal #{$i->id} ({$i->po_no}, item: {$i->item}) -> status: '{$i->status}'\n";
        $c = Contract::where('purchase_order_internal_id', $i->id)->first();
        if ($c) {
            echo "    Contract #{$c->id} ({$c->contract_no}) -> status: '{$c->status}', amandement_no: {$c->amandement_no}, sales_app: {$c->sales_approver}, q_app: {$c->quality_approver}\n";
        } else {
            echo "    No contract for internal item #{$i->id}\n";
        }
    }
}
