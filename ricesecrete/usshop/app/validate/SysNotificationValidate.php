<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-站内信
 * Class SysNotificationValidate
 * @package app\validate
 */
class SysNotificationValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'title|标题' => 'require|max:30',
        'status|状态' => 'in:1,2'
    ];
}
