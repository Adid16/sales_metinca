<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Contract;
use App\Models\ContractRequirement;
use App\Models\PurchaseOrderInternal;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

echo "=== UPGRADING ALL CONTRACT REQUIREMENTS IN DATABASE ===\n";

$contracts = Contract::with(['purchaseOrderInternal', 'quotation.items'])->get();

foreach ($contracts as $c) {
    // Delete existing old requirements
    ContractRequirement::where('contract_id', $c->id)->delete();

    $poi = $c->purchaseOrderInternal;
    $partName = $c->part_name ?: ($poi?->item ?: 'Bracket Front Bumper Support');
    $partNo = $c->part_no ?: ($poi?->article ?: 'DWG-2026-0001');
    $price = $poi ? number_format($poi->unit_price, 0, ',', '.') : '75.000';
    $qty = $poi ? number_format($poi->qty, 0, ',', '.') : '4.500';
    
    $po = PurchaseOrder::where('po_no', explode('-', $c->order_no)[0] ?? '')
        ->orWhere('po_no', $c->order_no)
        ->first();
        
    $deliv = ($po && $po->delivery_request) ? Carbon::parse($po->delivery_request)->format('d M Y') : '05 Sep 2026';
    $material = $poi?->material ?: 'SPCC-SD t=2.0mm / JIS G3141';
    $spec = $poi?->spesifikasi ?: "Drawing: {$partNo} | Standar Presisi Otomotif";
    
    $shortCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $partName), 0, 6));
    $dwgCode = 'DWG-2026-' . str_pad($c->id, 4, '0', STR_PAD_LEFT);
    $mjcCode = 'MJC-' . $shortCode . '-' . str_pad($c->id, 2, '0', STR_PAD_LEFT) . ' Rev. 2';
    $wiCode = 'WI-ST-' . (45000 + $c->id * 10);
    $dieCode = 'DIE-MIPW-' . $shortCode . '-' . str_pad($c->id, 2, '0', STR_PAD_LEFT);
    $cfCode = 'CF-' . (45000 + $c->id * 10);

    $reqs = [
        // SALES
        ['sales', 'Price', 'Rp ' . $price],
        ['sales', 'Quantity', $qty . ' pcs'],
        ['sales', 'Delivery Required', $deliv],
        ['sales', 'Supply Condition', 'PT. Metinca Prima Industrial Works (Franco Warehouse)'],
        ['sales', 'Special / Customer Requirement', 'Wajib melampirkan Certificate of Analysis (CoA) material dan Mill Sheet pada setiap pengiriman.'],

        // QUALITY
        ['quality', 'Drawing', $dwgCode],
        ['quality', 'Standard / Spec', "Drawing: {$dwgCode} | Material: {$material} | Berat: 1.45 Kg"],
        ['quality', 'Inspection', "Critical Dimension Check 100% pada diameter lubang bracket dan sudut tekukan menggunakan Go/No-Go Gauge."],

        // PPC
        ['ppc', 'Material Requirement', "{$material} (Ready stock di warehouse raw material)"],
        ['ppc', 'Pattern Wax', 'N/A (Proses Stamping - Tidak menggunakan cetakan lilin / investment casting)'],
        ['ppc', 'Purchasing', 'Bahan baku di-supply dari steel center resmi (PT. Hanwa Steel Service Center Indonesia).'],
        ['ppc', 'Sub Contracting', 'Proses Finishing ED Coating (Cat anti-karat) di-outsource ke vendor sub-kon tier-1 terverifikasi.'],

        // DESIGN ENGINEERING
        ['design engineering', 'Master Job Card', "{$mjcCode} sudah terdaftar dan rilis aktif di sistem ERP produksi."],
        ['design engineering', 'WRA / WI', "Lembar Instruksi Kerja {$wiCode} untuk operator Mesin Press & Casting sudah tersedia di line produksi."],
        ['design engineering', 'Dies', "Progressive Dies No. {$dieCode} status OK (Selesai preventif maintenance & trial stroke)."],
        ['design engineering', 'Tool', 'Standard punching toolset diameter 8mm dan 12mm siap di workstation.'],
        ['design engineering', 'Fixtures', "Checking Fixture {$cfCode} sudah dikalibrasi ulang oleh tim QA sebelum naik produksi."],
    ];

    foreach ($reqs as $r) {
        ContractRequirement::create([
            'contract_id' => $c->id,
            'requirement_from' => $r[0],
            'requirement' => $r[1],
            'requirement_value' => $r[2],
        ]);
    }
    
    // Also ensure po_pdf is set
    if (empty($c->po_pdf)) {
        $c->update(['po_pdf' => 'Dokumen_PO_PO20262208-2.pdf', 'others_comment' => '-']);
    }
    
    echo "[OK] Contract {$c->contract_no} ({$c->part_name}) upgraded with 17 realistic requirements.\n";
}

echo "ALL CONTRACT REQUIREMENTS SUCCESSFULLY UPGRADED!\n";
