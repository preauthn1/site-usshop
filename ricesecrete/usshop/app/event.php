<?php

use app\subscribe\UserSubscribe;

return [
    'bind'      => [
        'TeamCommission' => 'app\event\TeamCommission',
    ],

    'listen'    => [
        'AppInit'  => [],
        'HttpRun'  => [],
        'HttpEnd'  => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'TeamCommission'    =>    ['app\listener\TeamCommission'],
    ],

    'subscribe' => [
        UserSubscribe::class,
    ]
];
