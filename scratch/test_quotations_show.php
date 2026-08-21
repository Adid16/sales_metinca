<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Auth::login(User::first());

echo "=== TESTING QUOTATIONS SHOW ROUTE ===\n";

$quotations = Quotation::all();
echo "Found " . $quotations->count() . " quotations.\n\n";

foreach ($quotations as $q) {
    try {
        echo "Testing Quotation ID: {$q->id} ({$q->quotation_no})... ";
        $quotation = $q;
        $quotation->load(['customer', 'items', 'purchaseOrder.internals.contract', 'request.attachments', 'negotiates']);
        $customerAccount = \App\Models\Account::where('user_id', $quotation->customer_id)->first();
        $negotiations = $quotation->negotiates;
        $po = $quotation->purchaseOrder ?? \App\Models\PurchaseOrder::with(['internals.contract'])->where('quotation_id', $quotation->id)->first();

        $view = view('quotations.show', compact('quotation', 'customerAccount', 'negotiations', 'po'))->render();
        echo "OK! (Rendered " . strlen($view) . " bytes)\n";
    } catch (\Throwable $e) {
        echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    }
}
