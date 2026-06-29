<?php

namespace App\Exports;

use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class QuotationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Quotation::with(['customer'])->orderBy('created_at', 'desc');

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Quotation No',
            'Customer',
            'Date Expired',
            'Status',
            'Created At',
        ];
    }

    public function map($quotation): array
    {
        return [
            $quotation->quotation_no ?? '',
            $quotation->customer->company ?? '',
            $quotation->date_expired ?? '',
            $quotation->status ?? '',
            optional($quotation->created_at)->format('Y-m-d H:i:s') ?? '',
        ];
    }
}
