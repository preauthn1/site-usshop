<?php

declare(strict_types=1);

namespace app\validate;

use think\Validate;

class CurrencyValidate extends Validate
{
    protected $rule = [
        'name'          => 'require|max:50',
        'symbol'        => 'require|max:20',
        'exchange_rate' => 'float',
        'status'        => 'in:1,2',
        'is_default'    => 'require|in:1,2',
        'code'          => 'require|max:20',
    ];

    protected $message = [
        'name.require'        => '货币名称不能为空',
        'name.max'            => '货币名称最多20个字符',
        'symbol.require'      => '货币单位不能为空',
        'symbol.max'          => '货币单位最多20个字符',
        'code.require'        => '货币代码不能为空',
        'code.max'            => '货币代码最多20个字符',
        'exchange_rate.float' => '汇率只能是数字',
        'status.in'           => '状态值只能是1或2',
        'is_default.in'       => '是否默认只能是1或2',
        'is_default.require'  => '是否默认必填',
    ];
}
