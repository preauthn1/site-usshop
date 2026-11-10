<?php

namespace app\validate;

use app\base\BaseValidate;

/**
 * 验证器-UserBankValidate
 * Class UserBankValidate
 * @package app\validate
 */
class UserBankValidate extends BaseValidate
{
    protected $rule = [
        'id'                  => 'require|number',
        'bank_id|银行卡'      => 'require|number',
        'opening_bank|开户行' => 'require',
        'bank_no|行号'        => 'require|number',
        'card_no|卡号'        => 'number|number',
    ];
}
