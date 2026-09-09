<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;
use App\Models\ContractRequirement;

$contracts = Contract::with(['requirements', 'purchaseOrderInternal.purchaseOrder', 'quotation'])->get();
echo "Found " . $contracts->count() . " contracts in database.\n";
foreach ($contracts as $c) {
    echo "Contract: {$c->contract_no} (Order: {$c->order_no}, Part: {$c->part_name}) -> Requirements count: " . $c->requirements->count() . "\n";
}
