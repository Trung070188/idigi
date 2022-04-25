<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppController;
use App\Models\Schedule;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class SchedulesController extends AppController
{
    /**
     * @var ScheduleService
     */
    private $scheduleService;


    /**
     * SchedulesController constructor.
     * @param ScheduleService $scheduleService
     */
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        return view('admin.schedules.index');
    }

    public function data(Request $request)
    {
        $query = Schedule::query()->orderBy('id', 'desc');

        if ($request->keyword) {
            //$query->where('title', 'LIKE', '%' . $req->keyword. '%');
        }

        $query->createdIn($request->created);


        $entries = $query->paginate();

        return [
            'code' => 0,
            'data' => $entries->items(),
            'paginate' => [
                'currentPage' => $entries->currentPage(),
                'lastPage' => $entries->lastPage(),
            ]
        ];
    }
}
