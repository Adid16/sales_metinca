<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseOrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = PurchaseOrder::with(['quotation', 'customer'])->orderBy('created_at', 'desc');

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
            'PO No',
            'Quotation No',
            'Customer',
            'Delivery Date',
            'Attachment',
            'Status',
            'Created At',
        ];
    }

    public function map($po): array
    {
        // Safely format delivery date (handle DateTime or string)
        $deliveryDate = '';
        if (!empty($po->delivery_request)) {
            try {
                $deliveryDate = $po->delivery_request instanceof \DateTimeInterface
                    ? $po->delivery_request->format('Y-m-d')
                    : Carbon::parse($po->delivery_request)->format('Y-m-d');
            } catch (\Exception $e) {
                // Fallback to raw value if parsing fails
                $deliveryDate = (string) $po->delivery_request;
            }
        }

        return [
            $po->po_no ?? '',
            $po->quotation->quotation_no ?? '',
            $po->customer->name ?? '',
            $deliveryDate,
            $po->attachment ?? '',
            $po->status ?? '',
            optional($po->created_at)->format('Y-m-d H:i:s') ?? '',
        ];
    }
}
