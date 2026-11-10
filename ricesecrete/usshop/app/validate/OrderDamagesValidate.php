<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-退订规则
 * Class OrderDamagesValidate
 * @package app\validate
 */
class OrderDamagesValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'status' => 'require|number',
        'name|标签名称' => 'require',
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
