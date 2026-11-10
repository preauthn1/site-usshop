<?php

namespace app\validate;

use app\base\BaseValidate;
use app\libs\AliyunSms;
use app\model\user\UserModel;
use Psr\SimpleCache\InvalidArgumentException;
use think\facade\Cache;

/**
 * 验证器-用户管理
 * Class UserValidate
 * @package app\validate
 */
class UserValidate extends BaseValidate
{
    protected $rule = [
        'id'                        => 'require|number',
        'phone|手机号码'            => 'require|mobile|checkPhone|max:50',
        'email|邮箱'                => 'require|email|checkEmail|max:50',
        'code|验证码'               => 'require|number|length:6|checkCode|checkEmailCode',
        'password|密码'             => 'require|min:6|max:20',
        'old_password|原始密码'     => 'require|min:6|max:20',
        'confirm_password|确认密码' => 'require|confirm:password|max:20',
        'status|状态'               => 'in:1,2',
        'popularize_code|邀请码'    => 'alphaNum|length:6|checkPopularizeCode|max:20',

        'access_token'              => 'require',
        'openid'                    => 'require',
        'nick_name|昵称'            => 'chsDash|min:1|max:50',
        'gender|性别'               => 'in:1,2',
//        'head_img|头像'             => 'url',
        'birthday|生日'             => 'date',
        'province|省名称'           => 'chsAlpha',
        'city|市名称'               => 'chsAlpha',
    ];

    protected $message = [
        'code.checkCode'           => '验证码错误',
        'code.checkEmailCode'      => '验证码错误',
        'popularize_code.alphaNum' => '请输入正确的邀请码',
        'popularize_code.length'   => '请输入正确的邀请码',
        'confirm_password.confirm' => '密码和确认密码不一致',
        'gender.in'                => '性别不正确',
        'province.chsAlpha'        => '省名称格式不正确',
        'city.chsAlpha'            => '市名称格式不正确',
        'nick_name.checkNickName'  => '昵称已存在'
    ];

    protected $scene = [
        'uniPhoneLogin'        => ['access_token', 'openid'],
        'updateInfo'           => ['nick_name', 'gender', 'head_img', 'birthday', 'province', 'city'],
    ];

    public function sceneRegisterPhone(): UserValidate
    {
        return $this->only(['password', 'confirm_password', 'popularize_code', 'phone', 'code'])
            ->remove('code', 'checkEmailCode');
    }

    public function sceneRegisterEmail(): UserValidate
    {
        return $this->only(['password', 'confirm_password', 'popularize_code', 'email'])
            ->remove('code', 'checkCode');
    }

    // 登录验证场景定义
    public function sceneLoginPhone(): UserValidate
    {
        return $this->only(['phone', 'password'])
            ->remove('phone', 'checkPhone');
    }
    // 登录验证场景定义
    public function sceneLoginEmail(): UserValidate
    {
        return $this->only(['email', 'password'])
            ->remove('email', 'checkEmail');
    }

    public function sceneCheckCode(): UserValidate
    {
        return $this->only(['phone', 'code'])
            ->remove('code', 'checkEmailCode')
            ->remove('phone', 'checkPhone');

    }

    public function sceneCheckEmailCode(): UserValidate
    {
        return $this->only(['email', 'code'])
            ->remove('code', 'checkCode');
    }


    //验证场景-状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }

    /**
     * 验证验证码
     * @throws InvalidArgumentException
     */
    protected function checkCode($value, $rule, $data = [])
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

    /**
     * 验证手机号是否已注册
     */
    protected function checkPhone($value, $rule, $data = [])
    {
        $exist_user_id = UserModel::where('phone', $value)->value('id');
        if ($exist_user_id) {
            return '手机号已注册';
        }
        return true;
    }

    /**
     * 验证手机号是否已注册
     */
    protected function checkEmail($value, $rule, $data = [])
    {
        $exist_user_id = UserModel::where('email', $value)->value('id');
        if ($exist_user_id) {
            return '邮箱已注册';
        }
        return true;
    }

    /**
     * 校验推广码
     */
    protected function checkPopularizeCode($value, $rule, $data = [])
    {
        if (empty($value)) {
            return true;
        }
        $top_user_id = (new UserModel())->getUserIdByPopularizeCode($value);
        if (!$top_user_id) {
            return '请输入正确的邀请码';
        }
        return true;
    }

    public function checkAreaCode($value, $rule, $data = []): bool
    {
        if (!preg_match('/^\d{6}$/', $value)) {
            return false;
        }
        return true;
    }

    public function checkNickName($value, $rule, $data = []): bool
    {
        $exist_user_id = UserModel::where('nick_name', $value)->value('id');
        if ($exist_user_id) {
            return false;
        }
        return true;
    }
}
