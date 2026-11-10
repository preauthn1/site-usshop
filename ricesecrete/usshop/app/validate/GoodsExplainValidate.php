<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-说明文档
 * Class GoodsExplainValidate
 * @package app\validate
 */
class GoodsExplainValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'status' => 'require|number',
        'type' => 'require|number',
        'title|标签名称' => 'require',
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
