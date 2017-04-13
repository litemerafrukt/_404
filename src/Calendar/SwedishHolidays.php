<?php

namespace _404\Calendar;

class SwedishHolidays
{
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

    private $variableHolidays = [];

    private $easterDependents = [
        -2    => 'Långfredag',
        0     => 'Påskdagen',
        1     => 'Annandag Påsk',
        39    => 'Kristi himmelfärdsdag',
        49    => 'Pingstdagen'
    ];

    public function __construct()
    {
        $this->variableHolidays = [
            new VariableHoliday('Midsommar', 6, 20, 26, 'Sat'),
            new VariableHoliday('Alla helgons dag', 10, 31, 31, 'Sat'),
            new VariableHoliday('Alla helgons dag', 11, 1, 6, 'Sat'),
        ];
    }

    public function isHoliday($date)
    {
        return ! empty($this->name($date));
    }

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
            if ($holiday->dayNumber($year) == $day) {
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

//$holidays->isHoliday(Date());
//$holidays->name(Date());
