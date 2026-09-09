<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

// Contracts that are original and in production should have amandement_no = 0 if not amended
$originalContracts = Contract::whereIn('contract_no', ['CT-2026-001', 'CT-2026-002', 'CT-2026-003', 'CT-2026-004', 'CT-2026-005'])->get();
foreach ($originalContracts as $c) {
    if ($c->status === 'production' || $c->status === 'done') {
        $c->update(['amandement_no' => 0, 'alasan_amandemen' => null, 'status' => 'done']);
        echo "Reset {$c->contract_no} to original status (amandement_no = 0, status = done)\n";
    }
}
