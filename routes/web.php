<?php

use Illuminate\Support\Facades\Route;
use App\Services\VetScheduleService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::middleware(['cors'])->group(function () {
    Route::get('/schedule', function () {
        $VetScheduleService = new VetScheduleService();
        $result = $VetScheduleService->mergeAllSchedules(config('constants.schedule'));
        return response()->json($result, 200);});
    }
);
/* Route::get('/schedulesss', function () {
    $schedule = [
        [
            "scheduleId" => 4711,
            "startDate" => "2020-04-29",
            "startTime" => "10:00:00",
            "endDate" => "2020-04-29",
            "endTime" => "17:35:00",
            "startBreak" => "12:00:00",
            "endBreak" => "12:30:00",
            "startBreak2" => "16:00:00",
            "endBreak2" => "17:00:00",
            "startBreak3" => "00:00:00",
            "endBreak3" => "00:00:00",
            "startBreak4" => "00:00:00",
            "endBreak4" => "00:00:00",
            "employeeId" => 4712,
            "employeeName" => "John Doe"
        ],
        [
            "scheduleId" => 4713,
            "startDate" => "2020-04-29",
            "startTime" => "10:00:00",
            "endDate" => "2020-04-29",
            "endTime" => "16:35:00",
            "startBreak" => "10:30:00",
            "endBreak" => "12:30:00",
            "startBreak2" => "16:00:00",
            "endBreak2" => "16:15:00",
            "startBreak3" => "00:00:00",
            "endBreak3" => "00:00:00",
            "startBreak4" => "00:00:00",
            "endBreak4" => "00:00:00",
            "employeeId" => 4714,
            "employeeName" => "Jane Doe"
        ],
        [
            "scheduleId" => 4715,
            "startDate" => "2020-04-30",
            "startTime" => "18:00:00",
            "endDate" => "2020-04-30",
            "endTime" => "22:10:00",
            "startBreak" => "19:00:00",
            "endBreak" => "19:30:00",
            "startBreak2" => "00:00:00",
            "endBreak2" => "00:00:00",
            "startBreak3" => "00:00:00",
            "endBreak3" => "00:00:00",
            "startBreak4" => "00:00:00",
            "endBreak4" => "00:00:00",
            "employeeId" => 4714,
            "employeeName" => "Jane Doe"
        ]
    ];
    $data = [
        'message' => '¡Hola desde tu API!',
        'timestamp' => now(),
    ];
    return response()->json($data, 200);
}); */
