<?php

declare(strict_types=1);

namespace app\validate;

use app\model\system\LanguageItemDetailModel;
use think\Validate;

class LanguageItemDetailValidate extends Validate
{
    protected $rule = [
        'text'        => 'require|checkExists',
        'language_id' => 'require',
        'item_id'     => 'require',
    ];

    protected $message = [
        'text.require'        => '语言翻译不能为空',
        'text.checkExists'    => '语言翻译已存在',
        'language_id.require' => '缺少参数',
        'item_id.require'     => '缺少参数',
    ];

    protected function checkExists($value, $rule, $data): bool
    {
        if (empty($data['id'])) {
            $exist = LanguageItemDetailModel::where([
                'language_id' => $data['language_id'],
                'item_id'     => $data['item_id'],
            ])->find();
            return !$exist;
        }
        return true;
    }
}
