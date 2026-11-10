<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\base\BaseModel;
use app\libs\api\ApiIndexCode;
use app\model\system\PaymentChannelModel;
use app\transform\PaymentChannelTransform;
use think\App;
use think\db\exception\DbException;
use think\response\Json;

class PaymentChannel extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, PaymentChannelModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 渠道列表
     * @throws DbException
     */
    public function index(PaymentChannelTransform $transform): Json
    {
        $type = $this->request->param('type', '');
        if ($type === 'recharge') {
            $map[] = ['pay_status', '=', BaseModel::STATUS_ENABLE];
            $map[] = ['ident', '!=', 'balance'];
            $map[] = ['ident', '!=', 'proxy'];
        }
        if ($type === 'withdrawal') {
            $map[] = ['withdrawal_status', '=', BaseModel::STATUS_ENABLE];
        }
        $map[] = ['status', '=', BaseModel::STATUS_ENABLE];

        $payment_channel = $this->model->getAll($map);
        $data = $transform->transformCollection($payment_channel);
        return self::returnMsg(ApiIndexCode::SUCCESS, $data);
    }
}
