<?php
namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-角色分组
 * Class AuthGroupValidate
 * @package app\validate
 */
class AuthGroupValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'title|角色名称' => 'require|max:30',
        'status|状态' => 'in:0,1'
    ];
}
