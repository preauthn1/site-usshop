<?php

namespace app\libs;


use Psr\Http\Message\MessageInterface;
use think\facade\Config;
use Yansongda\Pay\Exception\ContainerException;
use Yansongda\Pay\Exception\InvalidParamsException;
use Yansongda\Pay\Exception\ServiceNotFoundException;
use Yansongda\Pay\Pay;
use Yansongda\Supports\Collection;

class WechatPay
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
     * app支付
     * @param $description string 商品描述
     * @param $out_trade_no string 商户系统内部订单号，只能是数字、大小写字母_-*且在同一个商户号下唯一。
     * @param $total_amount float 订单总金额，单位为元。
     * @return Collection
     */
    public function app(string $description, string $out_trade_no, float $total_amount): Collection
    {
        $total_amount = yuan2fen($total_amount);
        $order = [
            'out_trade_no' => $out_trade_no,
            'description'  => $description,
            'amount'       => [
                'total' => $total_amount,
            ],
        ];

        // 将返回 Collection 实例，供后续 APP 调用，调用方式不在本文档讨论范围内，请参考官方文档。
        return Pay::wechat()->app($order);
    }

    /**
     * h5支付
     * @param $description string 商品描述
     * @param $out_trade_no string 商户系统内部订单号，只能是数字、大小写字母_-*且在同一个商户号下唯一。
     * @param $total_amount float 订单总金额，单位为元。
     * @return mixed
     * @throws ContainerException
     */
    public function h5(string $description, string $out_trade_no, float $total_amount)
    {
        $total_amount = yuan2fen($total_amount);
        $order = [
            'out_trade_no' => $out_trade_no,
            'description'  => $description,
            'amount'       => [
                'total' => $total_amount,
            ],
            'scene_info'   => [
                'payer_client_ip' => '1.2.4.8',
                'h5_info'         => [
                    'type' => 'Wap',
                ]
            ],
        ];

        return Pay::wechat()->h5($order);
        // $result->h5_url;
    }

    /**
     * 公众号支付
     * @param $description string 商品描述
     * @param $out_trade_no string 商户系统内部订单号，只能是数字、大小写字母_-*且在同一个商户号下唯一。
     * @param $total_amount float 订单总金额，单位为元。
     * @return void
     */
    public function mp(string $description, string $out_trade_no, float $total_amount)
    {
        $total_amount = yuan2fen($total_amount);
        $order = [
            'out_trade_no' => $out_trade_no,
            'description'  => $description,
            'amount'       => [
                'total' => $total_amount,
            ],
            'payer'        => [
                'openid' => 'onkVf1FjWS5SBxxxxxxxx',
            ],
        ];

        $result = Pay::wechat()->mp($order);
    }

    /**
     * 发起退款
     * @param $out_trade_no string 商户系统内部订单号，原支付交易对应的商户订单号
     * @param $out_refund_no string 商户系统内部的退款单号，商户系统内部唯一，同一退款单号多次请求只退一笔。
     * @param $total_amount float 订单总金额。
     * @param $_action string 退款方式
     * @param $reason string 退款原因
     * @return array|Collection|MessageInterface|null
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function refund(
        string $out_trade_no,
        string $out_refund_no,
        float $total_amount,
        string $_action = 'app',
        string $reason = ''
    ) {
        $total_amount = yuan2fen($total_amount);
        $order = [
            'out_trade_no'  => $out_trade_no,
            'out_refund_no' => $out_refund_no,
            'amount'        => [
                'refund'   => $total_amount,
                'total'    => $total_amount,
                'currency' => 'CNY',
            ],
            'reason'        => $reason,
            // '_action' => 'jsapi', // jsapi 退款，默认
            '_action'       => $_action, // app 退款
            // '_action' => 'combine', // 合单退款
            // '_action' => 'h5', // h5 退款
            // '_action' => 'mini', // 小程序退款
            // '_action' => 'native', // native 退款

        ];

        return Pay::wechat()->refund($order);
    }

    /**
     * 查询普通支付订单
     * @param  string  $out_trade_no  商户订单号 订单支付时传入的商户订单号
     * @param  string  $_action
     * @return mixed
     */
    public function queryOutTradeNo(string $out_trade_no, string $_action = 'jsapi')
    {
        $order = [
            'out_trade_no' => $out_trade_no,
            '_action'      => $_action // 'jsapi', // 默认为 jsapi
            // '_action' => 'app', // 查询 App 支付
            // '_action' => 'h5', // 查询 H5 支付
            // '_action' => 'mini', // 查询小程序支付
            // '_action' => 'native', // 查询 Native 支付
            // '_action' => 'combine', // 查询合单支付

        ];

        return Pay::wechat()->query($order);
    }

    /**
     * 查询退款订单
     * @param  string  $transaction_id  微信支付交易订单号
     * @param  string  $_action
     * @return mixed
     */
    public function queryOutRequestNo(string $transaction_id, string $_action = 'refund')
    {
        $order = [
            'transaction_id' => $transaction_id,
            '_action'        => $_action //'refund',
            // '_action' => 'refund_jsapi', // 查询 jsapi 退款订单，默认
            // '_action' => 'refund_app', // 查询 App 退款订单
            // '_action' => 'refund_h5', // 查询 H5 退款订单
            // '_action' => 'refund_mini', // 查询小程序退款订单
            // '_action' => 'refund_native', // 查询 Native 退款订单
            // '_action' => 'refund_combine', // 查询合单退款订单
        ];

        return Pay::wechat()->query($order);
    }

    /**
     * 查询转账订单
     * @param  string  $out_batch_no  户系统内部区分转账批次单下不同转账明细单的唯一标识
     * @param  string  $out_detail_no  商户系统内部的商家批次单号，在商户系统内部唯一
     * @return mixed
     */
    private function queryOutBatchNo(string $out_batch_no, string $out_detail_no)
    {
        $order = [
            'out_batch_no'  => $out_batch_no,
            'out_detail_no' => $out_detail_no,
            '_action'       => 'transfer'
        ];

        return Pay::wechat()->query($order);
    }

    /**
     * 查询合单支付订单
     * @param  string  $combine_out_trade_no  合单支付总订单号，要求32个字符内，只能是数字、大小写字母_-|* ，且在同一个商户号下唯一 。
     * @return mixed
     */
    private function queryCombineOutTradeNo(string $combine_out_trade_no)
    {
        $order = [
            'combine_out_trade_no' => $combine_out_trade_no,
        ];

        return Pay::wechat()->query($order);
    }

    /**
     * 关闭普通订单操作
     * @param  string  $out_trade_no
     * @param  string  $_action
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function closeOutTradeNo(string $out_trade_no, string $_action = 'jsapi')
    {
        Pay::wechat()->close([
            'out_trade_no' => $out_trade_no,
            '_action' => $_action // 'jsapi', // jsapi 关单，默认
            // '_action' => 'app', // app 关单
            // '_action' => 'combine', // 合单关单
            // '_action' => 'h5', // h5 关单
            // '_action' => 'miniapp', // 小程序关单
            // '_action' => 'native', // native 关单
        ]);
    }

    /**
     * 关闭合单操作
     * @param  string  $combine_out_trade_no  合单商户订单号。
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function closeCombineOutTradeNo(string $combine_out_trade_no)
    {
        Pay::wechat()->close([
            'combine_out_trade_no' => $combine_out_trade_no,
        ]);
    }
}
