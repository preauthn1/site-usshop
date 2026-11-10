<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\api\ApiIndexCode;
use app\libs\payment\Entry;
use app\model\money\RechargeModel;
use app\model\system\PaymentChannelModel;
use app\validate\RechargeValidate;
use Exception;
use think\App;
use think\Request;
use think\response\Json;

class Recharge extends BaseIndexEncryptController
{

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, RechargeModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 新增充值申请
     * @throws Exception
     */
    public function onSave(Request $request): Json
    {
        $user_id = $this->currentUser['user_id'];
        requestLimit('recharge_'. $user_id);
        $formData = [
            'amount'     => $request->param('amount'),
            'channel_id' => $request->param('channel_id'),
        ];


        $validate = RechargeValidate::class;
        $this->validate($formData, $validate);

        // 获取支付渠道信息
        $payment_channel = PaymentChannelModel::find($formData['channel_id']);
        $formData['channel_indent'] = $payment_channel['ident'];

        $formData['user_id'] = $user_id;
        try {
            $recharge_model = new RechargeModel();
            $order = $recharge_model->addBalance($user_id, $formData['amount']);
            if ($order) {
                $order_data = $recharge_model->getPayInfo('', $order['order_no']);
                $formData['order_id'] = $order_data['pay_no'];
                $pay_info = (new Entry($payment_channel['config']))->create($payment_channel['ident'], $formData);
                return self::returnMsg(ApiIndexCode::SUCCESS, $pay_info);
            }
        } catch (Exception $e) {
            return self::returnMsg(ApiIndexCode::FAIL, [], $e->getMessage());
        }
    }
}
