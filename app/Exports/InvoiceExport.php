<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Excel;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceExport implements FromCollection, WithCustomStartCell, Responsable, WithMapping, WithColumnFormatting, WithHeadings, WithColumnWidths, WithStyles, WithDrawings
{
    use Exportable;

    private $filters;
    private $fileName = 'invoices.xlsx';
    private $writerType = Excel::XLSX;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        return Invoice::filter($this->filters)->get();
    }

    public function startCell(): string
    {
        return 'A10';
    }

    public function headings(): array
    {
        return [
            'Serie',
            'Correlativo',
            'Base',
            'IGV',
            'Total',
            'Usuario',
            'Fecha',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->serie,
            $invoice->correlative,
            $invoice->base,
            $invoice->igv,
            $invoice->total,
            $invoice->user->name,
            Date::dateTimeToExcel($invoice->created_at),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => 'dd/mm/yyyy',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 10,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 30,
            'G' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            10 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }

    public function drawings()
    {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Coders Free');
        $drawing->setDescription('Logo');
        $drawing->setPath(public_path('img/logos/logo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A3');

        return $drawing;
    }

}
