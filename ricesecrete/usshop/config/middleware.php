<?php

return [
    // 别名或分组
    'alias' => [
        'token' => app\middleware\CheckToken::class,
        'auth' => app\middleware\CheckAuth::class,
        'handleLog' => app\middleware\CheckHandleLog::class,
    ],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [
        app\middleware\CheckToken::class,
        app\middleware\CheckAuth::class,
        app\middleware\CheckHandleLog::class
    ]
];