<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\PurchaseOrder;
use App\Models\Contract;

$po = PurchaseOrder::where('po_no', 'PO011Adi1123')->first();
if ($po) {
    echo "=== PO {$po->po_no} (ID: {$po->id}) ===\n";
    echo "PO Status: {$po->status}\n";
    $contracts = Contract::where('order_no', 'like', $po->po_no . '%')->get();
    foreach ($contracts as $c) {
        echo "\nContract ID: {$c->id} | Contract No: {$c->contract_no} | Status: {$c->status} | Amandement No: {$c->amandement_no}\n";
        echo "Sales Approver: " . ($c->sales_approver ?? 'NULL') . " | Quality: " . ($c->quality_approver ?? 'NULL') . " | PPC: " . ($c->ppc_approver ?? 'NULL') . " | DE: " . ($c->dev_engineering_approver ?? 'NULL') . "\n";
        echo "Sales Reject Reason: " . ($c->sales_reject_reason ?? 'NULL') . "\n";
    }
} else {
    echo "PO PO011Adi1123 not found.\n";
}
