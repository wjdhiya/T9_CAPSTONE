<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetTriDharmaExport implements WithMultipleSheets
{
    protected $sheets = [];

    public function addSheet(TriDharmaExport $sheet)
    {
        $this->sheets[] = $sheet;
        return $this;
    }

    public function sheets(): array
    {
        return $this->sheets;
    }
}
