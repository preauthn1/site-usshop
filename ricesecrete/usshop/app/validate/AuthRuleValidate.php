<?php
namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-权限菜单
 * Class AuthRuleValidate
 * @package app\validate
 */
class AuthRuleValidate extends BaseValidate
{
    protected $rule = [
        'id'  =>  'require|number',
        'name|规则标识'  =>  'require|max:30|unique:auth_rule,name',
        'title|规则名称'  =>  'require|max:30',
        'url|规则URL' => 'max:200',
        'url_param|路由参数' => 'max:30',
        'icon|图标' => 'max:60',
        'type|类型' => 'in:0,1,2,3,4',
        'api_method|接口类型' => 'in:0,1,2,3,4',
        'rank|排序' => 'number',
        'parent_id|父级ID' => 'number'
    ];
}
