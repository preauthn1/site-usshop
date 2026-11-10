<?php

declare(strict_types=1);

namespace app\validate;

use think\Validate;

class LanguageValidate extends Validate
{
    protected $rule = [
        'name'          => 'require|max:100',
        'code'          => 'require|max:100',
        'app_lang_code' => 'max:255',
        'rtl'           => 'in:1,2',
        'status'        => 'in:1,2',
    ];

    protected $message = [
        'name.require'      => '语言名称不能为空',
        'name.max'          => '语言名称最多100个字符',
        'code.require'      => '语言代码不能为空',
        'code.max'          => '语言代码最多100个字符',
        'app_lang_code.max' => '应用语言代码最多255个字符',
        'rtl.in'            => 'RTL参数只能是1或2',
        'status.in'         => '状态值只能是1或2',
    ];
}
