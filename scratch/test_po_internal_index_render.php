<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\PurchaseOrderInternal;
use Illuminate\Support\Facades\Auth;

$admin = User::where('role', 'admin')->first();
Auth::login($admin);

$items = PurchaseOrderInternal::with([
    'purchaseOrder.customer', 
    'purchaseOrder.quotation',
    'purchaseOrder.contract'
])->paginate(10);

$filters = [];
$view = view('purchase-orders-internal.index', compact('items', 'filters'));
$html = $view->render();

if (strpos($html, 'simple-datatables.js') === false && strpos($html, 'Menampilkan') !== false) {
    echo "[PASS] Successfully verified clean single pagination on PO Internal index view!\n";
} else {
    echo "[FAIL] Render check failed.\n";
}
