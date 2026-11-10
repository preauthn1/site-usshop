<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-ArticleValidate
 * Class ArticleValidate
 * @package app\validate
 */
class ArticleValidate extends BaseValidate
{
    protected $rule = [
        'id'           => 'require',
        'title|标题'   => 'require',
        'type|类型'    => 'require',
        'content|内容' => 'require',
        'status|状态'  => 'require',
    ];

    protected $message = [
        'type.require' => '请选择平台类型'
    ];
}
