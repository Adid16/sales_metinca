<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$contracts = Contract::with(['purchaseOrderInternal', 'purchaseOrder.customer', 'customer'])->where('status', 'amandement_pending')->get();
foreach ($contracts as $c) {
    echo "Contract ID: " . $c->id . "\n";
    echo "Order No: " . $c->order_no . "\n";
    echo "Part Name: " . $c->part_name . "\n";
    echo "Alasan Amandemen: " . ($c->alasan_amandemen ?? 'NULL') . "\n";
    echo "PO Internal ID: " . ($c->purchase_order_internal_id ?? 'NULL') . "\n";
    echo "PO ID: " . ($c->purchase_order_id ?? 'NULL') . "\n";
    echo "PO Internal Item: " . ($c->purchaseOrderInternal ? $c->purchaseOrderInternal->item : 'NULL') . "\n";
    echo "----------------------------------------\n";
}
