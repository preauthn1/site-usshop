<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-SysBanner
 * Class SysBannerValidate
 * @package app\validate
 */
class SysBannerValidate extends BaseValidate
{
    protected $rule = [
        'id' => 'require|number',
        'title|标题' => 'require|max:30',
        'src|图片地址' => 'require|max:255',
        'jump_type|跳转类型' => 'require',
        'jump_link|跳转链接' => 'max:255',
        'goods_type|商品类型' => 'number',
        'goods_id|商品id' => 'number',
        'sort|排序' => 'number',
        'status|状态' => 'in:0,1'
    ];
}
