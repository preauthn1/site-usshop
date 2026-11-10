<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-商品分类
 * Class GoodsTypeValidate
 * @package app\validate
 */
class GoodsTypeValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'sort' => 'require|number',
        'status' => 'require|number',
        'name|分类名称' => 'require',
        'parent_id|父级' => 'number',
    ];


    //验证场景-更新
    public function sceneUpdate()
    {
        return $this->only(['id', 'name']);
    }

    //验证场景-上线状态
    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }


    //验证场景-添加
    public function sceneAdd()
    {
        return $this->only(['name', 'parent_id']);
    }
}
