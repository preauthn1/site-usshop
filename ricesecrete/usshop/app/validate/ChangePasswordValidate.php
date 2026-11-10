<?php

namespace app\validate;

use app\base\BaseValidate;
use app\libs\AliyunSms;
use Psr\SimpleCache\InvalidArgumentException;
use think\facade\Cache;

/**
 * 验证器-修改密码
 * Class UserValidate
 * @package app\validate
 */
class ChangePasswordValidate extends BaseValidate
{
    protected $rule = [
        'old_password'     => 'require|min:6|max:20',
        'password'         => 'require|min:6|max:20',
        'confirm_password' => 'require|confirm:password',
        'phone'            => 'require|mobile',
        'email'            => 'require|email',
        'code'             => 'require|number|length:6|checkPhoneCode|checkEmailCode'
    ];

    protected $message = [
        'old_password.require'     => '请输入旧密码',
        'password.require'         => '请输入新密码',
        'password.min'             => '请输入6-20位新密码',
        'password.max'             => '请输入6-20位新密码',
        'confirm_password.require' => '请输入确认密码',
        'confirm_password.confirm' => '两次密码输入不一致',
        'phone.require'            => '请输入手机号',
        'email.require'            => '请输入邮箱',
        'email.email'              => '请输入正确的邮箱',
        'code.require'             => '请输入验证码',
        'code.length'              => '请输入正确的验证码',
        'code.checkPhoneCode'      => '验证码错误',
        'code.checkEmailCode'      => '验证码错误'
    ];


    public function sceneChangePassword(): ChangePasswordValidate
    {
        return $this->only(['old_password', 'password', 'confirm_password']);
    }

    public function sceneChangePasswordByPhone(): ChangePasswordValidate
    {
        return $this->only(['phone', 'password', 'confirm_password', 'code'])
            ->remove('code', 'checkEmailCode');
    }

    public function sceneChangePasswordByEmail(): ChangePasswordValidate
    {
        return $this->only(['email', 'password', 'confirm_password', 'code'])
            ->remove('code', 'checkPhoneCode');
    }


    /**
     * 验证验证码
     * @throws InvalidArgumentException
     */
    protected function checkPhoneCode($value, $rule, $data = [])
    {
        $res = AliyunSms::verifySms($data['phone'], $value);
        if ($res['code'] !== 1) {
            return $res['msg'];
        }
        return true;
    }

    /**
     * 验证验证码
     * @throws InvalidArgumentException
     */
    protected function checkEmailCode($value, $rule, $data = []): bool
    {
        $verify_code = Cache::store('redis')->get($data['email']);
        if ($verify_code !== $value) {
            return false;
        }
        Cache::store('redis')->delete($data['email']);
        return true;
    }

}
