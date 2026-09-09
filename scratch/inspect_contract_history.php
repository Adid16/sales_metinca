<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$contract = App\Models\Contract::where('contract_no', 'CT-2026-009')->first();
if ($contract) {
    echo "Contract ID: " . $contract->id . " No: " . $contract->contract_no . " Order: " . $contract->order_no . "\n";
    echo "Customer: " . ($contract->customer->name ?? '-') . "\n";
    echo "Status: " . $contract->status . " Amandement No: " . $contract->amandement_no . " Alasan: " . $contract->alasan_amandemen . "\n";
    echo "Internal Item ID: " . $contract->purchase_order_internal_id . "\n";
    if ($contract->internalItem) {
        echo "  Internal Item: " . $contract->internalItem->item . " Qty: " . $contract->internalItem->qty . " Price: " . $contract->internalItem->unit_price . " Status: " . $contract->internalItem->status . "\n";
    }
    echo "Quotation ID: " . $contract->quotation_id . "\n";
    if ($contract->quotation) {
        echo "  Quotation No: " . $contract->quotation->quotation_no . " Status: " . $contract->quotation->status . " Total Items: " . $contract->quotation->items->count() . "\n";
        echo "  Request ID: " . $contract->quotation->request_id . "\n";
        if ($contract->quotation->request) {
            $r = $contract->quotation->request;
            echo "  Request Subject: " . $r->subject . " Customer: " . $r->name . " Email: " . $r->email . " Company: " . $r->company . " Created: " . $r->created_at . "\n";
            echo "  Request Message: " . $r->message . "\n";
            echo "  Sales PIC: " . ($r->assignment->sales->name ?? 'Unassigned') . "\n";
        }
        echo "  --- NEGOTIATIONS ---\n";
        foreach ($contract->quotation->negotiates as $n) {
            echo "    [Nego #{$n->id}] From: " . ($n->user->name ?? ($n->from_customer ? 'Customer' : 'Sales')) . " Total: {$n->negotiated_total} Action: {$n->action} Time: {$n->created_at}\n";
            echo "      Msg: {$n->negotiation_message}\n";
            if ($n->items) {
                echo "      Items Snapshot: " . json_encode($n->items) . "\n";
            }
        }
    }
    
    echo "--- ALL CONTRACTS FOR SAME PO ORDER NO (PO010926-99) ---\n";
    $contracts = App\Models\Contract::where('order_no', 'like', '%PO010926-99%')->get();
    foreach ($contracts as $ct) {
        echo "  CTR ID: {$ct->id} No: {$ct->contract_no} Order: {$ct->order_no} Rev#: {$ct->amandement_no} Status: {$ct->status} Alasan: {$ct->alasan_amandemen}\n";
    }

    echo "--- ALL ACTIVITIES FOR PO010926-99 ---\n";
    $acts = App\Models\HistoryActivity::where('activity', 'like', '%PO010926-99%')
        ->orWhere('activity', 'like', '%CT-2026-009%')
        ->orWhere('activity', 'like', '%QT-2026-09-0002%')
        ->orderBy('activity_time', 'asc')
        ->get();
    foreach ($acts as $a) {
        echo "  [{$a->activity_time}] by " . ($a->user->name ?? '-') . ": {$a->activity}\n";
    }
}
