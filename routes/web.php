<?php

use App\Services\VetScheduleService;
use Illuminate\Support\Facades\Route;

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

        return response()->json($result, 200);
    });
}
);
