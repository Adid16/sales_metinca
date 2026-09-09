<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;

$user = User::where('role', 'staff')->where('divisi', 'sales')->first();
Auth::login($user);

$pendingContracts = Contract::with(['customer', 'quotation', 'purchaseOrderInternal.purchaseOrder.customer'])
    ->where('status', 'amandement_pending')
    ->latest('updated_at')
    ->get();

$html = view('purchase-orders.approval-amandement', compact('pendingContracts'))->render();
file_put_contents('scratch/rendered_approval.html', $html);
echo "Rendered successfully! Length: " . strlen($html) . "\n";
