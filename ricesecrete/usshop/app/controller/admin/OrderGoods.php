<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\Util;
use app\model\goods\GoodsLogisticsModel;
use app\model\goods\GoodsProductModel;
use app\model\order\OrderDamagesModel;
use app\model\order\OrderGoodsModel;
use app\model\order\OrderHandleLogModel;
use app\model\order\OrderModel;
use app\model\order\OrderTourismModel;
use app\model\tourism\TourismBureauModel;
use app\model\user\UserModel;
use think\App;
use think\Request;

/**
 * 订单管理
 * Order
 * Class OrderGoods
 * @package app\controller
 */
class OrderGoods extends BaseEncryptController
{
    public function __construct(App $app, OrderModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     *分页列表
     * @param int $type
     * @param string $status
     * @param string $order_no
     * @param string $pay_no
     * @param string $keyword
     * @param array $time_section
     * @param string $start_day
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage(Request $request,  $type = OrderModel::TYPE_TOURISM, $status = '', $order_no = '', $pay_no = '', $keyword = '', $time_section = [], $start_day = '', $page = 1, $limit = 10, $init = '')
    {
        $email = $request->param('email', '');
        if (!empty($init)) {
            $statusList = OrderGoodsModel::$STATUS_MAP;
            $out_data['statusList'] = $statusList;
            $out_data['logisticsList'] = GoodsLogisticsModel::where('status', '=', 1)->field('id,name')->select();
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
        if (!empty($start_day)) {
            $map[] = ['start_day', '=', $start_day];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        if (!empty($order_no)) {
            $map[] = ['order_no', 'like', '%' . trim($order_no) . '%'];
        }
        if (!empty($pay_no)) {
            $map[] = ['pay_no', 'like', '%' . trim($pay_no) . '%'];
        }
        if (!empty($keyword)) {
            $map[] = ['order_keyword|city', 'like', '%' . trim($keyword) . '%'];
        }
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map['merchant_id'] = $bureau_id;
        }
        if (!empty($email)) {
            $user = (new UserModel())->getInfoMap(['email' => $email]);
            if ($user) {
                $map['user_id'] = $user['id'];
            } else {
                $map['user_id'] = '-1';
            }
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
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo($id)
    {
        $map = [];
        $map['id'] = $id;
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map['merchant_id'] = $bureau_id;
        }
        $data = $this->model->getInfoMap($map);
        if (!empty($data)) {
            if ($data['type'] == OrderModel::TYPE_TOURISM) {
                $order_item = OrderTourismModel::where('order_id', $id)->find();
                $data['serve_json'] = json_decode($data['serve_json'], true);
                $data['merchant_name'] = TourismBureauModel::getBureauName($data['merchant_id']);
            } else {
                $order_item = OrderGoodsModel::where('order_id', $id)->find();
            }
            if (!empty($order_item['spec_json'])) {
                $order_item['spec_json'] = json_decode($order_item['spec_json'], true);
            } else {
                $order_item['spec_json'] = [];
            }
            if (!empty($order_item['contacts'])) {
                $order_item['contacts'] = json_decode($order_item['contacts'], true);
            } else {
                $order_item['contacts'] = [];
            }
            $order_item['pay_time'] = $order_item['pay_time'] ? date('Y-m-d H:i:s', $order_item['pay_time']) : '--';
            $order_item['ver_time'] = $order_item['ver_time'] ? date('Y-m-d H:i:s', $order_item['ver_time']) : '--';
            $order_item['evaluate_time'] = $order_item['evaluate_time'] ? date('Y-m-d H:i:s', $order_item['evaluate_time']) : '--';
            $order_item['close_time'] = $order_item['close_time'] ? date('Y-m-d H:i:s', $order_item['close_time']) : '--';
            $order_item['refund_apply_time'] = $order_item['refund_apply_time'] ? date('Y-m-d H:i:s', $order_item['refund_apply_time']) : '--';
            $order_item['refund_time'] = $order_item['refund_time'] ? date('Y-m-d H:i:s', $order_item['refund_time']) : '--';
            $data['handle_log'] = OrderHandleLogModel::getOrderHandleLog($id, $data['type']);
            if ($data['type'] == OrderModel::TYPE_TOURISM) {
                $order_item['affirm_time'] = $order_item['affirm_time'] ? date('Y-m-d H:i:s', $order_item['affirm_time']) : '--';
                $days = Util::getDays(date("Y-m-d"), $data['start_day']);
                $temp_refund_amount = $data['pay_amount'] - OrderDamagesModel::getRateAmount($days, $data['pay_amount'], $data['is_holiday']);
                $data['temp_refund_amount'] = $temp_refund_amount;
                $data['days'] = $days;
                $data['order_damages'] = OrderDamagesModel::getListAttr($data['is_holiday']);
                $order_item['status_step'] = $this->model->_getTourismStatusStep($order_item['status'], $order_item);
            } else {
                $order_item['send_time'] = $order_item['send_time'] ? date('Y-m-d H:i:s', $order_item['send_time']) : '--';
                $order_item['refund_ok_time'] = $order_item['refund_ok_time'] ? date('Y-m-d H:i:s', $order_item['refund_ok_time']) : '--';
                $order_item['refund_sales_time'] = $order_item['refund_sales_time'] ? date('Y-m-d H:i:s', $order_item['refund_sales_time']) : '--';
                $order_item['refund_ing_time'] = $order_item['refund_ing_time'] ? date('Y-m-d H:i:s', $order_item['refund_ing_time']) : '--';
                $order_item['refuse_time'] = $order_item['refuse_time'] ? date('Y-m-d H:i:s', $order_item['refuse_time']) : '--';
                if (!empty($order_item['logistics'])) {
                    $order_item['logistics'] = json_decode($order_item['logistics'], true);
                } else {
                    $order_item['logistics'] = [];
                }
                if (!empty($order_item['refund_logistics'])) {
                    $order_item['refund_logistics'] = json_decode($order_item['refund_logistics'], true);
                } else {
                    $order_item['refund_logistics'] = [];
                }
                //到店自提地址
                $order_item['goods_address'] = GoodsProductModel::getProductInfo($data['product_id'], 'province,city,county,address,lng,lat');
                $order_item['status_step'] = $this->model->_getGoodsStatusStep($order_item['status'], $order_item);
            }
            $data['order_item'] = $order_item;
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
        $formData = [
            'id' => $request->param('id'),
            'operation' => $request->param('operation'),
            'amount' => $request->param('amount'),
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\OrderValidate.add';
        } else {
            $validate = 'app\validate\OrderValidate';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $map = [];
            $map['id'] = $formData['id'];
            $bureau_id = $this->currentUser['bureau_id'];
            if (!empty($bureau_id)) {
                $map['merchant_id'] = $bureau_id;
            }
            $data = $this->model->getInfoMap($map);
            if (empty($data)) {
                return self::returnMsg(0);
            }
            $ret = false;
            if ($formData['operation'] == 'ver') {
                if ($data['type'] == OrderModel::TYPE_TOURISM) {
                    $order_data = OrderTourismModel::where('order_id', $formData['id'])->field('ver_code')->find();
                    $content = "核销成功";
                } else {
                    $order_data = OrderGoodsModel::where('order_id', $formData['id'])->field('ver_code')->find();
                    $content = "签收成功";
                }
                //核销验证码
                $ret = $this->model->verOrderCode($formData['id'], $order_data['ver_code'], $data['type']);
                if ($ret) {
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], OrderHandleLogModel::TYPE_VERIFY, $content, $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'send') {
                $logistics_id = $request->param('logistics_id');
                $number = $request->param('number');
                if (empty($logistics_id) || empty($number)) {
                    return self::returnMsg(0, '', '请填写物流信息');
                }
                //订单发货
                $ret = $this->model->sendOrder($formData['id'], $logistics_id, $number);
                if ($ret) {
                    $logistics_name = GoodsLogisticsModel::getLogisticsIdName($logistics_id);
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], OrderHandleLogModel::TYPE_SEND, "物流公司:" . $logistics_name . ', 发货单号:' . $number, $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'mod') {
                //修改订单价格
                $ret = $this->model->modOrderAmount($formData['id'], $formData['amount']);
                if ($ret) {
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], OrderHandleLogModel::TYPE_MODIFY_AMOUNT, '修改金额：' . $formData['amount'], $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'refund') {
                $check = $request->param('check');
                $ret = $this->model->endRefundOrder(OrderModel::TYPE_TOURISM, $formData['id'], $check, $formData['amount']);
                if ($ret) {
                    $content = $check ? '退款成功' . " 退款金额: " . $formData['amount'] : '拒绝退款';
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], OrderHandleLogModel::TYPE_REFUND, $content, $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'refund_goods') {
                $ret = $this->model->endGoodsRefundOrder($formData['id'], $formData['amount']);
                if ($ret) {
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], OrderHandleLogModel::TYPE_REFUND, '退款成功', $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'refund_send') {
                $check = $request->param('check');
                $ret = $this->model->okSendGoodsRefundOrder($formData['id'], $formData['amount'], $check);
                if ($ret) {
                    $content = $check ? '同意退货退款' . " 退款金额: " . $formData['amount'] : '拒绝退款';
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], $check ? OrderHandleLogModel::TYPE_AGREE_REFUND : OrderHandleLogModel::TYPE_REFUSE_REFUND, $content, $this->currentUser['user_id']);
                }
            }
            if ($formData['operation'] == 'refund_end') {
                $check = $request->param('check');
                $ret = $this->model->endSendGoodsRefundOrder($formData['id'], $formData['amount'], $check);
                if ($ret) {
                    $content = $check ? '同意退货退款' . " 退款金额: " . $formData['amount'] : '拒绝退款';
                    //操作记录
                    OrderHandleLogModel::addOrderHandleLog($formData['id'], $check ? OrderHandleLogModel::TYPE_AGREE_REFUND_RETURN : OrderHandleLogModel::TYPE_REFUND_RETURN, $content, $this->currentUser['user_id']);
                }
            }
            if (false !== $ret) {
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $map = [];
        $map['id'] = $id;
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map['merchant_id'] = $bureau_id;
        }
        $data = $this->model->getInfoMap($map);
        if (empty($data)) {
            return self::returnMsg(1002);
        }
        $flag = $this->model->delOrder($id);
        if (false !== $flag) {
            //操作记录
            OrderHandleLogModel::addOrderHandleLog($id, OrderHandleLogModel::TYPE_DELETE, '删除订单', $this->currentUser['user_id']);
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
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $map = [];
        $map['id'] = $formData['id'];
        $bureau_id = $this->currentUser['bureau_id'];
        if (!empty($bureau_id)) {
            $map['merchant_id'] = $bureau_id;
        }
        $order_data = $this->model->getInfoMap($map);
        if (empty($order_data)) {
            return self::returnMsg(0);
        }
        if ($order_data) {
            $type = $order_data['type'];
            $this->model->startTrans();
            $ret = $this->model->changeOrderStatus($type, $formData['id'], $formData['status']);
            if ($ret) {
                $type_log = '';
                $content = '';
                if ($type == OrderModel::TYPE_TOURISM) {
                    if ($formData['status'] == OrderTourismModel::STATUS_PAYMENT) {
                        $type_log = OrderHandleLogModel::TYPE_AUDIT;
                        $content = "审核订单";
                        $serve_mobile = $request->param('mobile');
                        if (!empty($serve_mobile)) {
                            //添加服务人员（核销接待人员）
                            $ret = OrderTourismModel::addServeMobile($formData['id'], $serve_mobile);
                            if ($ret == false) {
                                $this->model->rollback();
                                return self::returnMsg(0);
                            }
                        } else {
                            $this->model->rollback();
                            return self::returnMsg(0);
                        }
                    } elseif ($formData['status'] == OrderTourismModel::STATUS_PAID) {
                        $type_log = OrderHandleLogModel::TYPE_PAY;
                        $content = "后台付款";
                    }
                } else {
                    if ($formData['status'] == OrderGoodsModel::STATUS_PAID) {
                        $type_log = OrderHandleLogModel::TYPE_PAY;
                        $content = "后台付款";
                    }
                }
                if ($formData['status'] == OrderTourismModel::STATUS_CANCEL) {
                    $type_log = OrderHandleLogModel::TYPE_CANCEL;
                    $content = "取消订单";
                }
                $this->model->commit();
                //操作记录
                OrderHandleLogModel::addOrderHandleLog($formData['id'], $type_log, $content, $this->currentUser['user_id']);
                return self::returnMsg(1);
            }
            $this->model->rollback();
            return self::returnMsg(0);
        }
        return self::returnMsg(0);
    }
}
