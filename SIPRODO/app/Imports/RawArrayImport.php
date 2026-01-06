<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class RawArrayImport implements ToArray, WithCustomCsvSettings
{
    private string $delimiter;

    public function __construct(string $delimiter = ',')
    {
        $this->delimiter = $delimiter;
    }

    /**
     * @param array<int, array<int, mixed>> $array
     */
    public function array(array $array)
    {
        return $array;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => $this->delimiter,
        ];
    }
}
