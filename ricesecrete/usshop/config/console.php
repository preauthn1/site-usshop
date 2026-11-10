<?php

return [
    // 指令定义
    'commands' => [
        //版本升级
        'upgrade' => 'app\command\Upgrade',
        //路由生成
        'newpage' => 'app\command\NewPage',
        //取消未支付订单
        'cancel_order' => 'app\command\CancelOrder',
        //更新语言
        'lang_update' => 'app\command\LangUpdate',
        //测试
        'test' => 'app\command\Test',
    ]
];
