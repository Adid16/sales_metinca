<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LEMBAR TINJAUAN KONTRAK - {{ $contract->contract_number }}</title>
    <style>
        /* Reset dan base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.2;
            color: #000;
            padding: 10px;
        }

        /* Container utama */
        .container {
            width: 100%;
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto;
        }

        /* Header styles */
        .header {
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .division {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
           
        }

        .no-contract{
          font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        /* Contract info styles */
        .contract-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0px;
        }

        .contract-info td {
            padding: 1px 5px;
            vertical-align: top;
            margin-bottom: 0px;
        }

        .contract-info .label {
            font-weight: bold;
            white-space: nowrap;
        }

        .contract-info .colon {
            width: 10px;
            text-align: center;
            font-weight: bold;
            padding: 0 2px;
            white-space: nowrap;
        }

        /* Data record section */
        .data-record {
            margin-bottom: 15px;
        }

        .data-record span {
            font-weight: bold;
        }

        /* Main table styles */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .main-table th {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            background-color: #f0f0f0;
            font-size: 10px;
        }

        .main-table td {
            border: 1px solid #000;
            padding: 5px 4px;
            vertical-align: top;
            min-height: 25px;
        }

        /* Department column */
        .dept-col {
            width: 40px;
            text-align: center;
            font-weight: bold;
        }

        /* Requirement column */
        .req-col {
            width: 180px;
        }

        /* Check by column */
        .check-col {
            width: 80px;
        }

        /* Remark column */
        .remark-col {
            width: auto;
        }

        /* Department row grouping */
        .dept-row {
            font-weight: bold;
        }

        .dept-sales {
            background-color: #ffffff;
        }

        .dept-quality {
            background-color: #ffffff;
        }

        .dept-ppc {
            background-color: #ffffff;
        }

        .dept-design {
            background-color: #ffffff;
        }

        /* Others/Comment section */
        .others-section {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .others-title {
            font-weight: bold;
            margin-bottom: 5px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }

        .others-content {
            min-height: 50px;
            border: 1px solid #ddd;
            padding: 5px;
        }

        /* Footer styles */
        .footer {
            margin-top: 30px;
            font-size: 9px;
            color: #666;
            text-align: center;
        }

        /* Print-specific styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .container {
                max-width: 100%;
                padding: 10mm;
            }

            .no-print {
                display: none;
            }

            .main-table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        .italic {
            font-style: italic;
        }

        .underline {
            text-decoration: underline;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mb-2 {
            margin-bottom: 10px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="company-name text-center">PT. Metinca Prima Industrial Works</div>
            <div class="division text-center">FOUNDRY DIVISION</div>
            <div class="document-title">LEMBAR TINJAUAN KONTRAK</div>
            <div class="no-contract text-center">(NO : {{ $contract->contract_no ?? '3701068' }})</div>
        </div>

<!-- Contract Information -->
<table class="contract-info">
    <tr>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">CUSTOMER</td>
                    <td class="colon">:</td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->customer->name }} - {{ $contract->customer->company }}</td>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">DATA RECORD</td>
                    <td class="colon">:</td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->created_at ? \Carbon\Carbon::parse($contract->created_at)->format('d - F - Y') : '20 - January - 2025' }}</td>
    </tr>
    <tr>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">ORDER NO</td>
                    <td class="colon">:</td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->order_no ?? '6183500' }}</td>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">PART NO.</td>
                    <td class="colon">:</td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->part_no ?? 'S6-201.04 / 32.441.026 Index d' }}</td>
    </tr>
    <tr>
    <td width="15%">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="label">AMANDEMENT NO</td>
                <td class="colon">:</td>
            </tr>
        </table>
    </td>
    {{-- Perubahan variabel nama kolom di bawah ini --}}
    <td width="35%">
        {{ $contract->amandement_no ?? '0' }} 
        @if(!empty($contract->alasan_amandemen))
            <span style="font-weight: normal; font-style: italic;">({{ $contract->alasan_amandemen }})</span>
        @endif
    </td>
    <td width="15%">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td class="label">PART NAME</td>
                <td class="colon">:</td>
            </tr>
        </table>
    </td>
    <td width="35%">{{ $contract->part_name ?? 'Shaft seal casing' }}</td>
</tr>
    <tr>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label">LOCATION</td>
                    <td class="colon">:</td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->location ?? 'PT.Metinca (Jakarta)' }}</td>
        <td width="15%">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="label"></td>
                    <td class="colon"></td>
                </tr>
            </table>
        </td>
        <td width="35%">{{ $contract->article_no ?? '344.32.32' }}</td>
    </tr>
</table>

            {{-- @if (isset($article->article_number) && $article->article_number)
                <tr>
                    <td class="label">ARTICLE NO. :</td>
                    <td colspan="3">{{ $article->article_number }}</td>
                </tr>
            @endif --}}

        <!-- Main Table -->
        <table class="main-table">
            <thead>
                <tr>
                    <th class="dept-col">DEPT.</th>
                    <th class="req-col">REQUIREMENT</th>
                    <th class="check-col">CHECK BY</th>
                    <th class="check-col">DATE</th>
                    <th class="remark-col">ACTION REQUIRED / REMARK</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Group details by department code
                    $groupedDetails = [];
                    if (isset($contract->requirements) && $contract->requirements->count() > 0) {
                        foreach ($contract->requirements as $detail) {
                            $groupedDetails[$detail->requirement_from][] = $detail;
                        }
                    } else {
                        // Default data from the image (if no data provided)
                        $groupedDetails = [
                            'sales' => [
                                (object) [
                                    'requirement' => 'Order Validation',
                                    'check_by' => '',
                                    'remark' => '',
                                ],
                                (object) [
                                    'requirement' => 'Price',
                                    'check_by' => '',
                                    'remark' => 'Ok, sexual price list',
                                ],
                                (object) [
                                    'requirement' => 'Quantity',
                                    'check_by' => '',
                                    'remark' => '50 Pcs',
                                ],
                                (object) [
                                    'requirement' => 'Delivery Required',
                                    'check_by' => '',
                                    'remark' => '01/07/2025',
                                ],
                                (object) [
                                    'requirement' => 'Supply Condition',
                                    'check_by' => '',
                                    'remark' => '',
                                ],
                                (object) [
                                    'requirement' => 'Special / Other Customer Reg.Need',
                                    'check_by' => '',
                                    'remark' => 'Material: 1.4581',
                                ],
                            ],
                            'quality' => [
                                (object) [
                                    'requirement' => 'Drawing',
                                    'check_by' => '',
                                    'remark' => 'No. S6-201.04. Rev. d Tgl : 28/05/2008',
                                ],
                                (object) [
                                    'requirement' => 'Standard / Spec',
                                    'check_by' => '',
                                    'remark' => 'DIN EN 10213',
                                ],
                                (object) [
                                    'requirement' => 'Inspection',
                                    'check_by' => '',
                                    'remark' => 'Penyaliman dengan Cert. 2.3',
                                ],
                            ],
                            'ppc' => [
                                (object) [
                                    'requirement' => 'Material Requirement',
                                    'check_by' => '',
                                    'remark' =>
                                        '1.4581 = 2.6000 x 6 x 1.15 x 8.33333333333333 = 149.5 Kg.<br>Pattern Wax = 0.3500 x 6 x 1.15 x 8.33333333333333 = 20.125 Kg.',
                                ],
                                (object) [
                                    'requirement' => 'Purchasing',
                                    'check_by' => '',
                                    'remark' => 'Ceramic +<br>1.4581 = 149.5 Kg.<br>Pattern Wax = Stock Minimum.',
                                ],
                                (object) [
                                    'requirement' => 'Sub Contracting',
                                    'check_by' => '',
                                    'remark' => 'Ceramic +',
                                ],
                            ],
                            'design engineering' => [
                                (object) [
                                    'requirement' => 'Master Job Card',
                                    'check_by' => '',
                                    'remark' => 'Ada',
                                ],
                                (object) [
                                    'requirement' => 'WRA / WI',
                                    'check_by' => '',
                                    'remark' => 'Ada',
                                ],
                                (object) [
                                    'requirement' => 'Dies',
                                    'check_by' => '',
                                    'remark' => 'Ada',
                                ],
                                (object) [
                                    'requirement' => 'Tool',
                                    'check_by' => '',
                                    'remark' => 'Ada',
                                ],
                                (object) [
                                    'requirement' => 'Fixtures',
                                    'check_by' => '',
                                    'remark' => '',
                                ],
                            ],
                        ];
                    }

                    // Define department order and labels
                    $deptOrder = ['sales', 'quality', 'ppc', 'design engineering'];
                    $deptLabels = [
                        'sales' => 'S',
                        'quality' => 'Q',
                        'ppc' => 'P',
                        'design engineering' => 'DE',
                    ];
                    $deptClasses = [
                        'sales' => 'dept-sales',
                        'quality' => 'dept-quality',
                        'ppc' => 'dept-ppc',
                        'design engineering' => 'dept-design',
                    ];

                    $deptApprovers = [
                        'sales'              => [
                            'id'   => $contract->sales_approver,
                            'date' => $contract->sales_approved_at,
                        ],
                        'quality'            => [
                            'id'   => $contract->quality_approver,
                            'date' => $contract->quality_approved_at,
                        ],
                        'ppc'                => [
                            'id'   => $contract->ppc_approver,
                            'date' => $contract->ppc_approved_at,
                        ],
                        'design engineering' => [
                            'id'   => $contract->dev_engineering_approver,
                            'date' => $contract->dev_engineering_approved_at,
                        ],
                    ];

                    // Load nama approver dari database
                    $approverIds   = array_filter(array_column($deptApprovers, 'id'));
                    $approverUsers = \App\Models\User::whereIn('id', $approverIds)->pluck('name', 'id');
                @endphp

                @foreach ($deptOrder as $deptCode)
                    @if (isset($groupedDetails[$deptCode]) && count($groupedDetails[$deptCode]) > 0)
                        @foreach ($groupedDetails[$deptCode] as $index => $detail)
                            <tr class="{{ $deptClasses[$deptCode] ?? '' }}">
                                @if ($index === 0)
                                    <td class="dept-col" rowspan="{{ count($groupedDetails[$deptCode]) }}">
                                        {{ $deptLabels[$deptCode] ?? strtoupper(substr($deptCode, 0, 2)) }}
                                    </td>
                                @endif
                                <td class="req-col">{{ $detail->requirement ?? '' }}</td>
                                <td class="check-col text-center">
                                    @if($index === 0)
                                        {{ $approverUsers[$deptApprovers[$deptCode]['id']] ?? '' }}
                                    @endif
                                </td>
                                <td class="check-col text-center">
                                    @if($index === 0 && !empty($deptApprovers[$deptCode]['date']))
                                        {{ \Carbon\Carbon::parse($deptApprovers[$deptCode]['date'])->format('d/m/Y') }}
                                    @endif
                                    {{-- @if (isset($detail->checkBy) && $detail->checkBy)
                                        {{ $detail->checkBy->name }}
                                    @elseif(isset($detail->check_by) && $detail->check_by)
                                        {{ $detail->check_by }}
                                    @else
                                        &nbsp;
                                    @endif --}}
                                </td>
                                <td class="remark-col">{!! nl2br(e($detail->requirement_value ?? '')) !!}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Others/Comment Section -->
        <div class="others-section">
            <div class="others-title">OTHERS / COMMENT</div>
            <div class="others-content">
                @if (isset($contract->others_comment) && $contract->others_comment)
                    {!! nl2br(e($contract->others_comment)) !!}
                @else
                    &nbsp;<br>&nbsp;<br>&nbsp;
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Printed on {{ now()->format('d/m/Y H:i:s') }} | Lembar Tinjauan Kontrak
        </div>
    </div>

    <!-- Print button (only visible in browser) -->
    {{-- <div class="no-print" style="position: fixed; bottom: 20px; right: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print / Save as PDF
        </button>
    </div> --}}
</body>

</html>
