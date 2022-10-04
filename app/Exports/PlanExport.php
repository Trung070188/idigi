<?php


namespace App\Exports;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PlanExport implements WithMultipleSheets
{
    use Exportable;
    protected $lessons;
    protected $devices;

    public function __construct($lessons,$devices)
    {
        $this->lessons = $lessons;
        $this->devices=$devices;
    }

    public function sheets(): array
    {
        $lessons = $this->lessons;
        $devices=$this->devices;
        $sheets = [];
//        $schoolId = $this->request->get('school', "");
//        $schoolIds = explode(',', $schoolId);
//        $schools = CoreSchool::select('id', 'name')->whereIn('id', $schoolIds)->get();
//        if (count($schools)) {
//            foreach ($schools as $school) {
//                $sheets[] = new StatisticalLessonExport($this->request, $school);
//            }
//        }
        foreach ($lessons as $lesson)
        {
            $sheets[] = new LessonPlanExport($lesson);

        }
        $sheets[] = new DevicePlanExport($devices);

        return $sheets;
    }
}
