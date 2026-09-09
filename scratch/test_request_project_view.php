<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$controller = new \App\Http\Controllers\RequestProjectController();

$customer = User::where('role', 'customer')->first();
Auth::login($customer);
$req = new \Illuminate\Http\Request();
$view = $controller->index($req);
$html = $view->render();

echo "[PASS] Successfully rendered requests-project.index for customer (HTML length: " . strlen($html) . ")\n";
