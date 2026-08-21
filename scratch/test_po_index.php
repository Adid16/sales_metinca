<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

Auth::login(User::first());

echo "=== TESTING PURCHASE ORDERS INDEX ROUTE ===\n";

try {
    $request = \Illuminate\Http\Request::create('/purchase-orders', 'GET');
    $controller = app(\App\Http\Controllers\PurchaseOrderController::class);
    $response = $controller->index($request);
    
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
