<?php

namespace _404\Calendar;

/**
 * Class Day
 * Mainly a container for info needed in view.
 *
 * @package _404\Calendar
 */
class Day
{
    private $number;
    private $isToday;
    private $holidayName;
    private $isHoliday;

    /**
     * Day constructor.
     *
     * @param $number
     * @param $weekday
     * @param $isToday
     * @param $holidayName
     */
    public function __construct($number, $weekday, $isToday, $holidayName)
    {
        $this->number = $number;
        $this->isToday = $isToday;
        $this->holidayName = $holidayName;
        $this->isHoliday = ! empty($holidayName) || $weekday == 'Sun';
    }

    /**
     * Return is today.
     *
     * @return bool
     */
    public function isToday()
    {
        return $this->isToday;
    }

    /**
     * If holiday
     *
     * @return bool
     */
    public function isHoliday()
    {
        return $this->isHoliday;
    }

    /**
     * Holiday name
     *
     * @return string
     */
    public function holidayName()
    {
        return $this->holidayName;
    }

    /**
     * Day number in month
     *
     * @return mixed
     */
    public function number()
    {
        return $this->number;
    }
}
