<?php

namespace App\Exports;

use App\Models\Contract;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContractExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Return a collection of contracts to export
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Contract::with(['quotation', 'customer', 'requirements'])
            ->orderBy('created_at', 'desc');

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['dept'])) {
            $dept = $this->filters['dept'];
            $query->whereHas('requirements', function ($q) use ($dept) {
                $q->where('requirement_from', $dept);
            });
        }

        return $query->get();
    }

    /**
     * Headings for the spreadsheet
     */
    public function headings(): array
    {
        return [
            'Contract No',
            'Quotation No',
            'PO No',
            'Part No',
            'Part Name',
            'Requirement Count',
            'Sales Approver',
            'Quality Approver',
            'PPC Approver',
            'Design Engineering Approver',
            'Status',
            'Created At',
        ];
    }

    /**
     * Map a contract model to a spreadsheet row
     */
    public function map($contract): array
    {
        return [
            $contract->contract_no,
            $contract->quotation->quotation_no ?? '',
            $contract->order_no ?? '',
            $contract->part_no ?? '',
            $contract->part_name ?? '',
            $contract->requirements->count(),
            $contract->sales_approver ? 'Yes' : 'No',
            $contract->quality_approver ? 'Yes' : 'No',
            $contract->ppc_approver ? 'Yes' : 'No',
            $contract->dev_engineering_approver ? 'Yes' : 'No',
            ucfirst($contract->status ?? ''),
            optional($contract->created_at)->format('Y-m-d H:i:s') ?? '',
        ];
    }
}

