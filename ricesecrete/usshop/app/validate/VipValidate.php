<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-LevelValidate
 * Class LevelValidate
 * @package app\validate
 */
class VipValidate extends BaseValidate
{
    protected $rule = [
        'id'                      => 'require',
        'name|等级名称'           => 'require|chsAlphaNum',
        'level|等级标识'          => 'require|number',
        'src|等级图片'            => 'require|url',
        'money|消费值'            => 'require|number',
        'invite_num|有效下级人数' => 'require|number',
        'status|状态'             => 'require',
        'rights|权益'             => 'require',
    ];
}
