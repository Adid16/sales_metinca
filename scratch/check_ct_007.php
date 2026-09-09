<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$c = Contract::where('contract_no', 'CT-2026-007')->orWhere('id', 7)->first();
if ($c) {
    echo "=== CONTRACT CT-2026-007 (ID: {$c->id}) ===\n";
    echo "Status: {$c->status}\n";
    echo "Amandement No: {$c->amandement_no}\n";
    echo "Sales Approver: " . ($c->sales_approver ?? 'NULL') . "\n";
    echo "Quality Approver: " . ($c->quality_approver ?? 'NULL') . "\n";
    echo "PPC Approver: " . ($c->ppc_approver ?? 'NULL') . "\n";
    echo "DE Approver: " . ($c->dev_engineering_approver ?? 'NULL') . "\n";
    echo "Sales Reject Reason: " . ($c->sales_reject_reason ?? 'NULL') . "\n";
    echo "Quality Reject Reason: " . ($c->quality_reject_reason ?? 'NULL') . "\n";
    echo "PPC Reject Reason: " . ($c->ppc_reject_reason ?? 'NULL') . "\n";
    echo "DE Reject Reason: " . ($c->dev_engineering_reject_reason ?? 'NULL') . "\n";
} else {
    echo "Contract not found.\n";
}
