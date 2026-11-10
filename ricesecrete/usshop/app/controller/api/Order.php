<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\payment\Entry;
use app\libs\Util;
use app\model\goods\GoodsLogisticsModel;
use app\model\order\OrderCommentModel;
use app\model\order\OrderCustomModel;
use app\model\order\OrderGoodsModel;
use app\model\order\OrderModel;
use app\model\order\OrderPayModel;
use app\model\order\OrderTourismModel;
use app\model\system\PaymentChannelModel;
use app\model\tourism\TourismProductModel;
use app\model\tourism\TourismSpecModel;
use app\model\tourism\TourismWorkerModel;
use app\model\user\UserModel;
use think\Request;


/**
 * 订单统一接口
 * Class Order
 * @package app\controller
 */
class Order extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 测试
     * @return \think\response\Json
     */
    public function index()
    {
        return self::returnMsg(1, []);
    }

    /**
     * 获取支付信息
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayInfo(Request $request)
    {
        $order_id = $request->param('order_id', '');
        $pay_type = $request->param('pay_type', '');
        if (empty($order_id) || empty($pay_type)) {
            return self::returnMsg(0, '参数错误');
        }
        $order_model = new OrderModel();
        if (strpos($order_id, ',') != false) {
            $order_id_arr = explode(',', $order_id);
            $temp = $order_model->getPayInfos($order_id_arr, $pay_type);
        } else {
            $temp = $order_model->getPayInfo($order_id, '', $pay_type);
        }
        if ($temp['code'] == 1) {
            // 获取支付渠道信息
            $payment_channel = PaymentChannelModel::where('ident', $pay_type)->find();
            if ($payment_channel['type'] == 1) {
                //线上支付
                $formData['order_id'] = $temp['data']['pay_no'];
                $formData['amount'] = $temp['data']['pay_amount'];
                $pay_info = (new Entry($payment_channel['config']))->create($payment_channel['ident'], $formData);
                $temp['data']['pay_info'] = $pay_info;
            } else {
                $config = [];
                if (is_string($payment_channel['config'])) {
                    $config = json_decode($payment_channel['config'], true);
                }
                //{"number":"1323243443","ewm":"https://usheen.s3.amazonaws.com/ewm/2025-03-10/24f9bb5029c069aeeb9df8b2f88378013ed388c5.jpg"}
                // 线下支付
                $temp['data']['pay_info'] = [
                    'name' => $payment_channel['name'],
                    'ident' => $payment_channel['ident'],
                    'src' => $payment_channel['src'],
                    'number' => $config['number'],
                    'ewm' => $config['ewm'],
                ];
            }
            return self::returnMsg(1, $temp['data']);
        } else {
            return self::returnMsg(0, [], $temp['msg']);
        }
    }

    /**
     * 上传支付凭证
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onUpdatePayImg(Request $request)
    {
        $pay_no = $request->param('pay_no');
        $pay_img = $request->param('pay_img');
        if (empty($pay_no) || empty($pay_img)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        $ret = $order_model->updatePayImg($pay_no, $pay_img);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 使用积分支付
     * @param Request $request
     * @return \think\response\Json
     */
    public function onIntegralPay(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, '参数错误');
        }
        $order_model = new OrderModel();
        if (strpos($order_id, ',') != false) {
            $order_id_arr = explode(',', $order_id);
            $temp = $order_model->getPayInfos($order_id_arr, OrderPayModel::PAY_TYPE_INTEGRAL);
        } else {
            $temp = $order_model->getPayInfo($order_id, '', OrderPayModel::PAY_TYPE_INTEGRAL);
        }
        if ($temp['code'] == 1) {
            $ret = $order_model->integralPay($temp['data']['pay_no'], $this->currentUser['user_id']);
            if ($ret == true) {
                return self::returnMsg(1, $temp['data']);
            } else {
                return self::returnMsg(0);
            }
        } else {
            return self::returnMsg(0, [], $temp['msg']);
        }
    }

    /**
     * 使用余额支付
     * @param Request $request
     * @return \think\response\Json
     */
    public function onBalancePay(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, '参数错误');
        }
        $order_model = new OrderModel();
        if (strpos($order_id, ',') != false) {
            $order_id_arr = explode(',', $order_id);
            $temp = $order_model->getPayInfos($order_id_arr, OrderPayModel::PAY_TYPE_BALANCE);
        } else {
            $temp = $order_model->getPayInfo($order_id, '', OrderPayModel::PAY_TYPE_BALANCE);
        }
        if ($temp['code'] == 1) {
            $ret = $order_model->balancePay($temp['data']['pay_no'], $this->currentUser['user_id']);
            if ($ret == true) {
                return self::returnMsg(1, $temp['data']);
            } else {
                return self::returnMsg(0, [], '余额不足');
            }
        } else {
            return self::returnMsg(0, [], $temp['msg']);
        }
    }

    /**
     * 取消订单（未付款）
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCancelOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $user_id = $this->currentUser['user_id'];
        $map = [
            'user_id' => $user_id,
            'id' => $order_id,
        ];
        $order_data = OrderModel::where($map)->field('type')->find();
        if (empty($order_data)) {
            return self::returnMsg(0, [], '订单不存在');
        }
        $order_model = new OrderModel();
        $ret = $order_model->cancelOrder($order_id, $order_data['type'], $this->currentUser['user_id']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 申请退款
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onApplyRefundOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        $refund_remark = $request->param('refund_remark', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        $ret = $order_model->applyRefundOrder($order_id, $refund_remark, $this->currentUser['user_id']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }


    /**
     * 获取物流列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onGetLogisticsList(Request $request)
    {
        $out_data = GoodsLogisticsModel::where('status', '=', 1)->field('id,name')->select();
        return self::returnMsg(1, $out_data);
    }

    /**
     * 申请退货退款
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onApplyRefundGoodsOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        $refund_remark = $request->param('refund_remark', '');
        $return_explain = $request->param('return_explain', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        $ret = $order_model->applyRefundGoodsOrder($order_id, $refund_remark, $return_explain, $this->currentUser['user_id']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 设置退货物流信息
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onSetRefundLogisticsOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        $logistics_id = $request->param('logistics_id', '');
        $number = $request->param('number', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        $ret = $order_model->setRefundLogisticsOrder($order_id, $logistics_id, $number, $this->currentUser['user_id']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 取消退款申请
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCancelRefundOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        $ret = $order_model->cancelRefundOrder($order_id, $this->currentUser['user_id']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 操作员核销订单
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onVerOrderCode(Request $request)
    {
        $order_id = $request->param('order_id', '');
        $code = $request->param('code', '');
        if (empty($order_id) || empty($code)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $user_id = $this->currentUser['user_id'];
        $bureau_arr = TourismWorkerModel::getWorkerBureauArr($user_id);
        $order_data = OrderModel::where('id', $order_id)->field('merchant_id,type')->find();
        if (in_array($order_data['merchant_id'], $bureau_arr)) {
            $order_model = new OrderModel();
            $ret = $order_model->verOrderCode($order_id, $code, $order_data['type']);
            if ($ret == true) {
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        } else {
            return self::returnMsg(0, [], '无权限操作');
        }
    }

    /**
     * 确认签收订单
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onSignOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $user_id = $this->currentUser['user_id'];
        $map = [
            'user_id' => $user_id,
            'id' => $order_id,
            'status' => OrderGoodsModel::STATUS_SEND,
        ];
        $order_data = OrderModel::where($map)->field('type')->find();
        if (empty($order_data)) {
            return self::returnMsg(0, [], '订单不存在');
        }
        $order_item = OrderGoodsModel::where('order_id', $order_id)->field('ver_code')->find();
        $order_model = new OrderModel();
        $ret = $order_model->verOrderCode($order_id, $order_item['ver_code'], $order_data['type']);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 删除订单
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onDeleteOrder(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $user_id = $this->currentUser['user_id'];
        $map = [
            'user_id' => $user_id,
            'id' => $order_id
        ];
        $order_data = OrderModel::where($map)->whereIn('status', [OrderTourismModel::STATUS_CANCEL, OrderTourismModel::STATUS_REFUND, OrderTourismModel::STATUS_EVALUATED])->field('id,type')->find();
        $order_model = new OrderModel();
        $ret = $order_model->changeOrderStatus($order_data['type'], $order_id, OrderTourismModel::STATUS_DELETE);
        if ($ret == true) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 获取订单列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getOrderList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $keyword = $request->param('keyword', '');//关键字
        //全部订单 1未付款 2待发货 3已发货 4已收货 5退款/售后
        $tab = $request->param('tab', '');//空全部 1未付款 2待发货 3已发货 4已收货 5退款/售后
        $type = $request->param('type', '');//订单类型 1旅游套餐 2正常商品 3积分商品
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['order_no|pay_no|product_name|order_keyword', 'like', '%' . $keyword . '%'];
        }
        $map[] = ['user_id', '=', $this->currentUser['user_id']];
        $map[] = ['status', '<>', OrderGoodsModel::STATUS_DELETE];
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        $map_sql = "";
        switch (intval($tab)) {
            case 1:
                $map[] = ['status', '=', OrderGoodsModel::STATUS_PAYMENT];
                break;
            case 2:
                $map[] = ['status', '=', OrderGoodsModel::STATUS_PAID];
                break;
            case 3:
                $map[] = ['status', '=', OrderGoodsModel::STATUS_SEND];
                break;
            case 4:
                $map[] = ['status', '=', OrderGoodsModel::STATUS_VERIFY];
                break;
            case 5:
                $map[] = ['status', 'in', [OrderGoodsModel::STATUS_REFUNDING, OrderGoodsModel::STATUS_REFUND, OrderGoodsModel::STATUS_REFUSE]];
                break;
        }
        $order_model = new OrderModel();
        //`order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
        //  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
        //  `type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
        // `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
        //  `product_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
        //  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
        //  `product_tag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品标签',
        //  `merchant_id` int DEFAULT '0' COMMENT '商家id',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格属性描述',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单金额',
        //  `pay_amount` decimal(20,2) DEFAULT '0.00' COMMENT '实际支付金额',
        $field = 'id,order_no,pay_no,type,express_type,product_id,product_img,product_name,
        product_tag,merchant_id,spec_id,spec_json,amount,pay_amount,status,create_time,update_time';
        $data = $order_model->getOrderList($map, $page, $limit, 'update_time DESC', $field, $map_sql);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取待核销订单列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderVerList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', OrderModel::TYPE_TOURISM);
        $user_phone = UserModel::getPhone($this->currentUser['user_id']);
        $order_model = new OrderModel();
        $map_sql = "";
        if ($type == OrderModel::TYPE_TOURISM) {
            $map = [
                'type' => $type,
                'serve_mobile' => $user_phone
            ];
        } else {
            $map = [
                'phone' => $user_phone,
            ];
            $bureau_ids = TourismWorkerModel::where($map)->column('bureau_id');
            $map_sql = "(merchant_id in (" . implode(',', $bureau_ids) . "))";
            $map = [
                'type' => $type,
            ];
        }
        $field = 'id,order_no,pay_no,type,express_type,product_id,product_img,product_name,
        product_tag,merchant_id,spec_id,spec_json,amount,pay_amount,status,create_time,update_time';
        $data = $order_model->getOrderList($map, $page, $limit, 'update_time DESC', $field, $map_sql);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取待核销订单列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderVer(Request $request)
    {
        $product_id = $request->param('product_id', '');
        $spec_id = $request->param('spec_id', '');
        if (empty($product_id) || empty($spec_id)) {
            return self::returnMsg(0);
        }
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', OrderModel::TYPE_TOURISM);
        $user_phone = UserModel::getPhone($this->currentUser['user_id']);
        $order_model = new OrderModel();
        $map_sql = "";
        if ($type == OrderModel::TYPE_TOURISM) {
            $map = [
                'type' => $type,
                'serve_mobile' => $user_phone
            ];
        } else {
            $map = [
                'phone' => $user_phone,
            ];
            $bureau_ids = TourismWorkerModel::where($map)->column('bureau_id');
            $map_sql = "(merchant_id in (" . implode(',', $bureau_ids) . "))";
            $map = [
                'type' => $type,
            ];
        }
        $map['product_id'] = $product_id;
        $map['spec_id'] = $spec_id;
        $field = 'id,order_no,pay_no,type,express_type,product_id,product_img,product_name,
        product_tag,merchant_id,spec_id,spec_json,amount,pay_amount,status,create_time,update_time';
        $data = $order_model->getOrderList($map, $page, $limit, 'update_time DESC', $field, $map_sql);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取旅游套餐核销订单详情
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderVerDetail(Request $request)
    {
        $product_id = $request->param('product_id', '');
        $spec_id = $request->param('spec_id', '');
        if (empty($product_id) || empty($spec_id)) {
            return self::returnMsg(0);
        }
        $product_info = TourismProductModel::getProductInfo($product_id, 'id,title,img,city,tag');
        $product_info['city'] = str_replace('市', '', $product_info['city']) . "出发";
        if (!empty($product_info['tag'])) {
            if (strpos($product_info['tag'], "|") >= 0) {
                $product_info['tag_arr'] = explode("|", $product_info['tag']);
            } else {
                $product_info['tag_arr'] = [$product_info['tag']];
            }
        } else {
            $product_info['tag_arr'] = [];
        }
        $spec_info = TourismSpecModel::getSpecInfo($spec_id, 'start_day,days_str');
        $product_info['start_day'] = $spec_info['start_day'];
        $product_info['start_week'] = Util::getDayWeek($spec_info['start_day']);
        $product_info['days_str'] = $spec_info['days_str'];
        return self::returnMsg(1, $product_info);
    }

    /**
     * 获取评论列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getCommentList(Request $request)
    {
        $product_id = $request->param('product_id', '');
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', '');//为空是全部  new最新  goods好评  img有图片
        $map = [];
        $map[] = ['product_id', '=', $product_id];
        $order = "create_time DESC";
        if ($type == 'new') {
            $order = "create_time DESC";
        }
        if ($type == 'goods') {
            $order = "all_score DESC,create_time DESC";
        }
        if ($type == 'img') {
            $map[] = ['images_num', ">", 0];
        }
        $field = "*";
        $tourism_comment_model = new OrderCommentModel();
        $data = $tourism_comment_model->getPageData($map, $page, $limit, $order, $field);
        foreach ($data['dataList'] as $k => $v) {
            if (!empty($v['images'])) {
                $data['dataList'][$k]['images'] = json_decode($v['images'], true);
            } else {
                $data['dataList'][$k]['images'] = [];
            }
            $data['dataList'][$k]['time'] = date('Y-m-d', strtotime($v['update_time']));
            if ($v['anonymous'] == 2) {
                $data['dataList'][$k]['user_name'] = '匿名用户';
            }
        }
        $images_num = $tourism_comment_model->where('product_id', $product_id)->sum('images_num');
        $out_data = [
            'current' => $data['current'],
            'limit' => $data['limit'],
            'total' => $data['total'],
            'img_count' => $images_num,
            'dataList' => $data['dataList']
        ];
        return self::returnMsg(1, $out_data);
    }

    /**
     * 提交评论
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onSubmitComment(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $order_model = new OrderModel();
        //订单状态
        $order_data = $order_model->where('id', $order_id)->field('status,type,product_id')->find();
        if ($order_data['status'] != OrderTourismModel::STATUS_VERIFY) {
            return self::returnMsg(0, [], '请勿重复评论');
        }
        $product_id = $order_data['product_id'];
        $content = $request->param('content', '');
        $anonymous = $request->param('anonymous', 1);
        $all_score = $request->param('all_score', 5);
        $tour_guide_score = $request->param('tour_guide_score', 5);
        $service_score = $request->param('service_score', 5);
        $experience_score = $request->param('experience_score', 5);
        $images = $request->param('images', '');
        if (!empty($images)) {
            $images = json_decode($images, true);
        } else {
            $images = [];
        }
        $up_data = [
            'order_id' => $order_id,
            'product_id' => $product_id,
            'type' => $order_data['type'],
            'user_id' => $this->currentUser['user_id'],
            'content' => $content,
            'images' => $images,
            'anonymous' => $anonymous,
            'all_score' => $all_score,
            'tour_guide_score' => $tour_guide_score,
            'service_score' => $service_score,
            'experience_score' => $experience_score,
        ];
        $ret = OrderCommentModel::addCommentLog($up_data);
        if ($ret) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 获取定制列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getCustomList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $user_id = $this->currentUser['user_id'];
        $order_custom_model = new OrderCustomModel();
        $data = $order_custom_model->getCustomList($user_id, $page, $limit);
        return self::returnMsg(1, $data);
    }

    /**
     * 添加/修改定制旅游套餐
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onAddCustom(Request $request)
    {
        $order_custom_model = new OrderCustomModel();
        $data = [
            'id' => $request->param('id'),
            'user_id' => $this->currentUser['user_id'],//用户id
            'name' => $request->param('name'),//姓名
            'phone' => $request->param('phone'),//手机号
            'enterprise' => $request->param('enterprise'),//企业名称
            'type' => $request->param('type'),//定制类型
            'address' => $request->param('address'),//出发地址
            'destination' => $request->param('destination'),//目的地
            'start_day' => $request->param('start_day'),//出发时间
            'number' => $request->param('number', 0),//人数
            'budget' => $request->param('budget', 0),//人均预算
            'remark' => $request->param('remark')//备注信息
        ];
        $out_data = $order_custom_model->addCustom($data);
        return self::returnMsg($out_data);
    }

    /**
     * 获取定制套餐信息
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getCustomDetail(Request $request)
    {
        $id = $request->param('id', 0);
        $order_custom_model = new OrderCustomModel();
        $data = $order_custom_model->getCustomInfo($id, $this->currentUser['user_id']);
        if (empty($data)) {
            return self::returnMsg(0);
        }
        return self::returnMsg(1, $data);
    }

    /**
     * 取消定制套餐
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function onCustomCancel(Request $request)
    {
        $id = $request->param('id', 0);
        $order_custom_model = new OrderCustomModel();
        $ret = $order_custom_model->cancelCustom($id, $this->currentUser['user_id']);
        if ($ret == false) {
            return self::returnMsg(0);
        }
        return self::returnMsg(1);
    }

    /**
     * 删除定制套餐
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function onCustomDelete(Request $request)
    {
        $id = $request->param('id', 0);
        $order_custom_model = new OrderCustomModel();
        $ret = $order_custom_model->deleteCustom($id, $this->currentUser['user_id']);
        if ($ret == false) {
            return self::returnMsg(0);
        }
        return self::returnMsg(1);
    }


}
