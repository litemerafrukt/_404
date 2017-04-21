<?php

namespace _404\Components\Calendar;

use _404\Component\ComponentRenderInterface;
use _404\Component\ComponentRenderTrait;
use Anax\Common\AppInjectableInterface;
use Anax\Common\AppInjectableTrait;
use Anax\Common\ConfigureInterface;
use Anax\Common\ConfigureTrait;

/**
 * Class WallCalendar.
 * Mimic physical wall calendar.
 * Uses config: calendar.php.
 *
 * @package _404\Calendar
 */

class WallCalendar implements AppInjectableInterface, ConfigureInterface, ComponentRenderInterface
{
    use AppInjectableTrait;
    use ConfigureTrait;
    use ComponentRenderTrait;

    private $prevMonth;
    private $nextMonth;
    private $currentMonth;
    private $firstWeekdayNumber;

    /**
     * Wall Calendar constructor.
     *
     * @param $year
     * @param $month
     */
    public function __construct($year, $month)
    {
        $prevYear  = $month == 1  ? $year - 1 : $year;
        $nextYear  = $month == 12 ? $year + 1 : $year;
        $prevMonth = $month == 1  ? 12 : $month - 1;
        $nextMonth = $month == 12 ? 1 : $month + 1;

        $this->currentMonth = new Month($year, $month, new SwedishHolidays());
        $this->prevMonth    = new Month($prevYear, $prevMonth, new SwedishHolidays());
        $this->nextMonth    = new Month($nextYear, $nextMonth, new SwedishHolidays());


        $firstOfCurrMonth = new \DateTimeImmutable("$year-$month-01");

        $this->firstWeekdayNumber = $firstOfCurrMonth->format("N");
    }

    /**
     * Get weeks to show on wall calendar.
     *
     * @return array - array of arrays with one week in each
     */
    private function weeks()
    {
        $calenderWeeks = [];

        $daysFromPrev = $this->firstWeekdayNumber - 2;

        $daysFromNext = 6 * 7 - ($this->firstWeekdayNumber - 1 + $this->currentMonth->daysInMonth());

        if ($daysFromPrev >= 0) {
            foreach ($this->prevMonth->endDays($daysFromPrev) as $day) {
                $day->active = false;
                $calenderWeeks[] = $day;
            }
        }

        foreach ($this->currentMonth->allDays() as $day) {
            $day->active = true;
            $calenderWeeks[] = $day;
        }

        if ($daysFromNext >= 0) {
            foreach ($this->nextMonth->frontDays($daysFromNext) as $day) {
                $day->active = false;
                $calenderWeeks[] = $day;
            }
        }

        return array_chunk($calenderWeeks, 7);
    }

    /**
     * Month image.
     *
     * @return string
     */
    private function monthImage()
    {
        return isset($this->config['months'][$this->currentMonth->name()]['image'])
            ? $this->config['months'][$this->currentMonth->name()]['image']
            : '';
    }

    /**
     * Current month config name. Corresponds to datetime month short name.
     *
     * @return string
     */
    private function currentMonthConfigName()
    {
        return isset($this->config['months'][$this->currentMonth->name()]['name'])
            ? $this->config['months'][$this->currentMonth->name()]['name']
            : $this->currentMonth->name();
    }

    /**
     * Previous month config name. Corresponds to datetime month short name.
     *
     * @return string
     */
    private function prevMonthConfigName()
    {
        return isset($this->config['months'][$this->prevMonth->name()]['name'])
            ? $this->config['months'][$this->prevMonth->name()]['name']
            : $this->prevMonth->name();
    }

    /**
     * Next month config name. Corresponds to datetime month short name.
     *
     * @return string
     */
    private function nextMonthConfigName()
    {
        return isset($this->config['months'][$this->nextMonth->name()]['name'])
            ? $this->config['months'][$this->nextMonth->name()]['name']
            : $this->nextMonth->name();
    }

    /**
     * Make views from config callable. PHP magic method.
     *
     * @param $methodName
     * @param array $data
     * @return string
     */
    public function __call($methodName, $data = [])
    {
        $this->validateViewMethod($methodName);
        $viewFile = $this->config['views'][$methodName];

        $prevMonthUri = "calendar/{$this->prevMonth->year()}/{$this->prevMonth->number()}";
        $nextMonthUri = "calendar/{$this->nextMonth->year()}/{$this->nextMonth->number()}";

        $viewData = [
            'image'             => $this->monthImage(),
            'prevMonthPath'     => $this->app->url->create($prevMonthUri),
            'prevMonthName'     => $this->prevMonthConfigName(),
            'currentMonthName'  => $this->currentMonthConfigName(),
            'nextMonthName'     => $this->nextMonthConfigName(),
            'nextMonthPath'     => $this->app->url->create($nextMonthUri),
            'weeks'             => $this->weeks(),
        ];

        $viewData = array_merge($viewData, $data);

        return $this->renderComponent($viewFile, $viewData);
    }
}
