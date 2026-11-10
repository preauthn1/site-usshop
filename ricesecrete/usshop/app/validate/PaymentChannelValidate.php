<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-PaymentChannelValidate
 * Class PaymentChannelValidate
 * @package app\validate
 */
class PaymentChannelValidate extends BaseValidate
{
    protected $rule = [
        'id'          => 'require',
        'name|名称'   => 'require',
        'ident|标识'  => 'require',
        'status|状态' => 'require',
    ];
}
