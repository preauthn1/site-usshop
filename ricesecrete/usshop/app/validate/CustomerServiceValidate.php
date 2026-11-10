<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-CustomerServiceValidate
 * Class HobbyValidate
 * @package app\validate
 */
class CustomerServiceValidate extends BaseValidate
{
    protected $rule = [
        'id'           => 'require|number',
        'type|标题'    => 'in:1,2',
        'title|标题'   => 'require|max:30',
        'content|内容' => 'require|max:255',
        'sort|排序'    => 'number',
        'status|状态'  => 'in:0,1',
    ];
}
