<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\RequestProject;

// 1. Sync User company from Account table
$users = User::with('account')->get();
foreach ($users as $u) {
    if (empty($u->company) && $u->account && !empty($u->account->company)) {
        $u->update(['company' => $u->account->company]);
        echo "Updated User #{$u->id} ({$u->name}) company to '{$u->account->company}'\n";
    }
}

// 2. Sync RequestProject company from Customer or Account
$projects = RequestProject::with(['customer.account'])->get();
foreach ($projects as $rp) {
    if (empty($rp->company)) {
        $comp = $rp->customer->company ?? ($rp->customer->account->company ?? null);
        if ($comp) {
            $rp->update(['company' => $comp]);
            echo "Updated RequestProject #{$rp->id} company to '{$comp}'\n";
        }
    }
    if (empty($rp->phone)) {
        $ph = $rp->customer->phone ?? ($rp->customer->account->phone ?? null);
        if ($ph) {
            $rp->update(['phone' => $ph]);
            echo "Updated RequestProject #{$rp->id} phone to '{$ph}'\n";
        }
    }
}
