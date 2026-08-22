<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$pos = \App\Models\PurchaseOrder::with(['quotation.items', 'internals', 'contracts'])
    ->where('po_no', 'like', '%8892%')
    ->orWhere('id', 8892)
    ->get();

foreach ($pos as $po) {
    echo "PO ID: {$po->id}, PO NO: {$po->po_no}\n";
    echo "QUOTATION ITEMS:\n";
    foreach ($po->quotation->items ?? [] as $q) {
        echo "  - ID: {$q->id}, Item: {$q->item}, Qty: {$q->qty}, Price: {$q->price}\n";
    }
    echo "INTERNALS:\n";
    foreach ($po->internals ?? [] as $i) {
        echo "  - ID: {$i->id}, PO NO: {$i->po_no}, Item: {$i->item}, Qty: {$i->qty}, Price: {$i->unit_price}\n";
    }
    echo "CONTRACTS:\n";
    foreach ($po->contracts ?? [] as $c) {
        echo "  - ID: {$c->id}, Order No: {$c->order_no}, Part: {$c->part_name}, Status: {$c->status}, Internal ID: {$c->purchase_order_internal_id}\n";
    }
}
