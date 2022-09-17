<?php

namespace App\Imports;

use App\Models\Invoice;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithGroupedHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class InvoiceImport implements ToCollection, WithGroupedHeadingRow, WithCustomCsvSettings
{
    
    public function collection($rows)
    {
        foreach($rows as $row){
            Invoice::create([
                'serie' => $row['serie'],
                'base' => $row['base'],
                'igv' => $row['igv'],
                'total' => $row['total'],
                'user_id' => 1,
            ]);
        }
    }

    public function getCsvSettings(): array
    {
        return [
            'input_encoding' => 'UTF-8',
            'delimiter' => ','
        ];
    }

}
