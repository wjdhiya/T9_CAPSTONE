<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class RawArrayImport implements ToArray
{
    /**
     * @param array<int, array<int, mixed>> $array
     */
    public function array(array $array)
    {
        return $array;
    }
}
