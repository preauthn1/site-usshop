<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-商品快递公司
 * Class GoodsLogisticsValidate
 * @package app\validate
 */
class GoodsLogisticsValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'status' => 'require|number',
        'name|名称' => 'require',
        'code|编码' => 'require',
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
