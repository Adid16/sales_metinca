<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING PO INDEX VIEW AS DIFFERENT ROLES ===\n";

$roles = [
    'Staff Sales' => User::where('role', 'staff')->where('divisi', 'sales')->first(),
    'Admin'       => User::where('role', 'admin')->first(),
    'Customer'    => User::where('role', 'customer')->first(),
];

foreach ($roles as $roleName => $user) {
    if (!$user) {
        echo "Skipping $roleName (not found)\n";
        continue;
    }
    Auth::login($user);
    $pos = PurchaseOrder::with(['customer', 'quotation.items', 'internals.contract'])->orderBy('created_at', 'desc')->paginate(10);
    $filters = [];
    
    try {
        $view = view('purchase-orders.index', compact('pos', 'filters'))->render();
        echo "[PASS] Successfully rendered purchase-orders.index as $roleName (User ID: {$user->id})\n";
    } catch (\Throwable $e) {
        echo "[FAIL] Error rendering as $roleName: " . $e->getMessage() . " on line " . $e->getLine() . "\n";
    }
}
