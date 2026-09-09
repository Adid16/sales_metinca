<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Quotation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$admin = User::where('role', 'admin')->first() ?? User::first();
Auth::login($admin);

$query = Quotation::with(['customer', 'items', 'request.assignment.sales', 'approvedByManager']);

$quotations = $query->orderByRaw("
    CASE 
        WHEN manager_approval_status = 'pending' OR status = 'waiting_manager_approval' THEN 1
        WHEN status IN ('negotiating', 'negotiation') THEN 2
        WHEN status IN ('created', 'draft') THEN 3
        WHEN status = 'sent' THEN 4
        WHEN status = 'accepted' THEN 5
        WHEN status = 'po' THEN 6
        WHEN status IN ('rejected', 'reject') THEN 7
        WHEN status = 'expired' THEN 8
        WHEN status IN ('cancel', 'canceled') THEN 9
        ELSE 10
    END ASC, updated_at DESC
")->get();

echo "=== NEW QUOTATION ORDER ===\n";
foreach ($quotations as $idx => $q) {
    echo ($idx + 1) . ". #{$q->quotation_no} | status: '{$q->status}' | mgr_app: '{$q->manager_approval_status}' | updated_at: {$q->updated_at}\n";
}
