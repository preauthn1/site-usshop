<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-商品管理
 * Class GoodsProductValidate
 * @package app\validate
 */
class GoodsProductValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'title|商品名称' => 'require',
        'type_id|商品分类' => 'require|number',
        'sort|排序' => 'require|number',
        'status|状态' => 'require|number'
    ];

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }
}
