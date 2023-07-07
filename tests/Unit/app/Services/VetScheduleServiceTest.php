<?php

namespace Tests\Unit\app\Services;

use App\Services\VetScheduleService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Tests\Mocks\ScheduleMock;

class VetScheduleServiceTest extends TestCase
{
    /**
     * Unit test for mergeallSchedules function.
     */
    public function merge_all_schedules(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $response = $service->mergeAllSchedules($schedules);
        $this->assertCount(28, $response);
    }

    /**
     * Unit test for getVetsAvailableIntervals function.
     */
    public function testGetVetAvailableIntervals(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $response = $service->getVetsAvailableIntervals($schedules[0]);
        $this->assertTrue(true);
    }

    /**
     * Unit test for roundTimeToNextQuarter function.
     */
    public function testRoundTimeToNextQuarter(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $response = $service->roundTimeToNextQuarter(Carbon::createFromFormat('H:i', '10:58'));
        $this->assertEquals('11:00', $response->format('H:i'));
    }

    /**
     * Unit test for extractBreaksFromSchedule function.
     */
    public function testExtractBreaksFromSchedule(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $response = $service->extractBreaksFromSchedule($schedules[0]);
        $this->assertCount(3, $response);
    }

    /**
     * Unit test for isInBreak function.
     *
     * @return bool true because the slot of time is in a break
     */
    public function testIsInBreak(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $startSlot = Carbon::createFromFormat('Y-m-d H:i:s', $schedules[0]->startDate.'  09:00:00');
        $endSlot = Carbon::createFromFormat('Y-m-d H:i:s', $schedules[0]->startDate.'  09:15:00');
        $breaks = $service->extractBreaksFromSchedule($schedules[0]);
        $response = $service->isInBreak($startSlot, $endSlot->addMinutes(15), $schedules[0]->startDate, $breaks);
        $this->assertTrue($response);
    }

    /**
     * Unit test for isInBreak function.
     *
     * @return bool false because the slot of time is not in a break
     */
    public function testIsNotInBreak(): void
    {
        $schedules = ScheduleMock::getScheduleMock();
        $service = new VetScheduleService();
        $startSlot = Carbon::createFromFormat('Y-m-d H:i:s', $schedules[0]->startDate.'  07:00:00');
        $endSlot = Carbon::createFromFormat('Y-m-d H:i:s', $schedules[0]->startDate.'  07:15:00');
        $breaks = $service->extractBreaksFromSchedule($schedules[0]);
        $response = $service->isInBreak($startSlot, $endSlot->addMinutes(15), $schedules[0]->startDate, $breaks);
        $this->assertFalse($response);
    }
}
