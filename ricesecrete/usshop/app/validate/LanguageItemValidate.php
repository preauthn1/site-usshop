<?php

declare(strict_types=1);

namespace app\validate;

use app\model\system\LanguageItemModel;
use think\Validate;

class LanguageItemValidate extends Validate
{
    protected $rule = [
        'title'   => 'require|max:100|checkTitleUnique',
    ];

    protected $message = [
        'title.require'          => '语言名称不能为空',
        'title.max'              => '语言名称最多100个字符',
        'title.checkTitleUnique' => '语言名称已存在'
    ];

    protected function checkTitleUnique($value, $rule, $data): bool
    {
        $where = [
            ['title', '=', $value]
        ];

        // 如果是编辑操作（存在id），则排除自身
        if (!empty($data['id'])) {
            $where[] = ['id', '<>', $data['id']];
        }

        $exist = LanguageItemModel::where($where)->find();
        return !$exist;
    }
}
