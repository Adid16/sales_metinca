<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$contracts = Contract::where('amandement_no', '>', 0)->where('status', '!=', 'done')->get();
foreach ($contracts as $c) {
    echo "Resetting approvals on amended Contract #{$c->contract_no} (ID: {$c->id}, Status: {$c->status}, Amandement No: {$c->amandement_no})...\n";
    $c->update([
        'sales_approver'             => null,
        'sales_approved_at'          => null,
        'manager_sales_signature'    => null,
        'sales_reject_reason'        => null,
        'sales_rejected_at'          => null,

        'quality_approver'           => null,
        'quality_approved_at'        => null,
        'manager_quality_signature'  => null,
        'quality_reject_reason'      => null,
        'quality_rejected_at'        => null,

        'ppc_approver'               => null,
        'ppc_approved_at'            => null,
        'manager_ppc_signature'      => null,
        'ppc_reject_reason'          => null,
        'ppc_rejected_at'            => null,

        'dev_engineering_approver'   => null,
        'dev_engineering_approved_at'=> null,
        'manager_de_signature'       => null,
        'dev_engineering_reject_reason' => null,
        'dev_engineering_rejected_at'=> null,
    ]);
    echo "[OK] Approvals reset for Contract #{$c->contract_no}.\n";
}
