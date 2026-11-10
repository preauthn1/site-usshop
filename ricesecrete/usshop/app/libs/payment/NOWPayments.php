<?php

namespace app\libs\payment;

use app\libs\api\ApiIndexCode;
use app\model\money\RechargeModel;
use think\facade\Log;

class NOWPayments implements PaymentInterface
{
    private array $config = [];

    public function __construct($config)
    {
        $this->config = $config;
    }

    // 充值
    public function create($packageName, $input): array
    {
        // Prepare payment data
        $amount = $input['amount'] ?? 0;
        $currency = $this->config['currency'] ?? 'usd';
        // Set callback URL for payment notifications
        $callbackUrl = $this->config['ipn_callback_url']; //'/api/payment/notify/nowpayments';

        // Create payment request using the NOWPayments API
        $paymentData = [
            'price_amount'        => $amount,
            'price_currency'      => $currency,
            'order_id'            => $input['order_id'],
            'order_description'   => "Recharge " . $amount . " " . $currency,
            'ipn_callback_url'    => $callbackUrl,
            'success_url'         => $this->config['success_url'] ?? '',
            'cancel_url'          => $this->config['cancel_url'] ?? '',
            'is_fixed_rate'       => true, // 是否需要手续费
            'is_fee_paid_by_user' => true, // 手续费由用户支付
        ];

        $header = [
            'x-api-key: ' . $this->config['api_key'],
            'Content-Type: application/json'
        ];
        Log::channel('payment')->info('Submit_create NOWPayments:' . var_export($paymentData, true));
        $res = PayRequest::curlS(
            $this->config['api_url'] . $this->config['invoice_url'],
            json_encode($paymentData),
            $header
        );
        Log::channel('payment')->info('Return_create NOWPayments:' . var_export($res, true));
        $res = json_decode($res, true);
        if (empty($res)) {
            throw new \Exception($res['message'] ?? '支付信息错误', ApiIndexCode::FAIL);
        }
        if (isset($res['invoice_url'])) {
            return ['pay_url' => $res['invoice_url']];
        }
        return $res;
    }

    // 提现
    public function withdrawal()
    {
        // TODO: Implement withdrawal() method.
    }

    // 查询订单
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
    }

    // 充值回调通知
    public function notify($packageName, $input): array
    {
        Log::channel('payment')->info('Notify_Input NOWPayments:' . var_export($input, true));
        // Verify if the request contains valid data
        if (empty($input)) {
            Log::channel('payment')->info('Notify_Error NOWPayments:Invalid data received');
            return ['code' => ApiIndexCode::FAIL, 'msg' => 'Invalid data received'];
        }

        // Extract payment information
        $order_id = $input['order_id'] ?? '';
        $paymentStatus = $input['payment_status'] ?? '';
        $price_amount = $input['price_amount'] ?? '';
        $paymentId = $input['payment_id'] ?? '';
        $payAmount = $input['pay_amount'] ?? 0;
        $payCurrency = $input['pay_currency'] ?? '';

        // Verify the payment status
        if (strtolower($paymentStatus) == 'finished') {
            // Payment confirmed - update your database
            // Process the successful payment

            // Here you would typically:
            // 1. Validate the order exists in your system
            // 2. Check if the payment amount matches expected amount
            // 3. Update the order status
            // 4. Credit the user account
            Log::channel('payment')->info('Notify_Success NOWPayments:' . var_export($input, true));
            $recharge_model = new RechargeModel();
            $result = $recharge_model->endPay('', $order_id, $price_amount, $packageName);
            try {
                if ($result) {
                    return ['code' => ApiIndexCode::SUCCESS, 'msg' => 'Payment processed successfully'];
                } else {
                    return ['code' => ApiIndexCode::FAIL, 'msg' => 'Payment processed failed'];
                }
            } catch (\Exception $e) {
                dd($e);
            }
        } else {
            // Payment has another status (pending, failed, etc.)
            Log::channel('payment')->info('Notify_Failed NOWPayments:' . var_export($input, true));
            return ['code' => ApiIndexCode::FAIL, 'msg' => 'Payment not completed'];
        }
    }

    // 提现回调通知
    public function notify_withdrawal($packageName, $input): array
    {
        // TODO: Implement notify_withdrawal() method.
    }
}
