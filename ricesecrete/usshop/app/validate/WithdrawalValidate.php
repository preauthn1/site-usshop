<?php

namespace app\validate;

use app\base\BaseValidate;
use app\model\system\PaymentChannelModel;

/**
 * 验证器-WithdrawalValidate
 * Class WithdrawalValidate
 * @package app\validate
 */
class WithdrawalValidate extends BaseValidate
{
    protected $rule = [
        'original_amount|提现金额' => 'require|number|checkAmount',
        'channel_id|渠道'          => 'require|number',
//        'user_bank_id|银行卡'      => 'number',
        'address'                  => 'require'
    ];

    protected $message = [
        'channel_id.require' => '请选择提现渠道',
        'address.require'    => '请选择提现渠道',
//        'user_bank_id.number' => '请选择正确银行卡'
    ];

    public function checkAmount($value, $rule, $data = [])
    {
        // 判断提现金额是否满足要求
        $payment_channel = PaymentChannelModel::find($data['channel_id']);
        if (!$payment_channel) {
            return '请选择正确的提现渠道';
        }
        if ($value < $payment_channel['min_withdrawal_money'] || $value > $payment_channel['max_withdrawal_money']) {
            return '提现金额范围在'.$payment_channel['min_withdrawal_money'].'至'.$payment_channel['max_withdrawal_money'].'之间';
        }
        return true;
    }
}
