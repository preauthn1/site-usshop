<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-用户关键词搜索
 * Class UserSearchValidate
 * @package app\validate
 */
class UserSearchValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'status' => 'require|number',
        'keyword|热门搜索' => 'require',
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
