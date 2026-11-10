<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-商品规格
 * Class GoodsSpecSpecValidate
 * @package app\validate
 */
class GoodsSpecSpecValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'product_id|商品id' => 'require|number',
        'status' => 'require|number'
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
