<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$c = Contract::where('contract_no', 'CT-2026-002')->first();
if ($c) {
    echo "=== Requirements for {$c->contract_no} ===\n";
    foreach ($c->requirements as $r) {
        echo "[$r->requirement_from] $r->requirement => $r->requirement_value\n";
    }
}
