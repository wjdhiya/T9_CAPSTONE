<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection;

class TriDharmaExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $data;
    protected $type;
    protected $headings;

    public function __construct(Collection $data, string $type, array $headings)
    {
        $this->data = $data;
        $this->type = $type;
        $this->headings = $headings;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function title(): string
    {
        return match ($this->type) {
            'penelitian' => 'Penelitian',
            'publikasi' => 'Publikasi',
            'pengmas' => 'Pengabdian Masyarakat',
            default => 'Data',
        };
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->data->count() + 1;
        $lastColumn = count($this->headings);
        $lastColumnLetter = $this->getColumnLetter($lastColumn);

        // Header style
        $sheet->getStyle("A1:{$lastColumnLetter}1")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => $this->getHeaderColor()],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // All cells border
        $sheet->getStyle("A1:{$lastColumnLetter}{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Data rows alignment
        if ($lastRow > 1) {
            $sheet->getStyle("A2:{$lastColumnLetter}{$lastRow}")->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true,
                ],
            ]);
        }

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }

    private function getHeaderColor(): string
    {
        return match ($this->type) {
            'penelitian' => 'A02127',  // Red
            'publikasi' => '10784B',   // Green
            'pengmas' => '003366',     // Blue
            default => '333333',
        };
    }

    private function getColumnLetter(int $columnNumber): string
    {
        $letter = '';
        while ($columnNumber > 0) {
            $columnNumber--;
            $letter = chr(65 + ($columnNumber % 26)) . $letter;
            $columnNumber = intval($columnNumber / 26);
        }
        return $letter;
    }
}
