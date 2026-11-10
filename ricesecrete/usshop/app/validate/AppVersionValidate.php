<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-HobbyValidate
 * Class HobbyValidate
 * @package app\validate
 */
class AppVersionValidate extends BaseValidate
{
    protected $rule = [
        'id'                           => 'require',
        'version_code|版本号（数字）'    => 'require',
        'version_name|版本号'          => 'require',
        'bulletin|公告'                => 'require',
        'platform_type|平台'           => 'require',
        'is_force_update|是否强制更新' => 'require',
        'is_wgt|是否wgt包'             => 'require',
        'url|下载地址'                 => 'require',
        'status|状态'                  => 'require',
    ];

    protected $message = [
        'platform_type.require' => '请选择平台类型'
    ];

    protected $scene = [
        'check' => ['version_code', 'platform_type']
    ];
}
