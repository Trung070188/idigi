<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class DevicePlanExport implements FromView,WithTitle,WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $data = $this->data;
        return view('exports.device_plan', compact('data'));
    }

    public function title(): string
    {
        return 'Device';
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 5,
            'C'=>40,
            'D'=>55,
            'E'=>25,
            'F'=>70
        ];
    }
}
