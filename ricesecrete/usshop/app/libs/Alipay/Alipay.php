<?php

namespace app\libs\Alipay;

use Psr\Http\Message\MessageInterface;
use think\facade\Config;
use Yansongda\Pay\Exception\ContainerException;
use Yansongda\Pay\Exception\InvalidParamsException;
use Yansongda\Pay\Exception\ServiceNotFoundException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Plugin\Alipay\Tools\SystemOauthTokenPlugin;
use Yansongda\Supports\Collection;

class Alipay
{
    protected $config;

    /**
     * @throws ContainerException
     */
    public function __construct()
    {
        $this->config = Config::get('pay');
        Pay::config($this->config);
    }

    /**
     * APP 支付
     * @param $subject string 订单标题 不可使用特殊字符，如 /，=，& 等
     * @param $out_trade_no string 商户订单号。64个字符以内，仅支持字母、数字、下划线且需保证在商户端不重复。
     * @param $total_amount float 订单总金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0
     * @return string
     */
    public function app(string $subject, string $out_trade_no, float $total_amount): string
    {
        $response = Pay::alipay()->app([
            'out_trade_no' => $out_trade_no,
            'total_amount' => $total_amount,
            'subject'      => $subject,
        ]);
        return $response->getBody()->getContents();
    }

    /**
     * H5支付
     * @param $subject string 订单标题 不可使用特殊字符，如 /，=，& 等
     * @param $out_trade_no string 商户订单号。64个字符以内，仅支持字母、数字、下划线且需保证在商户端不重复。
     * @param $total_amount float 订单总金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0
     * @param $quit_url string 可用于用户付款中途退出并返回到该参数设置的网站地址上
     * @return string
     */
    public function h5(string $subject, string $out_trade_no, float $total_amount, string $quit_url): string
    {
        $response = Pay::alipay()->wap([
            'out_trade_no' => $out_trade_no,
            'total_amount' => $total_amount,
            'subject'      => $subject,
            'quit_url'     => $quit_url,
        ]);
        return $response->getBody()->getContents();
    }

    /**
     * code换取 access_token
     * 获取用户 open_id
     * @throws InvalidParamsException
     * @throws ContainerException
     */
    public function auth($code)
    {
        $params = [
            'grant_type' => 'authorization_code',
            'code'       => $code,
        ];

        $allPlugins = Pay::alipay()->mergeCommonPlugins([SystemOauthTokenPlugin::class]);

        return Pay::alipay()->pay($allPlugins, $params);


        // 获取用户信息
//        $params = [
//            'auth_token' => 'authusrB59776f4d3c6a4f808ddf9098c9b91X35' //$result['access_token'],
//        ];
//
//        $allPlugins = Pay::alipay()->mergeCommonPlugins([InfoSharePlugin::class]);

//        $result = Pay::alipay()->pay($allPlugins, $params);
//       return $result;
    }

    /**
     * 发起退款
     * @param $out_trade_no  string 商户订单号 订单支付时传入的商户订单号
     * @param $refund_amount float 退款金额，单位为元，精确到小数点后两位，取值范围为 [0.01,100000000]。金额不能为0
     * @param $refund_reason string 退款原因
     * @return array|MessageInterface|Collection|null
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function refund(string $out_trade_no, float $refund_amount, string $refund_reason)
    {
        return Pay::alipay()->refund([
            'out_trade_no'  => $out_trade_no,
            'refund_amount' => $refund_amount,
            'refund_reason' => $refund_reason,
            // '_action' => 'agreement', // 商家收款退款
            // '_action' => 'authorization', // 预授权退款
            '_action'       => 'transfer', // 转账退款
        ]);
    }

    /**
     * 查询普通支付订单
     * @param  string  $out_trade_no  商户订单号 订单支付时传入的商户订单号
     * @param  string  $_action
     * @return mixed
     */
    public function queryOutTradeNo(string $out_trade_no, string $_action = 'web')
    {
        $order = [
            'out_trade_no' => $out_trade_no,
            '_action'      => $_action // 'web', // 默认值，查询普通支付网页订单
            // '_action' => 'app', // 查询 APP 订单
            // '_action' => 'mini', // 查询小程序订单
            // '_action' => 'pos', // 查询刷卡订单
            // '_action' => 'scan', // 查询扫码订单
            // '_action' => 'h5', // 查询 H5 订单

        ];

        // [
        //    "code" => "10000"
        //    "msg" => "Success"
        //    "buyer_logon_id" => "api***@sandbox.com"
        //    "buyer_pay_amount" => "0.00"
        //    "buyer_user_type" => "PRIVATE"
        //    "invoice_amount" => "0.00"
        //    "out_trade_no" => "0000000004"
        //    "point_amount" => "0.00"
        //    "receipt_amount" => "0.00"
        //    "send_pay_date" => "2024-09-09 16:54:53"
        //    "total_amount" => "0.01"
        //    "trade_no" => "2024090922001491040503941822"
        //    "trade_status" => "TRADE_CLOSED"
        //    "buyer_open_id" => "0047zfMJwJy0V8dxoSLtYhRqnbHWLJ6mIU-PrQ8poJ3vws8"
        //  ]
        return Pay::alipay()->query($order);
    }

    /**
     * 查询退款订单
     * @param  string  $out_trade_no  商户订单号 订单支付时传入的商户订单号
     * @param  string  $out_request_no  退款请求号。 请求退款接口时，传入的退款请求号
     * @param  string  $_action
     * @return mixed
     */
    public function queryOutRequestNo(string $out_trade_no, string $out_request_no, string $_action = 'refund')
    {
        $order = [
            'out_trade_no'   => $out_trade_no,
            'out_request_no' => $out_request_no,
            '_action'        => $_action //'refund', // 默认值，查询退款网页订单
            // '_action' => 'refund_web', // 默认值，查询退款网页订单
            // '_action' => 'refund_app', // 查询 APP 退款订单
            // '_action' => 'refund_mini', // 查询小程序退款订单
            // '_action' => 'refund_pos', // 查询刷卡退款订单
            // '_action' => 'refund_scan', // 查询扫码退款订单
            // '_action' => 'refund_h5', // 查询 H5 退款订单
        ];

        // [
        //    "code" => "10000"
        //    "msg" => "Success"
        //    "buyer_logon_id" => "api***@sandbox.com"
        //    "buyer_pay_amount" => "0.00"
        //    "buyer_user_type" => "PRIVATE"
        //    "invoice_amount" => "0.00"
        //    "out_trade_no" => "0000000004"
        //    "point_amount" => "0.00"
        //    "receipt_amount" => "0.00"
        //    "send_pay_date" => "2024-09-09 16:54:53"
        //    "total_amount" => "0.01"
        //    "trade_no" => "2024090922001491040503941822"
        //    "trade_status" => "TRADE_CLOSED"
        //    "buyer_open_id" => "0047zfMJwJy0V8dxoSLtYhRqnbHWLJ6mIU-PrQ8poJ3vws8"
        //  ]
        return Pay::alipay()->query($order);
    }

    /**
     * 查询转账订单
     * @param  string  $out_biz_no  转账订单号
     */
    private function queryOutBatchNo(string $out_biz_no)
    {
        $order = [
            'out_biz_no' => $out_biz_no,
            '_action'    => 'transfer'
        ];

        // [
        //    "code" => "10000"
        //    "msg" => "Success"
        //    "buyer_logon_id" => "api***@sandbox.com"
        //    "buyer_pay_amount" => "0.00"
        //    "buyer_user_type" => "PRIVATE"
        //    "invoice_amount" => "0.00"
        //    "out_trade_no" => "0000000004"
        //    "point_amount" => "0.00"
        //    "receipt_amount" => "0.00"
        //    "send_pay_date" => "2024-09-09 16:54:53"
        //    "total_amount" => "0.01"
        //    "trade_no" => "2024090922001491040503941822"
        //    "trade_status" => "TRADE_CLOSED"
        //    "buyer_open_id" => "0047zfMJwJy0V8dxoSLtYhRqnbHWLJ6mIU-PrQ8poJ3vws8"
        //  ]
        return Pay::alipay()->query($order);
    }

    /**
     * 关闭订单操作
     * @param  string  $out_trade_no
     * @param  string  $_action
     * @return array|MessageInterface|Collection|null
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function close(string $out_trade_no, string $_action = 'web')
    {
        return Pay::alipay()->close([
            'out_trade_no' => $out_trade_no,
             '_action' => $_action // 'web', // 默认值，关闭网页订单
            // '_action' => 'mini', // 关闭小程序订单
            // '_action' => 'scan', // 关闭扫码订单
            // '_action' => 'pos', // 关闭刷卡订单
            // '_action' => 'h5', // 关闭 H5 订单
        ]);
    }
}
