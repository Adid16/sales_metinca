<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = User::query();

        // If a specific role was requested
        if (!empty($this->filters['role'])) {
            $query->where('role', $this->filters['role']);
        } else {
            // default: exclude customers unless explicitly asked
            if (empty($this->filters['include_customers']) || ! $this->filters['include_customers']) {
                $query->where('role', '!=', 'customer');
            }
        }

        if (!empty($this->filters['name'])) {
            $query->where('name', 'like', "%{$this->filters['name']}%" );
        }

        if (!empty($this->filters['email'])) {
            $query->where('email', 'like', "%{$this->filters['email']}%" );
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Email',
            'Company',
            'Role',
            'Divisi',
            'Plant / Cabang',
            'Created At',
        ];
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->company,
            $user->role,
            $user->divisi,
            $user->plant ?? 'Jakarta',
            optional($user->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}
