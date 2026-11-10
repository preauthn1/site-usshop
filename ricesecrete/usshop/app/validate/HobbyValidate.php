<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-HobbyValidate
 * Class HobbyValidate
 * @package app\validate
 */
class HobbyValidate extends BaseValidate
{
    protected $rule = [
        'id'           => 'require|number',
        'title|标题'   => 'require|max:30',
        'src|图片地址' => 'require|max:255',
        'sort|排序'    => 'number',
        'status|状态'  => 'in:0,1',
    ];
}
