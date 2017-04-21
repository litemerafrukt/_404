<?php

namespace _404\Components\Calendar;

/**
 * Class Month.
 * Used by WallCalendar.
 *
 * @package _404\Calendar
 */
class Month
{
    private $year;
    private $month;
    private $daysInMonth;
    private $holidays;

    /**
     * Month constructor.
     *
     * @param $year
     * @param $month
     * @param $holidays - SwedishHolidays or something with same interface.
     */
    public function __construct($year, $month, $holidays)
    {
        $this->year = $year;
        $this->month = $month;
        $this->daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $this->holidays = $holidays;
    }

    /**
     * Get this month number.
     *
     * @return int
     */
    public function number()
    {
        return $this->month;
    }

    /**
     * Get 3 letter month name. Same as php datetime month shortname.
     *
     * @return string
     */
    public function name()
    {
        $firstDay = new \DateTimeImmutable("$this->year-$this->month-1");
        return $firstDay->format("M");
    }

    /**
     * What year is this month in.
     *
     * @return int
     */
    public function year()
    {
        return $this->year;
    }

    /**
     * Number of days in this month.
     *
     * @return int
     */
    public function daysInMonth()
    {
        return $this->daysInMonth;
    }

    /**
     * Generate days $from to $too.
     *
     * @param $from
     * @param $too
     * @return \Generator
     */
    public function days($from, $too)
    {
        foreach (range($from, $too) as $dayNumber) {
            $date = new \DateTimeImmutable("$this->year-$this->month-$dayNumber");
            $isToday = (new \DateTimeImmutable())->format('Y-m-d') == $date->format('Y-m-d');
            yield new Day(
                $dayNumber,
                $date->format('D'),
                $isToday,
                $this->holidays->name($date)
            );
        }
    }

    /**
     * Generate days from end of month.
     *
     * @param $nrOfDays
     * @return \Generator
     */
    public function endDays($nrOfDays)
    {
        return $this->days($this->daysInMonth - $nrOfDays, $this->daysInMonth);
    }

    /**
     * Generate days from front of month.
     *
     * @param $nrOfDays
     * @return \Generator
     */
    public function frontDays($nrOfDays)
    {
        return $this->days(1, $nrOfDays);
    }

    /**
     * Generate all days from month.
     *
     * @return \Generator
     */
    public function allDays()
    {
        return $this->days(1, $this->daysInMonth);
    }
}
