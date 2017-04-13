<?php

namespace _404\Calendar;

class SwedishHolidays
{
    private $fixed;
    private $easterDependent;

    public function __construct()
    {
        $this->fixed = [
            'Jan' => [
                '01' => 'Nyårsdagen',
                '06' => 'Trettondedag jul',
            ],
            'May' => [
                '01' => 'Första Maj',
            ],
            'Jun' => [
                '06' => 'Sveriges Nationaldag',
            ],
            'Dec' => [
                '25' => 'Juldagen',
                '26' => 'Annandag jul',
            ],
        ];

        $this->easterDependent = [
            '-2'    => 'Långfredag',
            '1'     => 'Annandag Påsk',
            '39'    => 'Kristi himmelfärdsdag',
            '49'    => 'Pingstdagen'
        ];
    }

    public function midsummersDay($year)
    {
        foreach (range(20, 26) as $day) {
            if (new DateTime("$year-06-$day") == 'Sat') {
                return $day;
            }
        }
    }
}

//$holidays->isHoliday(Date());
//$holidays->holidayName();
