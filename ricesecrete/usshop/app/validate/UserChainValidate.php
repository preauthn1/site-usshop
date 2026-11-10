<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-UserChainValidate
 * Class UserChainValidate
 * @package app\validate
 */
class UserChainValidate extends BaseValidate
{
    protected $rule = [
        'id'                  => 'require|number',
        'chain_id|链'      => 'require',
        'address|卡号'        => 'require',
    ];
}
