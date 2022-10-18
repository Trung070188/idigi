<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class DevicePlan implements FromView,WithTitle,WithColumnWidths
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        $data = $this->data;
        return view('exports.device_plan_export', compact('data'));
    }

    public function title(): string
    {
        return 'Device';
    }
    public function columnWidths(): array
    {
        return [
            'A'=>5,
            'B'=>35,
            'C'=>12,
            'D'=>30
        ];
    }
}
