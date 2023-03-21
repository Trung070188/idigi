<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class DeviceErrorExport implements FromView, WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $data = $this->data;
        return view('exports.device_error', compact('data'));
    }

    public function columnWidths(): array
    {
        return [
            'A' => 40,
            'B' => 15,
            'C' => 20,
            'D' => 40,

        ];
    }
}
