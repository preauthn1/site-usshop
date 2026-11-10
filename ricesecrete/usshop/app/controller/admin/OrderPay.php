<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\order\OrderPayModel;
use think\App;
use think\Request;

/**
 * 订单支付信息
 * OrderPay
 * Class OrderPay
 * @package app\controller
 */
class OrderPay extends BaseEncryptController
{
    public function __construct(App $app, OrderPayModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 分页列表
     * @param int $type
     * @param string $status
     * @param string $keyword
     * @param array $time_section
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($status = '', $keyword = '', $time_section = [], $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            $statusList = OrderPayModel::$STATUS_MAP;
            $out_data['statusList'] = $statusList;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (is_numeric($status)) {
            $map[] = ['status', '=', $status];
        }
        if (!empty($time_section)) {
            $map[] = ['create_time', '>=', strtotime($time_section[0])];
            $map[] = ['create_time', '<=', strtotime($time_section[1])];
        }
        if (!empty($keyword)) {
            $map[] = ['order_no|pay_no', 'like', '%' . trim($keyword) . '%'];
        }
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map['merchant_id'] = $bureau_id;
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
        return self::returnMsg(1);
    }
}
