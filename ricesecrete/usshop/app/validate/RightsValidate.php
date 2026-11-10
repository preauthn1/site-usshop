<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-RightsValidate
 * Class RightsValidate
 * @package app\validate
 */
class RightsValidate extends BaseValidate
{
    protected $rule = [
        'id'                      => 'require',
        'name|等级名称'           => 'require|chsAlphaNum',
        'src|等级图片'            => 'require|url',
        'content|消费值'            => 'require',
    ];
}
