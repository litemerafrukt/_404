<?php

namespace _404\Components\Calendar;

/**
 * Class VariableHoliday
 * Helper class for variable holidays.
 *
 * @package _404\Calendar
 */
class VariableHoliday
{
    private $name;
    private $firstDay;
    private $lastDay;
    private $weekday;

    /**
     * VariableHoliday constructor.
     *
     * @param $name
     * @param $month
     * @param $firstDay
     * @param $lastDay
     * @param $weekday
     */
    public function __construct($name, $month, $firstDay, $lastDay, $weekday)
    {
        $this->name = $name;
        $this->month = $month;
        $this->firstDay = $firstDay;
        $this->lastDay = $lastDay;
        $this->weekday = $weekday;
    }

    /**
     * Calculate if date is holiday.
     *
     * @param $date
     * @return bool
     */
    public function isHoliday($date)
    {
        $daynumbers = range($this->firstDay, $this->lastDay);
        foreach ($daynumbers as $day) {
            if ($date->format("n-j-D") == "$this->month-$day-$this->weekday") {
                return true;
            }
        }
        return false;
    }

    /**
     * Holiday name.
     *
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }
}
