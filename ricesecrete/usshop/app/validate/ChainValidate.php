<?php

namespace app\validate;

use think\Validate;

/**
 * 验证器-虚拟币链管理
 * Class ChainValidate
 * @package app\validate
 */
class ChainValidate extends Validate
{
    /**
     * 验证规则
     * @var array
     */
    protected $rule = [
        'id'          => 'require|number|gt:0',
        'name'        => 'require|max:50',
        'code'        => 'require|alphaDash|max:30|unique:chain',
        'icon'        => 'max:255',
        'description' => 'max:500',
        'status'      => 'require|in:1,2',
    ];

    /**
     * 错误消息
     * @var array
     */
    protected $message = [
        'id.require'      => 'ID不能为空',
        'id.number'       => 'ID必须为数字',
        'id.gt'           => 'ID必须大于0',
        'name.require'    => '链名称不能为空',
        'name.max'        => '链名称最多50个字符',
        'code.require'    => '链代码不能为空',
        'code.alphaDash'  => '链代码只能包含字母、数字、下划线和破折号',
        'code.max'        => '链代码最多30个字符',
        'code.unique'     => '链代码已存在',
        'icon.max'        => '图标路径过长',
        'description.max' => '描述信息过长',
        'status.require'  => '状态不能为空',
        'status.in'       => '状态值不正确',
    ];

    /**
     * 验证场景
     * @var array
     */
    protected $scene = [
        'status' => ['id', 'status'],
    ];
}
