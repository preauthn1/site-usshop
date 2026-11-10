<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\money\WithdrawalModel;
use app\model\system\PaymentChannelModel;
use app\repository\MoneyLogRepository;
use app\transform\WithdrawalTransform;
use app\validate\WithdrawalValidate;
use Exception;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;

class Withdrawal extends BaseIndexEncryptController
{

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, WithdrawalModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 银行卡列表
     * @throws DbException
     */
    public function index(Request $request, WithdrawalTransform $transform): Json
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $start_time = $this->request->param('start_time');
        $end_time = $this->request->param('end_time');
        $channel_indent = $this->request->param('channel_indent');

        $map = [];
        $map[] = ['user_id', '=', $this->currentUser['user_id']];

        if (!empty($channel_indent)) {
            $map[] = ['channel_indent', '=', $channel_indent];
        }
        if ($start_time) {
            $map[] = ['create_time', '>=', strtotime($start_time)];
        }
        if ($end_time) {
            $map[] = ['create_time', '<=', strtotime($end_time)];
        }


        $withdrawals = $this->model->getPageList($map, 'id desc', $page, $limit);
        $data = $transform->transformPages($withdrawals);
        return self::returnMsg(1, $data);
    }

    /**
     * 新增提现申请
     * @throws Exception
     */
    public function onSave(Request $request): Json
    {
        $user_id = $this->currentUser['user_id'];
        requestLimit('withdrawal_'. $user_id);
        $formData = [
            'original_amount' => $request->param('original_amount'),
            'channel_id'      => $request->param('channel_id'),
//            'user_bank_id'    => $request->param('user_bank_id', 0),
            'address'         => $request->param('address', '')
        ];


        $validate = WithdrawalValidate::class;
        $this->validate($formData, $validate);

        // 获取支付渠道信息
        $payment_channel = PaymentChannelModel::find($formData['channel_id']);
        if (!$payment_channel) {
            return self::returnMsg(0);
        }
        $formData['channel_indent'] = $payment_channel['ident'];
        $formData['user_id'] = $user_id;

        // 操作余额
        $money_log = new MoneyLogRepository($user_id, $formData['original_amount']);
        $res = $money_log->balanceWithdraw();

        if ($res['code'] !== 1) {
            return self::returnMsg($res['code']);
        }

        $formData['card_no'] = $formData['address'];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
