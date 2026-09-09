<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Article;
use App\Models\RequestProject;
use App\Models\RequestProjectAssignment;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Negotiate;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderInternal;
use App\Models\Contract;
use App\Models\ContractRequirement;
use App\Models\HistoryActivity;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DummyTenTransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds for 10 realistic comprehensive transactions.
     */
    public function run(): void
    {
        // 1. DUMMY SIGNATURE (Simple Valid Base64 PNG for Digital Signatures)
        $dummySig = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJYAAAA8CAYAAACEF7s8AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAJ/SURBVHgB7dtBbhNBFIbhqXkC1qywYo0Uiwq';

        // 2. SETUP USERS (Managers, Staff Sales, Customers)
        $staffSales1 = User::firstOrCreate(['email' => 'ssales1@example.com'], [
            'name' => 'Staff Sales 1',
            'password' => Hash::make('ssales123'),
            'role' => 'staff',
            'divisi' => 'sales',
        ]);

        $staffSales2 = User::firstOrCreate(['email' => 'ssales2@example.com'], [
            'name' => 'Staff Sales 2',
            'password' => Hash::make('ssales2abc'),
            'role' => 'staff',
            'divisi' => 'sales',
        ]);

        $mgrSales = User::firstOrCreate(['email' => 'msales@example.com'], [
            'name' => 'Manager Sales',
            'password' => Hash::make('msales123'),
            'role' => 'manager',
            'divisi' => 'sales',
        ]);

        $mgrQuality = User::firstOrCreate(['email' => 'mquality@example.com'], [
            'name' => 'Manager Quality',
            'password' => Hash::make('mquality123'),
            'role' => 'manager',
            'divisi' => 'quality',
        ]);

        $mgrPpc = User::firstOrCreate(['email' => 'mppc@example.com'], [
            'name' => 'Manager PPC',
            'password' => Hash::make('mppc123'),
            'role' => 'manager',
            'divisi' => 'ppc',
        ]);

        $mgrDe = User::firstOrCreate(['email' => 'mde@example.com'], [
            'name' => 'Manager DE',
            'password' => Hash::make('mde123'),
            'role' => 'manager',
            'divisi' => 'design engineering',
        ]);

        // Customers
        $custAhm = User::firstOrCreate(['email' => 'ahm@customer.com'], [
            'name' => 'PT. Astra Honda Motor',
            'company' => 'PT. Astra Honda Motor',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $custToyota = User::firstOrCreate(['email' => 'toyota@customer.com'], [
            'name' => 'PT. Toyota Motor Manufacturing Indonesia',
            'company' => 'PT. Toyota Motor Manufacturing Indonesia',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $custKomatsu = User::firstOrCreate(['email' => 'komatsu@customer.com'], [
            'name' => 'PT. Komatsu Indonesia',
            'company' => 'PT. Komatsu Indonesia',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $custKubota = User::firstOrCreate(['email' => 'kubota@customer.com'], [
            'name' => 'PT. Kubota Indonesia',
            'company' => 'PT. Kubota Indonesia',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $custAdi = User::firstOrCreate(['email' => 'adidwinugroho168@gmail.com'], [
            'name' => 'Adi Dwi Nugroho',
            'company' => 'CV. Teknik Mesin Mandiri',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        $custSabila = User::firstOrCreate(['email' => 'sabila@example.com'], [
            'name' => 'Sabila Customer',
            'company' => 'PT. Sabila Motor Parts',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        // 3. MASTER ARTICLES
        $articles = [
            'art_brk' => Article::firstOrCreate(['article_no' => 'ART-BRK-01'], [
                'internal_part_no' => 'IPN-MIPW-001',
                'part_name' => 'Bracket Front Bumper Support',
                'index_no' => 'Rev 0',
                'berat' => 1.45,
                'die_no' => 'DIE-ST-101',
                'material' => 'SPCC-SD t=2.0mm',
                'drawing_no' => 'DWG-2026-0001',
                'drawing_rev' => '3',
                'effective_date' => '2026-01-01',
                'customer_id' => $custAhm->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 50000,
                'machining_price' => 25000,
                'price' => 75000,
                'floor_price' => 67500,
            ]),
            'art_hng' => Article::firstOrCreate(['article_no' => 'ART-HNG-02'], [
                'internal_part_no' => 'IPN-MIPW-002',
                'part_name' => 'Hinge Rear Door Lower LH',
                'index_no' => 'Rev 1',
                'berat' => 0.98,
                'die_no' => 'DIE-ST-204',
                'material' => 'S45C Spheroidized',
                'drawing_no' => 'DWG-2026-0052',
                'drawing_rev' => 'A',
                'effective_date' => '2026-01-10',
                'customer_id' => $custAhm->id,
                'lokasi_pengerjaan' => '2',
                'casting_price' => 70000,
                'machining_price' => 40000,
                'price' => 110000,
                'floor_price' => 99000,
            ]),
            'art_mnt' => Article::firstOrCreate(['article_no' => 'ART-MNT-03'], [
                'internal_part_no' => 'IPN-MIPW-003',
                'part_name' => 'Engine Mounting Bracket RH (FC250)',
                'index_no' => 'Rev 2',
                'berat' => 2.80,
                'die_no' => 'DIE-CST-301',
                'material' => 'FC250 Gray Cast Iron',
                'drawing_no' => 'DWG-TOY-2026-09',
                'drawing_rev' => 'B',
                'effective_date' => '2026-02-01',
                'customer_id' => $custToyota->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 90000,
                'machining_price' => 45000,
                'price' => 135000,
                'floor_price' => 121500,
            ]),
            'art_vlv' => Article::firstOrCreate(['article_no' => 'ART-VLV-04'], [
                'internal_part_no' => 'IPN-MIPW-004',
                'part_name' => 'Hydraulic Valve Body Block FCD500',
                'index_no' => 'Rev 0',
                'berat' => 6.20,
                'die_no' => 'DIE-KOM-401',
                'material' => 'FCD500 Ductile Iron',
                'drawing_no' => 'DWG-KOM-2026-44',
                'drawing_rev' => '1',
                'effective_date' => '2026-02-15',
                'customer_id' => $custKomatsu->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 300000,
                'machining_price' => 150000,
                'price' => 450000,
                'floor_price' => 405000,
            ]),
            'art_trl' => Article::firstOrCreate(['article_no' => 'ART-TRL-05'], [
                'internal_part_no' => 'IPN-MIPW-005',
                'part_name' => 'Track Roller Flange Heavy Duty',
                'index_no' => 'Rev 1',
                'berat' => 4.10,
                'die_no' => 'DIE-KOM-402',
                'material' => 'SCM440 Cast Alloy',
                'drawing_no' => 'DWG-KOM-2026-45',
                'drawing_rev' => '2',
                'effective_date' => '2026-02-15',
                'customer_id' => $custKomatsu->id,
                'lokasi_pengerjaan' => '2',
                'casting_price' => 180000,
                'machining_price' => 95000,
                'price' => 275000,
                'floor_price' => 247500,
            ]),
            'art_flw' => Article::firstOrCreate(['article_no' => 'ART-FLW-06'], [
                'internal_part_no' => 'IPN-MIPW-006',
                'part_name' => 'Flywheel Housing Diesel 4-Cylinder',
                'index_no' => 'Rev 0',
                'berat' => 5.50,
                'die_no' => 'DIE-KUB-501',
                'material' => 'FC200 Cast Iron',
                'drawing_no' => 'DWG-KUB-2026-12',
                'drawing_rev' => '0',
                'effective_date' => '2026-03-01',
                'customer_id' => $custKubota->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 220000,
                'machining_price' => 100000,
                'price' => 320000,
                'floor_price' => 288000,
            ]),
            'art_crk' => Article::firstOrCreate(['article_no' => 'ART-CRK-07'], [
                'internal_part_no' => 'IPN-MIPW-007',
                'part_name' => 'Crankcase Cover Left Die Casting',
                'index_no' => 'Rev 3',
                'berat' => 1.80,
                'die_no' => 'DIE-AHM-701',
                'material' => 'ADC12 Aluminum Alloy',
                'drawing_no' => 'DWG-AHM-2026-77',
                'drawing_rev' => 'C',
                'effective_date' => '2026-03-05',
                'customer_id' => $custAhm->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 60000,
                'machining_price' => 35000,
                'price' => 95000,
                'floor_price' => 85500,
            ]),
            'art_str' => Article::firstOrCreate(['article_no' => 'ART-STR-08'], [
                'internal_part_no' => 'IPN-MIPW-008',
                'part_name' => 'Steering Knuckle Arm FCD450',
                'index_no' => 'Rev 1',
                'berat' => 3.20,
                'die_no' => 'DIE-TOY-801',
                'material' => 'FCD450 Ductile Iron',
                'drawing_no' => 'DWG-TOY-2026-88',
                'drawing_rev' => '1',
                'effective_date' => '2026-03-10',
                'customer_id' => $custToyota->id,
                'lokasi_pengerjaan' => '2',
                'casting_price' => 140000,
                'machining_price' => 70000,
                'price' => 210000,
                'floor_price' => 189000,
            ]),
            'art_exh' => Article::firstOrCreate(['article_no' => 'ART-EXH-09'], [
                'internal_part_no' => 'IPN-MIPW-009',
                'part_name' => 'Exhaust Manifold Ductile Iron FCD600',
                'index_no' => 'Rev 0',
                'berat' => 2.60,
                'die_no' => 'DIE-MIP-901',
                'material' => 'FCD600 Heat Resistant Iron',
                'drawing_no' => 'DWG-MIP-2026-99',
                'drawing_rev' => '0',
                'effective_date' => '2026-03-15',
                'customer_id' => $custSabila->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 110000,
                'machining_price' => 55000,
                'price' => 165000,
                'floor_price' => 148500,
            ]),
            'art_spr' => Article::firstOrCreate(['article_no' => 'ART-SPR-10'], [
                'internal_part_no' => 'IPN-MIPW-010',
                'part_name' => 'Sprocket Hub Casting FC300',
                'index_no' => 'Rev 2',
                'berat' => 4.90,
                'die_no' => 'DIE-KOM-1001',
                'material' => 'FC300 Cast Iron',
                'drawing_no' => 'DWG-KOM-2026-101',
                'drawing_rev' => 'A',
                'effective_date' => '2026-03-20',
                'customer_id' => $custKomatsu->id,
                'lokasi_pengerjaan' => '1',
                'casting_price' => 250000,
                'machining_price' => 130000,
                'price' => 380000,
                'floor_price' => 342000,
            ]),
        ];

        // Helper closures for contract requirements
        $createReq = function ($contractId, $poi = null, $po = null, $article = null) {
            $priceVal = $poi ? number_format($poi->unit_price, 0, ',', '.') : '7.082';
            $qtyVal   = $poi ? number_format($poi->qty, 0, ',', '.') : '4.500';
            $delivVal = ($po && $po->delivery_request) ? Carbon::parse($po->delivery_request)->format('d M Y') : '05 Sep 2026';
            $partName = $poi ? $poi->item : ($article ? $article->part_name : 'Bracket Front Bumper Support');
            $partNo   = ($article && $article->internal_part_no) ? $article->internal_part_no : ($poi->article ?? 'DWG-2026-0001');
            $dwgNo    = 'DWG-' . ($partNo ?: '2026-0001');
            $material = $poi ? ($poi->material ?: ($article ? $article->material : 'SPCC-SD t=2.0mm')) : 'SPCC-SD t=2.0mm';
            $spec     = $poi ? ($poi->spesifikasi ?: 'Drawing: ' . $dwgNo . ' | Berat: 1.45 Kg') : 'Drawing: ' . $dwgNo . ' | Berat: 1.45 Kg';
            $shortCode= strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $partName), 0, 6));

            $reqs = [
                // SALES
                ['sales', 'Price', $priceVal],
                ['sales', 'Quantity', $qtyVal],
                ['sales', 'Delivery Required', $delivVal],
                ['sales', 'Supply Condition', 'PT. Metinca Prima Industrial Works'],
                ['sales', 'Special / Customer Requirement', 'Wajib melampirkan Certificate of Analysis (CoA) material dan Mill Sheet pada setiap pengiriman'],

                // QUALITY
                ['quality', 'Drawing', $dwgNo],
                ['quality', 'Standard / Spec', $spec],
                ['quality', 'Inspection', 'Critical Dimension Check 100% pada diameter lubang bracket dan sudut tekukan menggunakan Go/No-Go Gauge'],

                // PPC
                ['ppc', 'Material Requirement', $material],
                ['ppc', 'Pattern Wax', 'N/A (Proses Stamping - Tidak menggunakan cetakan lilin / investment casting)'],
                ['ppc', 'Purchasing', 'Bahan baku di-supply dari steel center resmi (PT. Hanwa Steel Service Center Indonesia)'],
                ['ppc', 'Sub Contracting', 'Proses Finishing ED Coating (Cat anti-karat) di-outsource ke vendor sub-kon tier-1 terverifikasi'],

                // DESIGN ENGINEERING
                ['design engineering', 'Master Job Card', 'MJC-' . $shortCode . '-01 Rev. 2 sudah terdaftar dan rilis aktif di sistem ERP produksi'],
                ['design engineering', 'WRA / WI', 'Lembar Instruksi Kerja WI-ST-45120 untuk operator Mesin Press 110 Ton sudah tersedia di line stamping'],
                ['design engineering', 'Dies', 'Progressive Dies No. DIE-MIPW-' . $shortCode . '-01 status OK (Selesai preventif maintenance & trial stroke)'],
                ['design engineering', 'Tool', 'Standard punching toolset diameter 8mm dan 12mm siap di workstation'],
                ['design engineering', 'Fixtures', 'Checking Fixture CF-45120 sudah dikalibrasi ulang oleh tim QA sebelum naik produksi'],
            ];
            foreach ($reqs as $r) {
                ContractRequirement::create([
                    'contract_id' => $contractId,
                    'requirement_from' => $r[0],
                    'requirement' => $r[1],
                    'requirement_value' => $r[2],
                ]);
            }
        };

        // =========================================================================
        // DATA #1: PO-2026-AHM-001 (Multi-Item - Status: PRODUCTION - 4 Manajer ACC)
        // =========================================================================
        $req1 = RequestProject::firstOrCreate(['name' => 'Pengadaan Komponen Bracket & Hinge AHM Batch 1'], [
            'customer_id' => $custAhm->id,
            'email' => $custAhm->email,
            'phone' => '0216518080',
            'company' => $custAhm->name,
            'subject' => 'quotation',
            'message' => 'Permintaan penawaran harga komponen casting presisi untuk lini perakitan 2026.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req1->id], ['sales_id' => $staffSales1->id]);

        $qt1 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-AHM-001'], [
            'customer_id' => $custAhm->id,
            'request_id' => $req1->id,
            'date_expired' => Carbon::now()->addDays(60),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(10),
        ]);
        $qi1_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt1->id, 'item' => $articles['art_brk']->part_name], [
            'article_id' => $articles['art_brk']->id,
            'qty' => 250,
            'price' => 75000,
            'original_price' => 75000,
            'floor_price' => 67500,
        ]);
        $qi1_2 = QuotationItem::firstOrCreate(['quotation_id' => $qt1->id, 'item' => $articles['art_hng']->part_name], [
            'article_id' => $articles['art_hng']->id,
            'qty' => 250,
            'price' => 110000,
            'original_price' => 110000,
            'floor_price' => 99000,
        ]);

        $po1 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-AHM-001'], [
            'customer_id' => $custAhm->id,
            'quotation_id' => $qt1->id,
            'delivery_request' => Carbon::now()->addDays(20),
            'status' => 'production',
        ]);
        $poi1_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po1->id, 'po_no' => 'PO-2026-AHM-001-1'], [
            'item' => $articles['art_brk']->part_name,
            'article' => $articles['art_brk']->article_no,
            'material' => $articles['art_brk']->material,
            'spesifikasi' => 'Casting SPCC-SD t=2.0mm',
            'qty' => 250,
            'unit_price' => 75000,
            'subtotal' => 18750000,
            'status' => 'production',
        ]);
        $poi1_2 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po1->id, 'po_no' => 'PO-2026-AHM-001-2'], [
            'item' => $articles['art_hng']->part_name,
            'article' => $articles['art_hng']->article_no,
            'material' => $articles['art_hng']->material,
            'spesifikasi' => 'S45C Spheroidized High Precision',
            'qty' => 250,
            'unit_price' => 110000,
            'subtotal' => 27500000,
            'status' => 'production',
        ]);

        $ctr1_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-AHM-001-1'], [
            'customer_id' => $custAhm->id,
            'quotation_id' => $qt1->id,
            'purchase_order_internal_id' => $poi1_1->id,
            'order_no' => $po1->po_no,
            'article_id' => $articles['art_brk']->id,
            'part_no' => $articles['art_brk']->internal_part_no,
            'part_name' => $articles['art_brk']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(5),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(5),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(5),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(5),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr1_1->id);

        $ctr1_2 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-AHM-001-2'], [
            'customer_id' => $custAhm->id,
            'quotation_id' => $qt1->id,
            'purchase_order_internal_id' => $poi1_2->id,
            'order_no' => $po1->po_no,
            'article_id' => $articles['art_hng']->id,
            'part_no' => $articles['art_hng']->internal_part_no,
            'part_name' => $articles['art_hng']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(5),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(5),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(5),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(5),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr1_2->id);

        // =========================================================================
        // DATA #2: PO-2026-TOYOTA-102 (Single-Item - Status: PRODUCTION - 4 Manajer ACC)
        // =========================================================================
        $req2 = RequestProject::firstOrCreate(['name' => 'Pengadaan Engine Mounting RH Toyota Yaris Cross'], [
            'customer_id' => $custToyota->id,
            'email' => $custToyota->email,
            'phone' => '0218983000',
            'company' => $custToyota->name,
            'subject' => 'quotation',
            'message' => 'Pesanan rutin casting engine mounting FC250 untuk kuartal ini.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req2->id], ['sales_id' => $staffSales2->id]);

        $qt2 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-TOY-102'], [
            'customer_id' => $custToyota->id,
            'request_id' => $req2->id,
            'date_expired' => Carbon::now()->addDays(45),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(8),
        ]);
        $qi2_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt2->id, 'item' => $articles['art_mnt']->part_name], [
            'article_id' => $articles['art_mnt']->id,
            'qty' => 500,
            'price' => 135000,
            'original_price' => 135000,
            'floor_price' => 121500,
        ]);

        $po2 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-TOYOTA-102'], [
            'customer_id' => $custToyota->id,
            'quotation_id' => $qt2->id,
            'delivery_request' => Carbon::now()->addDays(25),
            'status' => 'production',
        ]);
        $poi2_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po2->id, 'po_no' => 'PO-2026-TOYOTA-102-1'], [
            'item' => $articles['art_mnt']->part_name,
            'article' => $articles['art_mnt']->article_no,
            'material' => $articles['art_mnt']->material,
            'spesifikasi' => 'FC250 Grey Iron Casting Machined',
            'qty' => 500,
            'unit_price' => 135000,
            'subtotal' => 67500000,
            'status' => 'production',
        ]);

        $ctr2_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-TOY-102'], [
            'customer_id' => $custToyota->id,
            'quotation_id' => $qt2->id,
            'purchase_order_internal_id' => $poi2_1->id,
            'order_no' => $po2->po_no,
            'article_id' => $articles['art_mnt']->id,
            'part_no' => $articles['art_mnt']->internal_part_no,
            'part_name' => $articles['art_mnt']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(4),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(4),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(4),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(4),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr2_1->id);

        // =========================================================================
        // DATA #3: PO-2026-KOMATSU-045 (Multi-Item - Status: PRODUCTION - 4 Manajer ACC)
        // =========================================================================
        $req3 = RequestProject::firstOrCreate(['name' => 'Pengadaan Hydraulic Valve & Track Roller Excavator'], [
            'customer_id' => $custKomatsu->id,
            'email' => $custKomatsu->email,
            'phone' => '0214604290',
            'company' => $custKomatsu->name,
            'subject' => 'quotation',
            'message' => 'Material FCD500 dan SCM440 dengan uji ultrasonik dan NDT.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req3->id], ['sales_id' => $staffSales1->id]);

        $qt3 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-KOM-045'], [
            'customer_id' => $custKomatsu->id,
            'request_id' => $req3->id,
            'date_expired' => Carbon::now()->addDays(60),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(7),
        ]);
        $qi3_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt3->id, 'item' => $articles['art_vlv']->part_name], [
            'article_id' => $articles['art_vlv']->id,
            'qty' => 80,
            'price' => 450000,
            'original_price' => 450000,
            'floor_price' => 405000,
        ]);
        $qi3_2 = QuotationItem::firstOrCreate(['quotation_id' => $qt3->id, 'item' => $articles['art_trl']->part_name], [
            'article_id' => $articles['art_trl']->id,
            'qty' => 120,
            'price' => 275000,
            'original_price' => 275000,
            'floor_price' => 247500,
        ]);

        $po3 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-KOMATSU-045'], [
            'customer_id' => $custKomatsu->id,
            'quotation_id' => $qt3->id,
            'delivery_request' => Carbon::now()->addDays(30),
            'status' => 'production',
        ]);
        $poi3_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po3->id, 'po_no' => 'PO-2026-KOMATSU-045-1'], [
            'item' => $articles['art_vlv']->part_name,
            'article' => $articles['art_vlv']->article_no,
            'material' => $articles['art_vlv']->material,
            'spesifikasi' => 'FCD500 Ductile Iron NDT Test',
            'qty' => 80,
            'unit_price' => 450000,
            'subtotal' => 36000000,
            'status' => 'production',
        ]);
        $poi3_2 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po3->id, 'po_no' => 'PO-2026-KOMATSU-045-2'], [
            'item' => $articles['art_trl']->part_name,
            'article' => $articles['art_trl']->article_no,
            'material' => $articles['art_trl']->material,
            'spesifikasi' => 'SCM440 Heavy Duty Induction Hardened',
            'qty' => 120,
            'unit_price' => 275000,
            'subtotal' => 33000000,
            'status' => 'production',
        ]);

        $ctr3_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-KOM-045-1'], [
            'customer_id' => $custKomatsu->id,
            'quotation_id' => $qt3->id,
            'purchase_order_internal_id' => $poi3_1->id,
            'order_no' => $po3->po_no,
            'article_id' => $articles['art_vlv']->id,
            'part_no' => $articles['art_vlv']->internal_part_no,
            'part_name' => $articles['art_vlv']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(3),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(3),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(3),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(3),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr3_1->id);

        $ctr3_2 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-KOM-045-2'], [
            'customer_id' => $custKomatsu->id,
            'quotation_id' => $qt3->id,
            'purchase_order_internal_id' => $poi3_2->id,
            'order_no' => $po3->po_no,
            'article_id' => $articles['art_trl']->id,
            'part_no' => $articles['art_trl']->internal_part_no,
            'part_name' => $articles['art_trl']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(3),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(3),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(3),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(3),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr3_2->id);

        // =========================================================================
        // DATA #4: PO-2026-KUBOTA-088 (Single-Item - Status: PRODUCTION - 4 Manajer ACC)
        // =========================================================================
        $req4 = RequestProject::firstOrCreate(['name' => 'Flywheel Housing Mesin Traktor Pertanian Kubota'], [
            'customer_id' => $custKubota->id,
            'email' => $custKubota->email,
            'phone' => '0247605999',
            'company' => $custKubota->name,
            'subject' => 'quotation',
            'message' => 'Cor besi FC200 toleransi getaran rendah untuk traktor 4 silinder.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req4->id], ['sales_id' => $staffSales2->id]);

        $qt4 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-KUB-088'], [
            'customer_id' => $custKubota->id,
            'request_id' => $req4->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(6),
        ]);
        $qi4_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt4->id, 'item' => $articles['art_flw']->part_name], [
            'article_id' => $articles['art_flw']->id,
            'qty' => 150,
            'price' => 320000,
            'original_price' => 320000,
            'floor_price' => 288000,
        ]);

        $po4 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-KUBOTA-088'], [
            'customer_id' => $custKubota->id,
            'quotation_id' => $qt4->id,
            'delivery_request' => Carbon::now()->addDays(28),
            'status' => 'production',
        ]);
        $poi4_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po4->id, 'po_no' => 'PO-2026-KUBOTA-088-1'], [
            'item' => $articles['art_flw']->part_name,
            'article' => $articles['art_flw']->article_no,
            'material' => $articles['art_flw']->material,
            'spesifikasi' => 'FC200 Cast Iron Grey High Damping',
            'qty' => 150,
            'unit_price' => 320000,
            'subtotal' => 48000000,
            'status' => 'production',
        ]);

        $ctr4_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-KUB-088'], [
            'customer_id' => $custKubota->id,
            'quotation_id' => $qt4->id,
            'purchase_order_internal_id' => $poi4_1->id,
            'order_no' => $po4->po_no,
            'article_id' => $articles['art_flw']->id,
            'part_no' => $articles['art_flw']->internal_part_no,
            'part_name' => $articles['art_flw']->part_name,
            'status' => 'production',
            'sales_approver' => $mgrSales->id,
            'sales_approved_at' => Carbon::now()->subDays(2),
            'manager_sales_signature' => $dummySig,
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(2),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(2),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(2),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr4_1->id);

        // =========================================================================
        // DATA #5: PO-2026-AHM-002 (Multi-Item - Status: REVIEW - 3 dari 4 Manajer ACC)
        // =========================================================================
        $req5 = RequestProject::firstOrCreate(['name' => 'Crankcase Cover & Gearbox Shell AHM'], [
            'customer_id' => $custAhm->id,
            'email' => $custAhm->email,
            'phone' => '0216518080',
            'company' => $custAhm->name,
            'subject' => 'quotation',
            'message' => 'Kebutuhan die casting aluminium ADC12 untuk perakitan mesin matic.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req5->id], ['sales_id' => $staffSales1->id]);

        $qt5 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-AHM-002'], [
            'customer_id' => $custAhm->id,
            'request_id' => $req5->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(4),
        ]);
        $qi5_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt5->id, 'item' => $articles['art_crk']->part_name], [
            'article_id' => $articles['art_crk']->id,
            'qty' => 300,
            'price' => 95000,
            'original_price' => 95000,
            'floor_price' => 85500,
        ]);

        $po5 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-AHM-002'], [
            'customer_id' => $custAhm->id,
            'quotation_id' => $qt5->id,
            'delivery_request' => Carbon::now()->addDays(18),
            'status' => 'review',
        ]);
        $poi5_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po5->id, 'po_no' => 'PO-2026-AHM-002-1'], [
            'item' => $articles['art_crk']->part_name,
            'article' => $articles['art_crk']->article_no,
            'material' => $articles['art_crk']->material,
            'spesifikasi' => 'ADC12 Die Casting Leak Test 100%',
            'qty' => 300,
            'unit_price' => 95000,
            'subtotal' => 28500000,
            'status' => 'review',
        ]);

        $ctr5_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-AHM-002'], [
            'customer_id' => $custAhm->id,
            'quotation_id' => $qt5->id,
            'purchase_order_internal_id' => $poi5_1->id,
            'order_no' => $po5->po_no,
            'article_id' => $articles['art_crk']->id,
            'part_no' => $articles['art_crk']->internal_part_no,
            'part_name' => $articles['art_crk']->part_name,
            'status' => 'review',
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subDays(1),
            'manager_quality_signature' => $dummySig,
            'ppc_approver' => $mgrPpc->id,
            'ppc_approved_at' => Carbon::now()->subDays(1),
            'manager_ppc_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subDays(1),
            'manager_de_signature' => $dummySig,
            'sales_approver' => null, // Pending Manager Sales
        ]);
        $createReq($ctr5_1->id);

        // =========================================================================
        // DATA #6: PO-2026-TOYOTA-103 (Single-Item - Status: REVIEW - QC & DE Approved)
        // =========================================================================
        $req6 = RequestProject::firstOrCreate(['name' => 'Steering Knuckle Arm FCD450 Innova Zenix'], [
            'customer_id' => $custToyota->id,
            'email' => $custToyota->email,
            'phone' => '0218983000',
            'company' => $custToyota->name,
            'subject' => 'quotation',
            'message' => 'Spesifikasi keselamatan tinggi uji impak dan radiografi.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req6->id], ['sales_id' => $staffSales2->id]);

        $qt6 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-TOY-103'], [
            'customer_id' => $custToyota->id,
            'request_id' => $req6->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(3),
        ]);
        $qi6_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt6->id, 'item' => $articles['art_str']->part_name], [
            'article_id' => $articles['art_str']->id,
            'qty' => 200,
            'price' => 210000,
            'original_price' => 210000,
            'floor_price' => 189000,
        ]);

        $po6 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-TOYOTA-103'], [
            'customer_id' => $custToyota->id,
            'quotation_id' => $qt6->id,
            'delivery_request' => Carbon::now()->addDays(22),
            'status' => 'review',
        ]);
        $poi6_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po6->id, 'po_no' => 'PO-2026-TOYOTA-103-1'], [
            'item' => $articles['art_str']->part_name,
            'article' => $articles['art_str']->article_no,
            'material' => $articles['art_str']->material,
            'spesifikasi' => 'FCD450 Ductile Iron Impact Test',
            'qty' => 200,
            'unit_price' => 210000,
            'subtotal' => 42000000,
            'status' => 'review',
        ]);

        $ctr6_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-TOY-103'], [
            'customer_id' => $custToyota->id,
            'quotation_id' => $qt6->id,
            'purchase_order_internal_id' => $poi6_1->id,
            'order_no' => $po6->po_no,
            'article_id' => $articles['art_str']->id,
            'part_no' => $articles['art_str']->internal_part_no,
            'part_name' => $articles['art_str']->part_name,
            'status' => 'review',
            'quality_approver' => $mgrQuality->id,
            'quality_approved_at' => Carbon::now()->subHours(12),
            'manager_quality_signature' => $dummySig,
            'dev_engineering_approver' => $mgrDe->id,
            'dev_engineering_approved_at' => Carbon::now()->subHours(10),
            'manager_de_signature' => $dummySig,
        ]);
        $createReq($ctr6_1->id);

        // =========================================================================
        // DATA #7: PO-2026-ADIDWI-007 (Status: AMANDEMENT - Amandemen #1 Disetujui Sales)
        // =========================================================================
        $req7 = RequestProject::firstOrCreate(['name' => 'Pengadaan Cover Engine Side LH Custom Adi Dwi'], [
            'customer_id' => $custAdi->id,
            'email' => $custAdi->email,
            'phone' => '081298765432',
            'company' => 'CV. Teknik Mesin Mandiri',
            'subject' => 'quotation',
            'message' => 'Amandemen perubahan kuantitas dari 100 pcs ke 120 pcs.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req7->id], ['sales_id' => $staffSales1->id]);

        $qt7 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-ADI-007'], [
            'customer_id' => $custAdi->id,
            'request_id' => $req7->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(12),
        ]);
        $qi7_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt7->id, 'item' => 'Cover Engine Side LH'], [
            'qty' => 120,
            'price' => 45000,
            'original_price' => 45000,
            'floor_price' => 40000,
        ]);

        $po7 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-ADIDWI-007'], [
            'customer_id' => $custAdi->id,
            'quotation_id' => $qt7->id,
            'delivery_request' => Carbon::now()->addDays(14),
            'status' => 'amandement',
        ]);
        $poi7_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po7->id, 'po_no' => 'PO-2026-ADIDWI-007-1'], [
            'item' => 'Cover Engine Side LH',
            'article' => 'ffgh-iiuo-89',
            'material' => 'SPCC Cold Rolled Steel',
            'spesifikasi' => 'Revisi kuantitas disetujui (120 pcs)',
            'qty' => 120,
            'unit_price' => 45000,
            'subtotal' => 5400000,
            'status' => 'amandement',
        ]);

        $ctr7_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-ADI-007'], [
            'customer_id' => $custAdi->id,
            'quotation_id' => $qt7->id,
            'purchase_order_internal_id' => $poi7_1->id,
            'order_no' => $po7->po_no,
            'part_no' => 'mntsy-yeo-990',
            'part_name' => 'Cover Engine Side LH',
            'status' => 'amandement',
            'amandement_no' => 1,
            'alasan_amandemen' => 'Penambahan volume pesanan proyek menjadi 120 pcs.',
            'others_comment' => 'Amandemen disetujui oleh tim sales, lanjutkan proses internal.',
        ]);
        $createReq($ctr7_1->id);

        // =========================================================================
        // DATA #8: PO-2026-SABILA-012 (Single-Item - Status: AMANDEMENT_PENDING)
        // =========================================================================
        $req8 = RequestProject::firstOrCreate(['name' => 'Exhaust Manifold Ductile Iron Sabila'], [
            'customer_id' => $custSabila->id,
            'email' => $custSabila->email,
            'phone' => '081345678901',
            'company' => 'PT. Sabila Motor Parts',
            'subject' => 'quotation',
            'message' => 'Pengajuan amandemen berkas teknis dan jadwal delivery.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req8->id], ['sales_id' => $staffSales2->id]);

        $qt8 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-SAB-012'], [
            'customer_id' => $custSabila->id,
            'request_id' => $req8->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(5),
        ]);
        $qi8_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt8->id, 'item' => $articles['art_exh']->part_name], [
            'article_id' => $articles['art_exh']->id,
            'qty' => 100,
            'price' => 165000,
            'original_price' => 165000,
            'floor_price' => 148500,
        ]);

        $po8 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-SABILA-012'], [
            'customer_id' => $custSabila->id,
            'quotation_id' => $qt8->id,
            'delivery_request' => Carbon::now()->addDays(20),
            'status' => 'amandement_pending',
        ]);
        $poi8_1 = PurchaseOrderInternal::firstOrCreate(['purchase_order_id' => $po8->id, 'po_no' => 'PO-2026-SABILA-012-1'], [
            'item' => $articles['art_exh']->part_name,
            'article' => $articles['art_exh']->article_no,
            'material' => $articles['art_exh']->material,
            'spesifikasi' => 'FCD600 High Heat Exhaust',
            'qty' => 100,
            'unit_price' => 165000,
            'subtotal' => 16500000,
            'status' => 'amandement_pending',
        ]);

        $ctr8_1 = Contract::firstOrCreate(['contract_no' => 'CTR-2026-SAB-012'], [
            'customer_id' => $custSabila->id,
            'quotation_id' => $qt8->id,
            'purchase_order_internal_id' => $poi8_1->id,
            'order_no' => $po8->po_no,
            'article_id' => $articles['art_exh']->id,
            'part_no' => $articles['art_exh']->internal_part_no,
            'part_name' => $articles['art_exh']->part_name,
            'status' => 'amandement_pending',
            'amandement_no' => 1,
            'alasan_amandemen' => 'Mohon penyesuaian tanggal delivery dimajukan 5 hari karena jadwal perakitan dipercepat.',
        ]);
        $createReq($ctr8_1->id);

        // =========================================================================
        // DATA #9: PO-2026-KOMATSU-049 (Multi-Item - Status: SENT - PO Baru Terbit)
        // =========================================================================
        $req9 = RequestProject::firstOrCreate(['name' => 'Sprocket Hub & Pinion Housing Komatsu PC200'], [
            'customer_id' => $custKomatsu->id,
            'email' => $custKomatsu->email,
            'phone' => '0214604290',
            'company' => $custKomatsu->name,
            'subject' => 'quotation',
            'message' => 'PO baru diterbitkan setelah finalisasi penawaran harga.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req9->id], ['sales_id' => $staffSales1->id]);

        $qt9 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-KOM-049'], [
            'customer_id' => $custKomatsu->id,
            'request_id' => $req9->id,
            'date_expired' => Carbon::now()->addDays(45),
            'status' => 'po',
            'po_date' => Carbon::now()->subDays(1),
        ]);
        $qi9_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt9->id, 'item' => $articles['art_spr']->part_name], [
            'article_id' => $articles['art_spr']->id,
            'qty' => 60,
            'price' => 380000,
            'original_price' => 380000,
            'floor_price' => 342000,
        ]);
        $qi9_2 = QuotationItem::firstOrCreate(['quotation_id' => $qt9->id, 'item' => $articles['art_vlv']->part_name], [
            'article_id' => $articles['art_vlv']->id,
            'qty' => 40,
            'price' => 450000,
            'original_price' => 450000,
            'floor_price' => 405000,
        ]);

        $po9 = PurchaseOrder::firstOrCreate(['po_no' => 'PO-2026-KOMATSU-049'], [
            'customer_id' => $custKomatsu->id,
            'quotation_id' => $qt9->id,
            'delivery_request' => Carbon::now()->addDays(35),
            'status' => 'sent',
        ]);

        // =========================================================================
        // DATA #10: QT-2026-NEGO-010 (Status: NEGOTIATING - Aktif Tawar-Menawar Harga)
        // =========================================================================
        $req10 = RequestProject::firstOrCreate(['name' => 'Penawaran Cylinder Head & Manifold Kubota Diesel'], [
            'customer_id' => $custKubota->id,
            'email' => $custKubota->email,
            'phone' => '0247605999',
            'company' => $custKubota->name,
            'subject' => 'quotation',
            'message' => 'Negosiasi volume besar untuk kontrak tahunan 2026.',
        ]);
        RequestProjectAssignment::firstOrCreate(['request_project_id' => $req10->id], ['sales_id' => $staffSales2->id]);

        $qt10 = Quotation::firstOrCreate(['quotation_no' => 'QT-2026-NEGO-010'], [
            'customer_id' => $custKubota->id,
            'request_id' => $req10->id,
            'date_expired' => Carbon::now()->addDays(30),
            'status' => 'negotiating',
        ]);
        $qi10_1 = QuotationItem::firstOrCreate(['quotation_id' => $qt10->id, 'item' => 'Cylinder Head Cover Aluminum Casting'], [
            'qty' => 200,
            'price' => 120000,
            'original_price' => 120000,
            'floor_price' => 102000,
            'negotiated_price' => 110000,
        ]);

        Negotiate::firstOrCreate([
            'quotation_id' => $qt10->id,
            'user_id' => $custKubota->id,
            'message' => 'Mohon pertimbangkan harga Rp 105.000 per unit untuk volume order tahunan 200 pcs.',
        ], [
            'from_customer' => true,
            'action' => 'negotiate',
            'negotiated_total' => 21000000,
            'payment_terms' => '30 Days Net',
            'target_delivery_date' => Carbon::now()->addDays(25),
            'negotiated_items' => [
                [
                    'item_id' => $qi10_1->id,
                    'item_name' => 'Cylinder Head Cover Aluminum Casting',
                    'qty' => 200,
                    'price' => 105000,
                    'subtotal' => 21000000,
                ]
            ],
            'requires_manager_approval' => false,
        ]);

        Negotiate::firstOrCreate([
            'quotation_id' => $qt10->id,
            'user_id' => $staffSales2->id,
            'message' => 'Penawaran terbaik kami di angka Rp 110.000 per unit sudah termasuk ongkos deliver franco gudang.',
        ], [
            'from_customer' => false,
            'action' => 'negotiate',
            'negotiated_total' => 22000000,
            'payment_terms' => '30 Days Net',
            'target_delivery_date' => Carbon::now()->addDays(25),
            'negotiated_items' => [
                [
                    'item_id' => $qi10_1->id,
                    'item_name' => 'Cylinder Head Cover Aluminum Casting',
                    'qty' => 200,
                    'price' => 110000,
                    'subtotal' => 22000000,
                ]
            ],
            'requires_manager_approval' => false,
        ]);

        echo "\n=== 10 COMPREHENSIVE DUMMY TRANSACTIONS SEEDED SUCCESSFULLY ===\n";
    }
}
