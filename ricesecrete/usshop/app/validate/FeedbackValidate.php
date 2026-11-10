<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-UserAddressValidate
 * Class UserAddressValidate
 * @package app\validate
 */
class FeedbackValidate extends BaseValidate
{
    protected $rule = [
        'type|问题类型'    => 'require',
        'content|内容'     => 'require|min:10|max:500',
        'picture|截图'     => 'require',
        'contact|联系方式' => 'require|min:8',
    ];

    protected $message = [
        'type.require'    => '请选择问题类型',
        'content.require' => '请填写内容',
        'picture.require' => '请上传截图',
        'contact.require' => '请填写联系方式',
        'contact.min'     => '请输入正确的联系方式',
    ];
}
