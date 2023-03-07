<?php


namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SchoolErrorExport implements FromView
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

//    public function columnWidths(): array
//    {
//        return [
//            'A'=>20,
//            'B'=>25,
//            'C'=>25,
//            'D'=>11,
//            'E'=>30,
//            'F'=>6,
//            'G'=>40
//        ];
//    }
}
