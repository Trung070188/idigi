<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;


class SchoolErrorExport implements FromView, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $data = $this->data;
        return view('exports.school_error', compact('data'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 20,
            'C' => 30,
            'D' => 11,
            'E' => 11,
            'F' => 6,
            'G' => 6,
            'H' => 20,
            'I' => 40
        ];
    }
}
