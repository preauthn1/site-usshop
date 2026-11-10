<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-公告
 * Class SysBulletinValidate
 * @package app\validate
 */
class SysBulletinValidate extends BaseValidate
{
    protected $rule = [
        'id'                => 'require|number',
        'title|标题'        => 'require|max:50',
        'content|标题'      => 'require',
        'status|状态'       => 'in:1,2',
        'is_popup|是否弹窗' => 'in:1,2'
    ];
}
