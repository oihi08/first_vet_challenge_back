<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VetController extends Controller
{
    //  const mergeAllSchedules = (schedule) => {
//     let items = [];
//     setVetsScheduleList([]);
//     setFilteredVetsScheduleList([]);

//     schedule.forEach((vetSchedule) => {
//       const intervals = getVetsAvailableIntervals(vetSchedule);
//       items = [...items, ...intervals];
//     });

//     setVetsScheduleList(items);
//     setFilteredVetsScheduleList(items);
//     onSortByDate(items, "asc-date");
//   };

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function mergeAllSchedules() {
        $availableSchedule = [];
        $schedules = config('constants.schedule');
        foreach ($schedules->schedule as $schedule) {
            $availableSchedule = array_merge($availableSchedule, ['John', 'Dinesh', 'Mahesh']);
        }

        /* $colorCode = $this->getColorCode('red');

        $post = Post::create([
            'name' => 'Silver',
            'stock' => 100,
            'bg_color' => $colorCode
        ]);

        dd($post); */
    }
}
