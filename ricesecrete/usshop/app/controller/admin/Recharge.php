<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\money\RechargeModel;
use app\model\order\OrderPayModel;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 充值订单
 * Recharge
 * Class Recharge
 * @package app\controller
 */
class Recharge extends BaseEncryptController
{
    public function __construct(App $app, RechargeModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param  Request  $request
     * @param  int  $page
     * @param  int  $limit
     * @param  string  $init
     * @return Json
     */
    public function getPage(Request $request, $page = 1, $limit = 10, $init = '')
    {
        $status = $request->param('status', '');
        $keyword = $request->param('keyword', '');
        $create_time = $request->param('create_time', []);
        $pay_time = $request->param('pay_time', []);
        $user_id = $request->param('user_id', '');
//        $email = $request->param('email', '');

        if (!empty($init)) {
            $statusList = OrderPayModel::$STATUS_MAP;
            $out_data['statusList'] = $statusList;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (is_numeric($status)) {
            $map[] = ['status', '=', $status];
        }
        if (is_numeric($user_id)) {
            $map[] = ['user_id', '=', $user_id];
        }
        if (!empty($create_time)) {
            $map[] = ['create_time', '>=', strtotime($create_time[0])];
            $map[] = ['create_time', '<=', strtotime($create_time[1])];
        }
        if (!empty($pay_time)) {
            $map[] = ['pay_time', '>=', strtotime($pay_time[0])];
            $map[] = ['pay_time', '<=', strtotime($pay_time[1])];
        }
        if (!empty($keyword)) {
            $map[] = ['order_no|pay_no', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $dataList
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $data = $this->model->getInfo($id);
        if (!empty($data)) {
            return self::returnMsg(1, $data);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存
     * @param Request $request
     * @return \think\response\Json
     */
    public function onSave(Request $request)
    {
        $user_id = $request->param('user_id', '');
        $amount = $request->param('amount', 0);
        $id = $this->model->addBalance($user_id, $amount);
        if (false !== $id) {
            $this->model->endPay($id, '', $amount, 'Platform');
        }
        return self::returnMsg(1);
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function onStatus(Request $request)
    {
        $id = $request->param('id');
        $status = $request->param('status');
        $flag = $this->model::where('id', $id)->update(['status' => $status]);
        if (false !== $flag) {
            $temp = $this->model::where('id', $id)->find();
            if ($status == RechargeModel::STATUS_PAID) {
                $this->model->endPay($id, '', $temp['amount'], 'Platform');
            }
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }
}
