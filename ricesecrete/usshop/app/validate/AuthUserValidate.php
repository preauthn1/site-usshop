<?php
namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-权限用户
 * Class AuthUserValidate
 * @package app\validate
 */
class AuthUserValidate extends BaseValidate
{
    protected $rule = [
        'id'  =>  'require|number',
        'username|用户名'  =>  'require|max:30|unique:auth_user,username',
        'password|密码' =>  'require',
        'realname|真实姓名' => 'require|max:30',
//        'captcha|验证码'=>'require|captcha',
        'group_id|所属角色'=>'require|number',
        'status|状态' =>  'in:0,1'
    ];

    public function sceneLogin()
    {
//        return $this->only(['username.require','password','captcha']);
        return $this->only(['username.require','password']);
    }

    public function sceneEditpwd()
    {
        return $this->only(['id','password']);
    }

    public function sceneAdd()
    {
        return $this->only(['username','realname','group_id','status']);
    }

    public function sceneEdit()
    {
        return $this->only(['id','username','realname','group_id','status']);
    }
}
