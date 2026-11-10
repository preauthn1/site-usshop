<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-CommissionRateValidate
 * Class HobbyValidate
 * @package app\validate
 */
class CommissionRateValidate extends BaseValidate
{
    protected $rule = [
        'id'             => 'require',
        'level|代理级别' => 'require|number',
        'rate|返佣比例'  => 'require|number',
        'status|状态'    => 'require',
    ];
}
