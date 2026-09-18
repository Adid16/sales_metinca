<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;

$c = Contract::find(21);
if ($c) {
    echo "=== Details for Contract {$c->id} ({$c->contract_no}) ===\n";
    echo "Order No: {$c->order_no}\n";
    echo "Status: {$c->status}\n";
    echo "Rejected by Dept: {$c->rejected_by_dept}\n";
    echo "Alasan Penolakan: {$c->alasan_penolakan}\n";
    echo "Others Comment: {$c->others_comment}\n";
}
