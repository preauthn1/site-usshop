<?php

namespace app\validate;

use app\base\BaseValidate;
use app\model\system\PaymentChannelModel;

/**
 * 验证器-WithdrawalValidate
 * Class WithdrawalValidate
 * @package app\validate
 */
class RechargeValidate extends BaseValidate
{
    protected $rule = [
        'amount|充值金额' => 'require|float',
        'channel_id|渠道' => 'require|number|checkChannel',
    ];

    protected $message = [
        'amount.require'     => '请输入充值金额',
        'channel_id.require' => '请选择充值渠道',
    ];

    public function checkChannel($value, $rule, $data = [])
    {
        // 判断提现金额是否满足要求
        $payment_channel = PaymentChannelModel::field('id')->find($data['channel_id']);
        if (!$payment_channel) {
            return '请选择正确的提现渠道';
        }
        return true;
    }
}
