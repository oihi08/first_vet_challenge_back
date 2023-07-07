<?php

namespace App\Services;

use Carbon\Carbon;

final class VetScheduleService
{
    /**
     * Return the time to the next quarter.
     *
     * @param Carbon $time the time to be rounded
     *
     * @return Carbon the rounded time
     */
    public function roundTimeToNextQuarter($time)
    {
        $minutes = $time->minute;
        $roundedMinutes = ceil($minutes / 15) * 15;
        $time->minute($roundedMinutes);
        $time->second(0);

        return $time;
    }

    /**
     * Return an array containing the breaks for given schedule.
     *
     * @param object $schedule the shedule to extract breaks
     *
     * @return array array of breaks with start and end time
     */
    public function extractBreaksFromSchedule($schedule)
    {
        $breakTimes = [];

        $properties = get_object_vars($schedule);
        $keys = array_keys($properties);
        $filteredKeys = array_values(array_filter($keys, function ($key) {
            return stripos($key, 'startbreak') !== false;
        }));
        foreach ($filteredKeys as $i => $item) {
            $startBreak = $schedule->$item;
            $dynamicKey = 'endBreak'.($i === 0 ? '' : (int) $i + 1);
            $endBreak = $schedule->{$dynamicKey};
            if ($startBreak !== '00:00:00') {
                $breakTimes[] = ['startBreak' => substr($startBreak, 0, -3), 'endBreak' => substr($endBreak, 0, -3)];
            }
        }

        return $breakTimes;
    }

    /**
     * Return true or false based on an slot of time is inside a break slot.
     *
     * @param string $startSlot   the start of time period
     * @param string $endSlot     the end of time period
     * @param string $currentDate date of the time period
     * @param array  $breaks      array of break slots
     *
     * @return bool true if the time slot is inside a break slot, otherwise false
     */
    public function isInBreak($startSlot, $endSlot, $currentDate, $breaks)
    {
        $isInBreak = false;
        foreach ($breaks as $item) {
            $timeSlotStart = $startSlot;
            $timeSlotEnd = $endSlot;
            $startbreakSlot = Carbon::createFromFormat('Y-m-d H:i', $currentDate.' '.$item['startBreak']);
            $endBreakSlot = Carbon::createFromFormat('Y-m-d H:i', $currentDate.' '.$item['endBreak']);

            $slotIsInBreak = ($timeSlotStart->gte($startbreakSlot) && $timeSlotStart->lt($endBreakSlot)) ||
            ($timeSlotEnd->gt($startbreakSlot) && $timeSlotEnd->lte($endBreakSlot)) ||
            ($timeSlotStart->lte($startbreakSlot) && $timeSlotEnd->gte($endBreakSlot));
            if ($slotIsInBreak) {
                return true;
            }
        }

        return $isInBreak;
    }

    /**
     * Return an array with available slots of times for schedule.
     * Return time periods divided into quarters (00,15,30,45).
     *
     * @param object $schedule current schedule to get available slots
     *
     * @return array array with available slots of time
     */
    public function getVetsAvailableIntervals($schedule)
    {
        $startString = substr($schedule->startDate.' '.$schedule->startTime, 0, -3);
        $endString = substr($schedule->endDate.' '.$schedule->endTime, 0, -3);
        $breakTimes = $this->extractBreaksFromSchedule($schedule);
        $start = Carbon::createFromFormat('Y-m-d H:i', $startString);
        $end = Carbon::createFromFormat('Y-m-d H:i', $endString)->subMinutes(15);

        $intervals = [];
        $current = $start->clone();
        while ($current <= $end) {
            $roundedTime = $this->roundTimeToNextQuarter($current);
            // $roundedTime = $current;
            $slotIsInBreak = $this->isInBreak(
                $roundedTime,
                $roundedTime->clone()->addMinutes(15),
                Carbon::createFromFormat('Y-m-d', $schedule->startDate)->toDateString(),
                $breakTimes
            );
            if ($slotIsInBreak == false) {
                $intervals[] =
                    [
                        'date' => $roundedTime->format('Y-m-d'),
                        'timeStart' => $roundedTime->format('H:i'),
                        'timeFinish' => $roundedTime->clone()->addMinutes(15)->format('H:i'),
                        'name' => $schedule->employeeName,
                        'id' => $schedule->employeeId,
                        'scheduleId' => $schedule->scheduleId,
                    ];
            }
            $current = $current->clone()->addMinutes(15);
        }

        return $intervals;
    }

    /**
     * Merge all available slots of time of all schedules.
     *
     * @param array $schedules all schedules of vets
     *
     * @return array array with available slots of time of all vets
     */
    public function mergeAllSchedules($schedules)
    {
        $availableSchedule = [];
        foreach ($schedules as $i => $schedule) {
            $intervals = $this->getVetsAvailableIntervals($schedule);
            $availableSchedule = array_merge($availableSchedule, $intervals);
        }

        return $availableSchedule;
    }
}
