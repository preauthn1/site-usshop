<?php

namespace app\validate;

use app\base\BaseValidate;
use app\repository\UserAddressRepository;

/**
 * 验证器-UserAddressValidate
 * Class UserAddressValidate
 * @package app\validate
 */
class UserAddressValidate extends BaseValidate
{
    protected $rule = [
        'id'                      => 'require|number|checkOwnership',
        'name|收货人'             => 'require|max:30',
        'mobile|收货人电话'       => 'require|max:11',
        'province|省名称'         => 'require',
        'city|市名称'             => 'require',
        'area|区名称'             => 'require',
        'address|详细地址'        => 'require',
        'is_default|是否默认地址' => 'boolean',
        'sort|排序'               => 'number',
    ];

    protected $message = [
        'id.checkOwnership' => 'Parameter error'
    ];

    //验证场景-默认状态
    public function sceneIsDefault(): UserAddressValidate
    {
        return $this->only(['id', 'is_default']);
    }

    public function checkOwnership($value, $rule, $data): bool
    {
        $address = (new UserAddressRepository)->field('user_id')->first($value);
        if (!$address || $address['user_id'] !== $this->request->currentUser['user_id']) {
            return false;
        }
        return true;
    }
}
