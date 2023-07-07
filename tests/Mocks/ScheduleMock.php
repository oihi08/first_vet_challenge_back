<?php

namespace Tests\Mocks;

class ScheduleMock
{
    public static function getScheduleMock()
    {
        return [
            (object) [
                'scheduleId' => 4713,
                'startDate' => '2020-04-29',
                'startTime' => '07:30:00',
                'endDate' => '2020-04-29',
                'endTime' => '15:45:00',
                'startBreak' => '09:08:00',
                'endBreak' => '09:26:00',
                'startBreak2' => '11:15:00',
                'endBreak2' => '11:31:00',
                'startBreak3' => '14:20:00',
                'endBreak3' => '14:25:00',
                'startBreak4' => '00:00:00',
                'endBreak4' => '00:00:00',
                'employeeId' => 4714,
                'employeeName' => 'Oihane',
            ],
        ];
    }
}
