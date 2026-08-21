<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quotation;
use App\Models\Negotiate;

echo "=== TESTING TWO-WAY NEGOTIATION CLOSING LOGIC ===\n";

$quotations = Quotation::with('negotiates')->whereHas('negotiates')->get();

echo "Quotations with negotiation history: " . $quotations->count() . "\n";

foreach ($quotations as $q) {
    $lastNego = $q->negotiates->sortByDesc('id')->first();
    $fromCustomer = $lastNego ? $lastNego->from_customer : false;
    
    echo "Quotation #{$q->quotation_no} (Status: {$q->status}):\n";
    echo "  - Last offer by: " . ($fromCustomer ? 'Customer' : 'Staff Sales') . "\n";
    echo "  - Who can close now?\n";
    echo "    * Customer can close? " . ((!$fromCustomer && $q->status !== 'accepted') ? 'YES' : 'NO') . "\n";
    echo "    * Sales can close?    " . (($fromCustomer && $q->status !== 'accepted') ? 'YES' : 'NO') . "\n";
}
