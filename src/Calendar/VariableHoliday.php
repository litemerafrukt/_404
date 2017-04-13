<?php

namespace _404\Calendar;

class VariableHoliday
{
    private $firstDay;
    private $lastDay;
    private $weekday;
    private $name;

    /**
     * VariableHoliday constructor.
     *
     * @param $month
     * @param $firstDay
     * @param $lastDay
     * @param $weekday
     * @param $name
     */
    public function __construct($name, $month, $firstDay, $lastDay, $weekday)
    {
        $this->month = $month;
        $this->firstDay = $firstDay;
        $this->lastDay = $lastDay;
        $this->weekday = $weekday;
        $this->name = $name;
    }

    public function dayNumber($year)
    {
        $daynumbers = range($this->firstDay, $this->lastDay);
        $weekdaysInRange = array_map(function ($day) use ($year) {
            return (new \DateTime("$year-$this->month-$day"))->format('D');
        }, $daynumbers);

        $weekdayNumbers = array_combine($weekdaysInRange, $daynumbers);

        return isset($weekdayNumbers[$this->weekday])
            ? $weekdayNumbers[$this->weekday]
            : null;
    }

    public function name()
    {
        return $this->name;
    }
}
