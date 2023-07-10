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
    public function extractBreaksFromSchedule(object $schedule)
    {
        $propertykeys = array_keys(get_object_vars($schedule));
        $filteredKeys = array_values(array_filter($propertykeys, function ($key) {
            return stripos($key, 'startbreak') !== false;
        }));

        if (empty($filteredKeys)) {
            return []; // No breaks found, return an empty array
        }

        $breakTimes = [];

        foreach ($filteredKeys as $index => $item) {
            $startBreak = $schedule->$item;
            $dynamicKey = 'endBreak'.($index === 0 ? '' : (int) $index + 1);
            $endBreak = $schedule->{$dynamicKey};

            if ($startBreak !== '00:00:00') {
                $breakTimes[] = [
                    'startBreak' => substr($startBreak, 0, -3),
                    'endBreak' => substr($endBreak, 0, -3),
                ];
            }
        }

        return $breakTimes;
    }

    /**
     * Return true or false based on an slot of time is inside a break slot.
     *
     * @param object $startSlot   the start of time period
     * @param object $endSlot     the end of time period
     * @param string $currentDate date of the time period
     * @param array  $breaks      array of break slots
     *
     * @return bool true if the time slot is inside a break slot, otherwise false
     */
    public function isInBreak(object $startSlot, object $endSlot, string $currentDate, array $breaks)
    {
        foreach ($breaks as $break) {
            $startbreakSlot = Carbon::createFromFormat('Y-m-d H:i', $currentDate.' '.$break['startBreak']);
            $endBreakSlot = Carbon::createFromFormat('Y-m-d H:i', $currentDate.' '.$break['endBreak']);

            $slotIsInBreak = ($startSlot->gte($startbreakSlot) && $startSlot->lt($endBreakSlot)) ||
            ($endSlot->gt($startbreakSlot) && $endSlot->lte($endBreakSlot)) ||
            ($startSlot->lte($startbreakSlot) && $endSlot->gte($endBreakSlot));
            if ($slotIsInBreak) {
                return true;
            }
        }

        return false;
    }

    /**
     * Return an array with available slots of times for schedule.
     * Return time periods divided into quarters (00,15,30,45).
     *
     * @param object $schedule current schedule to get available slots
     *
     * @return array array with available slots of time
     */
    public function getVetsAvailableIntervals(object $schedule)
    {
        $startString = substr($schedule->startDate.' '.$schedule->startTime, 0, -3);
        $endString = substr($schedule->endDate.' '.$schedule->endTime, 0, -3);
        $breakTimes = $this->extractBreaksFromSchedule($schedule);
        $start = Carbon::createFromFormat('Y-m-d H:i', $startString);
        $end = Carbon::createFromFormat('Y-m-d H:i', $endString)->subMinutes(15);
        $formattedStartDate = Carbon::createFromFormat('Y-m-d', $schedule->startDate)->toDateString();

        $intervals = [];
        $current = $start->clone();

        while ($current <= $end) {
            $roundedTime = $this->roundTimeToNextQuarter($current);
            $slotIsInBreak = $this->isInBreak(
                $roundedTime,
                $roundedTime->clone()->addMinutes(15),
                $formattedStartDate,
                $breakTimes
            );
            if (!$slotIsInBreak) {
                $intervals[] = $this->createIntervalData($roundedTime, $schedule);
            }
            $current = $current->clone()->addMinutes(15);
        }

        return $intervals;
    }

    /**
     * Create interval data of available dates of vets.
     *
     * @param Carbon $startSlotTime start time of the time slot
     * @param object $schedule      vet's schedule data
     *
     * @return object object with necessary information for the inverval
     */
    public function createIntervalData(Carbon $startSlotTime, object $schedule)
    {
        return (object) [
            'date' => $startSlotTime->format('Y-m-d'),
            'timeStart' => $startSlotTime->format('H:i'),
            'timeFinish' => $startSlotTime->clone()->addMinutes(15)->format('H:i'),
            'name' => $schedule->employeeName,
            'id' => $schedule->employeeId,
            'scheduleId' => $schedule->scheduleId,
        ];
    }

    /**
     * Merge all available slots of time of all schedules.
     *
     * @param array $schedules all schedules of vets
     *
     * @return array array with available slots of time of all vets
     */
    public function mergeAllSchedules(array $schedules)
    {
        $availableSchedule = [];
        foreach ($schedules as $schedule) {
            $intervals = $this->getVetsAvailableIntervals($schedule);
            $availableSchedule = [...$availableSchedule, ...$intervals];
        }

        return $availableSchedule;
    }
}
