<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\RequestProject;

$user = User::where('email', 'adidwinugroho168@gmail.com')->first();
if ($user) {
    echo "=== USER FOUND ===\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Company in user: '{$user->company}'\n";
    echo "Role: {$user->role}\n";
} else {
    echo "User not found by email.\n";
}

$rp = RequestProject::where('email', 'adidwinugroho168@gmail.com')->latest('id')->first();
if ($rp) {
    echo "\n=== REQUEST PROJECT ===\n";
    echo "ID: {$rp->id}\n";
    echo "Customer ID: {$rp->customer_id}\n";
    echo "Company in RP: '{$rp->company}'\n";
    if ($rp->customer) {
        echo "Customer Name: {$rp->customer->name}, Company: '{$rp->customer->company}'\n";
    }
}
