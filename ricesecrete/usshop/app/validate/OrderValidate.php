<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-订单
 * Class OrderValidate
 * @package app\validate
 */
class OrderValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
    ];
}
