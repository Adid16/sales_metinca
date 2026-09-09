<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$roles = [
    'staff' => User::where('role', 'staff')->first(),
    'admin' => User::where('role', 'admin')->first(),
    'manager' => User::where('role', 'manager')->first(),
    'customer' => User::where('role', 'customer')->first(),
];

$dashboardController = new \App\Http\Controllers\DashboardController();

foreach ($roles as $roleName => $user) {
    if (!$user) {
        echo "Skipping $roleName (no user found)\n";
        continue;
    }
    Auth::login($user);
    $view = $dashboardController->dashboard();
    $html = $view->render();
    echo "[PASS] Successfully rendered dashboard for role: $roleName (HTML length: " . strlen($html) . ")\n";
}
