<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-UserHobbyValidate
 * Class HobbyValidate
 * @package app\validate
 */
class UserHobbyValidate extends BaseValidate
{
    protected $rule = [
        'ids' => 'require|checkIDs'
    ];

    protected $message = [
        'ids.checkIDs' => 'ids参数必须为以逗号分隔的多个正整数'
    ];

    protected function checkIDs($value): bool
    {
        $result = true;
        $values = explode(',', $value);
        if (empty($values)) {
            $result = false;
        }
        foreach ($values as $id) {
            if (!$this->isPositiveInteger($id)) {
                // 必须是正整数
                $result = false;
            }
        }
        return $result;
    }

    protected function isPositiveInteger($value): bool
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }

}
