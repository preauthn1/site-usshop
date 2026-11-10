<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\payment\Entry;
use app\model\order\OrderModel;
use app\model\order\OrderPayModel;
use app\model\system\PaymentChannelModel;
use think\App;
use think\facade\Config;
use think\response\Json;
use Yansongda\Pay\Pay;

class PayNotify extends BaseIndexEncryptController
{
    protected $config;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->config = Config::get('pay');
        Pay::config($this->config);
    }

    public function alipay()
    {
        $result = Pay::alipay()->callback();
        // TODO 处理回调逻辑
        if ($result['trade_status'] == 'TRADE_SUCCESS') {
            $order_model = new OrderModel();
            $pay_sn = $result['out_trade_no'];
            $total_amount = $result['total_amount'];
            $ret = $order_model->endPayOrder($pay_sn, floatval($total_amount), OrderPayModel::PAY_TYPE_ALIPAY);
            if ($ret) {
                // 确认回调
                return Pay::alipay()->success();
            }
        }
        return self::returnMsg(0);
    }

    public function wechat()
    {
        $result = Pay::wechat()->callback();
        // TODO 处理回调逻辑
        if ($result['trade_status'] == 'TRADE_SUCCESS') {
            $order_model = new OrderModel();
            $pay_sn = $result['out_trade_no'];
            $total_amount = fen2yuan($result['total_amount']);
            $ret = $order_model->endPayOrder($pay_sn, $total_amount, OrderPayModel::PAY_TYPE_WEIXIN);
            if ($ret) {
                // 确认回调
                return Pay::alipay()->success();
            }
        }
        // 确认回调
        return Pay::wechat()->success();

    }

    public function rechargeNotify($ident): Json
    {
        // 查询支付渠道信息
        $payment_channel = PaymentChannelModel::where('ident', $ident)->find();
        if (empty($payment_channel)) {
            return self::returnMsg(0, '支付渠道不存在');
        }
        $res = (new Entry($payment_channel['config']))->notify($payment_channel['ident'], $this->request->param());
        return json($res);
    }

    function tksort(&$array)
    {
        ksort($array);
        foreach(array_keys($array) as $k)
        {
            if(gettype($array[$k])=="array")
            {
                tksort($array[$k]);
            }
        }
    }
    function check_ipn_request_is_valid()
    {
        $error_msg = "Unknown error";
        $auth_ok = false;
        $request_data = null;
        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
            $request_json = file_get_contents('php://input');
            $request_data = json_decode($request_json, true);
            $this->tksort($request_data);
            $sorted_request_json = json_encode($request_data, JSON_UNESCAPED_SLASHES);
            if ($request_json !== false && !empty($request_json)) {
                $hmac = hash_hmac("sha512", $sorted_request_json, trim($this->ipn_secret));
                if ($hmac == $recived_hmac) {
                    $auth_ok = true;
                } else {
                    $error_msg = 'HMAC signature does not match';
                }
            } else {
                $error_msg = 'Error reading POST data';
            }
        } else {
            $error_msg = 'No HMAC signature sent.';
        }
        return $auth_ok;
    }
}
