<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Login as Staff Sales / Manager
$salesUser = User::where('role', 'staff')->orWhere('role', 'admin')->first();
Auth::login($salesUser);

echo "=== TESTING APPROVAL AMANDEMENT ROUTE (/approval-amandement) ===\n";

try {
    $request = \Illuminate\Http\Request::create('/approval-amandement', 'GET');
    $controller = app(\App\Http\Controllers\PurchaseOrderController::class);
    $response = $controller->indexAmandement();
    
    if ($response instanceof \Illuminate\View\View) {
        $rendered = $response->render();
        echo "OK! Rendered " . strlen($rendered) . " bytes successfully.\n";
    } else {
        echo "Response type: " . get_class($response) . "\n";
    }
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
