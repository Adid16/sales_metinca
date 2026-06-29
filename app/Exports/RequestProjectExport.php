<?php

namespace App\Exports;

use App\Models\RequestProject;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class RequestProjectExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = RequestProject::with(['sales','customer'])->orderBy('created_at', 'desc');

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('created_at', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('created_at', '<=', $this->filters['end_date']);
        }

        // if (!empty($this->filters['sales_id'])) {
        //     $query->where('sales_id', $this->filters['sales_id']);
        // }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Company',
            'Email',
            'Subject',
            'Sales',
            'Created At',
        ];
    }

    public function map($project): array
    {
        return [
            $project->id,
            $project->name ?? '',
            $project->company ?? '',
            $project->email ?? '',
            $project->subject ?? '',
            $project->sales->name ?? '',
            optional($project->created_at)->format('Y-m-d H:i:s') ?? '',
        ];
    }
}
