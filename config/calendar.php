<?php

return [
    'views' => [
        /**
         * Look in WallCalendar class method __call() to see what values
         * is supplied to view.
         */
        'fullCalendar' => _404_APP_PATH . '/view/calendarcomponent/calendar.php',
    ],
    'months' => [
        'Jan' => [
            'image' => $this->app->url->asset('image/months/january.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Januari',
        ],
        'Feb' => [
            'image' => $this->app->url->asset('image/months/february.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Februari',
        ],
        'Mar' => [
            'image' => $this->app->url->asset('image/months/mars.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Mars',
        ],
        'Apr' => [
            'image' => $this->app->url->asset('image/months/april.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'April',
        ],
        'May' => [
            'image' => $this->app->url->asset('image/months/may.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Maj',
        ],
        'Jun' => [
            'image' => $this->app->url->asset('image/months/june.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Juni',
        ],
        'Jul' => [
            'image' => $this->app->url->asset('image/months/july.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Juli',
        ],
        'Aug' => [
            'image' => $this->app->url->asset('image/months/august.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Augusti',
        ],
        'Sep' => [
            'image' => $this->app->url->asset('image/months/september.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'September',
        ],
        'Oct' => [
            'image' => $this->app->url->asset('image/months/october.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'Oktober',
        ],
        'Nov' => [
            'image' => $this->app->url->asset('image/months/november.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'November',
        ],
        'Dec' => [
            'image' => $this->app->url->asset('image/months/december.jpg?w=1164&h=728&crop-to-fit'),
            'name'  => 'December',
        ],
    ]
];
