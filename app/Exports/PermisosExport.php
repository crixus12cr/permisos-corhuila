<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PermisosExport implements FromCollection,WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        return ['Fecha solicitud', 'Fecha permiso', 'Hora inicio', 'Hora fin', 'Tipo compromiso', 'Observacion', 'Autorizacion jefe', 'Autorizacion Recursos Humanos', 'Usuario', 'N° documento', 'Cargo', 'Area', 'Rol'];
    }

    public function map($permisos): array
    {
        $autorizacion_boss = $permisos->autorization_boss ? 'Autorizado' : 'No Autorizado';
        $autorization_hr = $permisos->autorization_hr ? 'Autorizado' : 'No Autorizado';
        return [
            // $permisos->updated_at ?: '',
            $permisos->request_date ?: '',
            $permisos->date_permission ?: '',
            $permisos->time_start ?: '',
            $permisos->time_end ?: '',
            $permisos->commitment ?: '',
            $permisos->observations ?: '',
            $autorizacion_boss ?: '',
            $autorization_hr ?: '',
            $permisos->user->name.' '.$permisos->user->last_name ?: '',
            $permisos->user->document_number ?: '',
            $permisos->user->position->name ?: '',
            $permisos->user->area->name ?: '',
            $permisos->user->rol->name ?: '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => [
                    'bold' => true,
                    'name' => 'Arial',
                    'color' => [
                            'argb' => 'f8f8f8',
                        ],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => [
                    'argb' => 'ff5353',
                ]
            ]
        ]);

        $sheet->getStyle('A1:M' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin'
                ]
            ]
        ]);
    }
}
