<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LessonPlanExport implements FromView,WithTitle,WithStyles,WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        $data = $this->data;
        return view('exports.plan_view', compact('data'));
    }

    public function title(): string
    {

        return $this->data['package_name'];
    }

    public function styles(Worksheet $sheet)
    {

    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 45,
            'C'=>20,
            'D'=>10,
            'E'=>6

        ];
    }
}
