<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-ArticleValidate
 * Class ArticleValidate
 * @package app\validate
 */
class ActivityValidate extends BaseValidate
{
    protected $rule = [
        'id'            => 'require',
        'title|标题'    => 'require',
        'type|类型'     => 'require',
        'summary|摘要'  => 'require',
        'head_img|头图' => 'require',
        'content|内容'  => 'require',
        'status|状态'   => 'require',
    ];

    protected $message = [
        'type.require' => '请选择类型'
    ];
}
