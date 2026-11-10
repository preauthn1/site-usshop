<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-系统配置
 * Class SysConfigValidate
 * @package app\validate
 */
class SysConfigValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'name|参数名称' => 'require',
        'key|参数键' => 'require',
    ];
}
