<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RequestProject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

$sales = User::where('role', 'staff')->where('divisi', 'sales')->first() ?? User::first();
Auth::login($sales);

$rp = RequestProject::with(['attachments', 'customer.account'])->find(22);
$view = view('requests-project.show', ['requestProject' => $rp]);
$html = $view->render();

if (strpos($html, 'Tech Titans') !== false) {
    echo "[PASS] Successfully rendered Company 'Tech Titans' for Request #22!\n";
} else {
    echo "[FAIL] Company not found in rendered HTML.\n";
}
