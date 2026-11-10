<?php

namespace app\libs\payment;

use app\libs\api\ApiIndexCode;
use app\model\money\RechargeModel;
use Stripe\Event;
use Stripe\Stripe;
use Stripe\StripeClient;
use think\facade\Log;

class StripePay implements PaymentInterface
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
        $currency = $this->pay_set['currency'] ?? 'usd';
        // Set callback URL for payment notifications

        // Create payment request using the StripePay API



        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        $stripe = new StripeClient([
            "api_key" => $this->config['api_key'],
            "stripe_version" => "2025-02-24.acacia; custom_checkout_beta=v1;"
        ]);
        $product_name = "Recharge " . $amount . " " . $currency;
        $price_id = $this->create_product($product_name, $amount);
        $data = [
            'client_reference_id' => $input['order_id'],
            'line_items' => [[
                'price' => $price_id, // 产品id
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'ui_mode' => 'hosted',
            'success_url' => $this->config['success_url'],
        ];
        Log::channel('payment')->info('Submit_create StripePay:' . var_export($data, true));

        $res = $stripe->checkout->sessions->create($data);
        Log::channel('payment')->info('Return_create StripePay:' . var_export($res, true));

        if (empty($res)) {
            throw new \Exception($res['message'] ?? '支付信息错误', ApiIndexCode::FAIL);
        }
        if (isset($res->url)) {
            return ['pay_url' => $res->url];
        }
        return $res;
    }

    //创建商品
    public function create_product($product_name, $amount){
        $_key =  $this->config['api_key'];
        $stripe = new StripeClient($_key);
        $product =  $stripe->products->create([
            'name' => $product_name,
        ]);
        return $this->create_price($product->id, $amount);
    }

    //创建价格
    public function create_price($product_id, float $amount){

        $_key = $this->config['api_key'];
        $stripe = new StripeClient($_key);
        $price = $stripe->prices->create([
            'unit_amount' => $amount*100,
            'currency' => $this->config['currency'],
            'tax_behavior' => 'exclusive', //  exclusive 用户付税，inclusive 用户不付税， 注意，不是手续费
            'product' => $product_id,
        ]);
        return $price->id;
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
        Log::channel('payment')->info('Notify_Input StripePay:' . var_export($input, true));
        // Verify if the request contains valid data
        if (empty($input)) {
            Log::channel('payment')->info('Notify_Error StripePay:Invalid data received');
            return ['code' => ApiIndexCode::FAIL, 'msg' => 'Invalid data received'];
        }

        $_key = $this->config['api_key'];
        Stripe::setApiKey($_key);
        $payload = $input;
        $event = null;
        try {
            $event = Event::constructFrom($payload);
        } catch(\UnexpectedValueException $e) {
            // Payment has another status (pending, failed, etc.)
            Log::channel('payment')->info('Notify_Failed StripePay:' . var_export($input, true));
            return ['code' => ApiIndexCode::FAIL, 'msg' => 'Payment not completed'];
            // Invalid payload
            http_response_code(400);
            exit();
        }
        switch ($event->type) {
            case 'checkout.session.completed': // 支付成功
                $succeeded = $event->data->object;
                Log::channel('payment')->info('Notify_Success StripePay:' . var_export($input, true));

                $token = input('token', '');

                if ($succeeded->status == 'complete') { // 支付完成
                    $recharge_model = new RechargeModel();
                    $order_id = $succeeded->client_reference_id;
                    $amount_total = $succeeded->amount_total / 100;
                    $result = $recharge_model->endPay('', $order_id, $amount_total, $packageName);
                    try {
                        if ($result) {
                            return ['code' => ApiIndexCode::SUCCESS, 'msg' => 'Payment processed successfully'];
                        } else {
                            return ['code' => ApiIndexCode::FAIL, 'msg' => 'Payment processed failed'];
                        }
                    } catch (\Exception $e) {
                        dd($e);
                    }

                }
                break;
            case 'checkout.session.async_payment_failed': // 支付失败
                Log::channel('payment')->info('Notify_Failed StripePay:' . var_export($input, true));
                break;
            default:
                return ['code' => ApiIndexCode::FAIL, 'msg' => 'Received unknown event type ' . $event->type];
                break;
        }
    }

    // 提现回调通知
    public function notify_withdrawal($packageName, $input): array
    {
        // TODO: Implement notify_withdrawal() method.
    }
}
