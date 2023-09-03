<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PermisosExport implements FromCollection,WithHeadings, WithMapping
{
    use Exportable;

    protected $permisos;

    public function __construct($permisos)
    {
        $this->permisos = $permisos;
    }

    public function collection()
    {
        return $this->permisos;
    }

    public function headings(): array
    {
        return ['Nombres', 'Apellidos', 'Telefono', 'Usuario', 'Email', 'Tipo de Identificacion', 'Numero de Identificacion'];
    }

    public function map($customers): array
    {
        return [
            $customers->user->name,
            $customers->user->last_name,
            $customers->user->phone ?: '',
            $customers->user->username ?: '',
            $customers->user->email ?: '',
            $customers->user->document_type ? $customers->user->document_type->name : '',
            $customers->user->document_number ?: ''
        ];
    }
}
