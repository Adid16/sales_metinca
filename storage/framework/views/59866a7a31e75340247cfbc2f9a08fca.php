<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotation <?php echo e($quotation->quotation_no); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            background: #fff;
        }

        .page {
            padding: 30px 40px;
        }

        /* ─── HEADER ─── */
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-left { display: table-cell; vertical-align: middle; width: 60%; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; width: 40%; }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #0d3c88;
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        .company-sub {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
            letter-spacing: 0.5px;
        }

        .doc-title {
            font-size: 20px;
            font-weight: bold;
            color: #0d3c88;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .doc-no {
            font-size: 12px;
            color: #444;
            margin-top: 3px;
        }

        /* ─── STATUS BADGE ─── */
        .status-badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: white;
            background-color: #0dcaf0;
        }
        .status-draft    { background-color: #6c757d; }
        .status-sent     { background-color: #0dcaf0; color: #000; }
        .status-accepted { background-color: #198754; }
        .status-rejected { background-color: #dc3545; }

        /* ─── INFO SECTIONS ─── */
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 14px;
        }
        .info-box {
            display: table-cell;
            width: 49%;
            vertical-align: top;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
        }
        .info-box + .info-box { margin-left: 2%; }

        .info-box-header {
            background-color: #e9ecef;
            padding: 5px 10px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
        }
        .info-box-body { padding: 10px; }

        .info-line {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 45%;
            color: #666;
        }
        .info-sep   { display: table-cell; width: 5%; }
        .info-value { display: table-cell; width: 50%; font-weight: 600; }

        /* ─── TABLE ─── */
        .section-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #495057;
            background: #e9ecef;
            padding: 5px 10px;
            border: 1px solid #dee2e6;
            border-bottom: none;
            border-radius: 4px 4px 0 0;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #dee2e6;
            border-radius: 0 0 4px 4px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        table.items thead tr {
            background-color: #0d6efd;
            color: white;
        }
        table.items thead th {
            padding: 7px 10px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.items tbody tr:nth-child(even) { background-color: #f8f9fa; }
        table.items tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e9ecef;
            font-size: 11px;
        }
        table.items tfoot tr { background-color: #eef2ff; }
        table.items tfoot th {
            padding: 8px 10px;
            font-size: 11px;
            font-weight: bold;
            border-top: 2px solid #0d6efd;
        }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }

        /* ─── NOTES ─── */
        .notes-box {
            border: 1px solid #dee2e6;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 14px;
        }
        .notes-body {
            padding: 10px;
            font-size: 11px;
            color: #555;
            font-style: italic;
            min-height: 40px;
        }

        /* ─── SIGNATURE ─── */
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        .sig-box {
            display: table-cell;
            width: 30%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        .sig-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 50px;
        }
        .sig-name {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-size: 10px;
            font-weight: bold;
        }

        /* ─── FOOTER ─── */
        .footer {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #dee2e6;
            display: table;
            width: 100%;
            font-size: 9px;
            color: #999;
        }
        .footer-left  { display: table-cell; text-align: left; }
        .footer-right { display: table-cell; text-align: right; }

        /* ─── WATERMARK DRAFT ─── */
        <?php if($quotation->status == 'draft'): ?>
        .watermark {
            position: fixed;
            top: 40%;
            left: 15%;
            font-size: 80px;
            font-weight: bold;
            color: rgba(200,200,200,0.25);
            text-transform: uppercase;
            transform: rotate(-30deg);
            letter-spacing: 10px;
            z-index: 0;
        }
        <?php endif; ?>
    </style>
</head>
<body>
<div class="page">

    <?php if($quotation->status == 'draft'): ?>
        <div class="watermark">DRAFT</div>
    <?php endif; ?>

    
    <div class="header">
    <div class="header-left">
        <div style="display: table;">
            <div style="display: table-cell; vertical-align: middle; padding-right: 12px;">
                <img src="<?php echo e(public_path('assets/compiled/jpg/logometinca.jpg')); ?>" 
                     alt="Logo" 
                     style="height: 40px;">
            </div>
            <div style="display: table-cell; vertical-align: middle;">
                <div class="company-name">PT. Metinca Prima Industrial Works</div>
                <div class="company-sub">The #1 Precision Casting and Tooling Facility in Indonesia</div>
            </div>
        </div>
    </div>
    <div class="header-right">
        <div class="doc-title">Quotation</div>
        <div class="doc-no">No: <strong><?php echo e($quotation->quotation_no); ?></strong></div>
    </div>
</div>

    
<div class="info-row">
    <div class="info-box" style="width: 40%;">
        <div class="info-box-header">&#128196; Quotation Information</div>
        <div class="info-box-body">
            <div class="info-line">
                <div class="info-label">Quotation No</div>
                <div class="info-sep">:</div>
                <div class="info-value"><strong><?php echo e($quotation->quotation_no); ?></strong></div>
            </div>
            <div class="info-line">
                <div class="info-label">Request ID</div>
                <div class="info-sep">:</div>
                <div class="info-value"><strong><?php echo e($quotation->request_id ?? '-'); ?></strong></div>
            </div>
            <div class="info-line">
                <div class="info-label">Created Date</div>
                <div class="info-sep">:</div>
                <div class="info-value"><strong><?php echo e(\Carbon\Carbon::parse($quotation->created_at)->format('d F Y')); ?></strong></div>
            </div>
            <div class="info-line">
                <div class="info-label">Expired Date</div>
                <div class="info-sep">:</div>
                <div class="info-value"><strong><?php echo e(\Carbon\Carbon::parse($quotation->date_expired)->format('d F Y')); ?></strong></div>
            </div>
        </div>
    </div>
    <div class="info-box" style="width: 40%; margin-left: 2%;">
        <div class="info-box-header">&#128100; PIC Information</div>
        <div class="info-box-body">
            <div class="info-line">
                <div class="info-label">Nama</div>
                <div class="info-sep">:</div>
                <div class="info-value"><?php echo e($quotation->customer->name ?? '-'); ?></div>
            </div>
            <div class="info-line">
                <div class="info-label">Email</div>
                <div class="info-sep">:</div>
                <div class="info-value"><?php echo e($quotation->customer->email ?? '-'); ?></div>
            </div>
            <div class="info-line">
                <div class="info-label">Company</div>
                <div class="info-sep">:</div>
                <div class="info-value"><?php echo e($customerAccount->company ?? '-'); ?></div>
            </div>
            <div class="info-line">
                <div class="info-label">Phone</div>
                <div class="info-sep">:</div>
                <div class="info-value"><?php echo e($customerAccount->phone ?? '-'); ?></div>
            </div>
        </div>
    </div>
</div>

    
    <div class="section-title">&#128203; Item Quotation</div>
    <table class="items">
        <thead>
            <tr>
                <th class="text-center" width="5%">#</th>
                <th>Deskripsi Item</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="20%">Harga Satuan</th>
                <th class="text-right" width="20%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $quotation->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center"><?php echo e($index + 1); ?></td>
                    <td><?php echo e($item->item); ?></td>
                    <td class="text-center"><?php echo e($item->qty); ?></td>
                    <td class="text-right">Rp <?php echo e(number_format($item->price, 0, ',', '.')); ?></td>
                    <td class="text-right">Rp <?php echo e(number_format($item->qty * $item->price, 0, ',', '.')); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center" style="color:#999; font-style:italic; padding: 15px;">
                        Tidak ada item
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">TOTAL</th>
                <th class="text-right">
                    Rp <?php echo e(number_format($quotation->items->sum(fn($i) => $i->qty * $i->price), 0, ',', '.')); ?>

                </th>
            </tr>
        </tfoot>
    </table>

    
    <div class="notes-box">
        <div class="info-box-header">&#128221; Catatan</div>
        <div class="notes-body"><?php echo e($quotation->notes ?? 'Tidak ada catatan.'); ?></div>
    </div>

    
    <div class="signature-row">
        <div class="sig-box">
            <div class="sig-label">Dibuat Oleh,</div>
            <div class="sig-name">( _________________ )</div>
        </div>
        <div class="sig-box">
            <div class="sig-label">Diperiksa Oleh,</div>
            <div class="sig-name">( _________________ )</div>
        </div>
        <div class="sig-box">
            <div class="sig-label">Disetujui Oleh,</div>
            <div class="sig-name">( _________________ )</div>
        </div>
    </div>

    
    <div class="footer">
        <div class="footer-left">
            Dicetak pada: <?php echo e(now()->format('d F Y, H:i')); ?> WIB
        </div>
        <div class="footer-right">
            <?php echo e($quotation->quotation_no); ?> &bull; PT. Metinca Prima Industrial Works
        </div>
    </div>

</div>
</body>
</html><?php /**PATH C:\laragon\www\sales_metinca\resources\views/quotations/quotation-pdf.blade.php ENDPATH**/ ?>