<?php

namespace _404\Calendar;

/**
 * Class SwedishHolidays
 * Holds and calculates swedish holidays.
 * Contains a lot of hardcoded swedish holiday names.
 *
 * Usage:
 * $holidays->isHoliday($date);
 * $holidays->name($date)
 *
 * @package _404\Calendar
 */
class SwedishHolidays
{
    /**
     * Holidays with fixed dates.
     *
     * @var array
     */
    private $fixed = [
        'Jan' => [
            1 => 'Nyårsdagen',
            6 => 'Trettondedag jul',
        ],
        'May' => [
            1 => 'Första Maj',
        ],
        'Jun' => [
            06 => 'Sveriges Nationaldag',
        ],
        'Dec' => [
            25 => 'Juldagen',
            26 => 'Annandag jul',
        ],
    ];

    /**
     * Holidays with variable dates dependent on weekday and date-range.
     *
     * @var array
     */
    private $variableHolidays = [];

    /**
     * Holidays dependent on easter.
     *
     * @var array
     */
    private $easterDependents = [
        -2    => 'Långfredag',
        0     => 'Påskdagen',
        1     => 'Annandag Påsk',
        39    => 'Kristi himmelfärdsdag',
        49    => 'Pingstdagen'
    ];

    /**
     * SwedishHolidays constructor.
     * Construct the variable holidays.
     */
    public function __construct()
    {
        $this->variableHolidays = [
            new VariableHoliday('Midsommar', 6, 20, 26, 'Sat'),
            new VariableHoliday('Alla helgons dag', 10, 31, 31, 'Sat'),
            new VariableHoliday('Alla helgons dag', 11, 1, 6, 'Sat'),
        ];
    }

    /**
     * Check if date is a holiday.
     *
     * @param \DateTimeImmutable $date
     * @return bool
     */
    public function isHoliday($date)
    {
        return ! empty($this->name($date));
    }

    /**
     * Return holiday name.
     *
     * @SuppressWarnings(PHPMD.StaticAccess) * Motivation, see end of file.
     *
     * @param \DateTimeImmutable $date
     * @return string - empty if not holiday
     */
    public function name($date)
    {
        $year = $date->format('Y');
        $month = $date->format('M');
        $day = $date->format('j');

        // find fixed
        if (isset($this->fixed[$month])) {
            if (isset($this->fixed[$month][$day])) {
                return $this->fixed[$month][$day];
            }
        }

        foreach ($this->variableHolidays as $holiday) {
            if ($holiday->isHoliday($date)) {
                return $holiday->name();
            }
        }

        // find easter dependent
        $easterSunday = (new \DateTimeImmutable("$year-03-21"))
            ->add(\DateInterval::createFromDateString(easter_days($year) . " day"));

        foreach ($this->easterDependents as $fromEaster => $name) {
            $easterDependent = $easterSunday->add(\DateInterval::createFromDateString("$fromEaster day"));
            if ($easterDependent->diff($date)->days === 0) {
                return $name;
            }
        }

        return "";
    }
}

/*
Why supress PHPMD.StaticAccess?
    In this case PHP seems to behave differently if I try too create a DateTimeInterval object instead of
    using static method. I got a parse error if I tried to create a DateInterval object with negative day
    interval i constructor but the static factory method works.
*/
