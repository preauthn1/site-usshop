<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\api\ApiIndexCode;
use app\libs\Util;
use app\model\goods\GoodsBuyLogModel;
use app\model\goods\GoodsCartModel;
use app\model\goods\GoodsLangModel;
use app\model\goods\GoodsLogisticsModel;
use app\model\goods\GoodsProductModel;
use app\model\goods\GoodsSpecModel;
use app\model\goods\GoodsStoreModel;
use app\model\system\PaymentChannelModel;
use app\model\system\SysConfigModel;
use app\model\tourism\TourismBureauModel;
use app\model\tourism\TourismBuyLogModel;
use app\model\tourism\TourismProductModel;
use app\model\tourism\TourismSpecModel;
use app\model\user\UserModel;
use app\repository\MoneyLogRepository;
use think\model\concern\SoftDelete;
use think\model\relation\BelongsTo;

/**
 * 模型-OrderModel
 * Class OrderModel
 * @package app\model
 */
class OrderModel extends BaseModel
{
    //订单类型 1旅游套餐 2正常商品 3积分商品
    const TYPE_TOURISM = 1;//旅游套餐
    const TYPE_NORMAL = 2;//正常商品
    const TYPE_INTEGRAL = 3;//积分商品

    static $TYPE_MAP = [
        self::TYPE_TOURISM => "旅游套餐",
        self::TYPE_NORMAL => "正常商品",
        self::TYPE_INTEGRAL => "积分商品",
    ];

    use SoftDelete;

    protected $name = 'order';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'update_time DESC', $field = '*')
    {
        $dataList = $this
            ->withSearch(['team'], [
                'team' => 'user_id'
            ])
            ->with('user')
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['spec_json'] = json_decode($item['spec_json'], true);
            $item['status_str'] = $this->getOrderStatus($item['status'], $item['type']);
            if ($item['type'] != self::TYPE_TOURISM) {
                $item['contacts'] = OrderGoodsModel::getOrderContacts($item['id']);
                //到店自提地址
                $item['goods_address'] = GoodsProductModel::getProductInfo($item['product_id'], 'province,city,county,address,lng,lat');
            }
            if ($item['type'] == self::TYPE_TOURISM) {
                $item['contacts'] = OrderTourismModel::getOrderContacts($item['id']);
                $item['serve_json'] = json_decode($item['serve_json'], true);
                $days = Util::getDays(date("Y-m-d"), $item['start_day']);
                $temp_refund_amount = $item['pay_amount'] - OrderDamagesModel::getRateAmount($days, $item['pay_amount'], $item['is_holiday']);
                $item['temp_refund_amount'] = $temp_refund_amount;
                $item['days'] = $days;
                $item['order_damages'] = OrderDamagesModel::getListAttr($item['is_holiday']);
            }
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取订单列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getOrderList($map, $current_page, $list_rows, $order = 'id DESC', $field = '*', $map_sql = '')
    {
        if (empty($map_sql)) {
            $dataList = $this
                ->field($field)
                ->where($map)
                ->order($order)
                ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        } else {
            $dataList = $this
                ->field($field)
                ->where($map)
                ->where($map_sql)
                ->order($order)
                ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        }
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $spec_json = json_decode($item['spec_json'], true);
            if ($item['type'] == self::TYPE_TOURISM) {
                $spec_json['start_day_str'] = str_replace('-', '/', $spec_json['start_day']);
                $spec_json['end_day_str'] = str_replace('-', '/', $spec_json['end_day']);
//                $item['ver_code'] = OrderTourismModel::where('order_id', $item['id'])->value('ver_code');
            } else {
//                $item['ver_code'] = OrderGoodsModel::where('order_id', $item['id'])->value('ver_code');
            }
            if (!empty($spec_json['spec'])) {
                $spec_arr = explode(';', $spec_json['spec']);
                $spec_arr_new = [];
                foreach ($spec_arr as $k1 => $v1) {
                    $spec_arr_ = explode('|', $v1);
                    $spec_arr_s = [];
                    foreach ($spec_arr_ as $k2 => $v2) {
                        if (isChinese($v2)) {
                            $spec_arr_s[] = lang($v2);
                        } else {
                            $spec_arr_s[] = $v2;
                        }
                    }
                    $spec_arr_new[] = implode('|', $spec_arr_s);
                }
                $spec_json['spec'] = implode(';', $spec_arr_new);
            }

            $item['spec_json'] = $spec_json;
            $item['amount'] = Util::getAmountFormat($item['amount']);

        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 用户创建订单
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addOrder($data)
    {
//        $data = [
//            'type' => 1,//旅游套餐
//            'product_id' => 52,//测试
//            'user_id' => 15,//测试
//            'spec_id' => 54,//套餐id
//            'number' => 1,//成人数量
//            'kid_number' => 1,//儿童数量,
//            'contacts' => [
//                [
//                    'name'=>'姓名',
//                    'phone'=>'18785169822'
//                ]
//            ],
//            'remarks' => '备注'
//        ];
        //`order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
        //  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
        //  `type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
        //  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `product_img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
        //  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格属性描述',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单金额',
        //  `status` tinyint DEFAULT '0' COMMENT '订单状态  不同类型订单不一样',
        //  `order_keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单关键词用于订单搜索',
        //  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
        //  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
        //  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
        if (empty($data['type']) || empty($data['product_id']) || empty($data['spec_id'])) {
            return [
                'code' => 6001,
                'msg' => ApiIndexCode::$code['6001']
            ];
        }
        $order_no = $this->_getOrderNo($data['type']);
        $order_data['order_no'] = $order_no;//订单编号
        $order_data['type'] = $data['type'];//订单类型
        $order_data['product_id'] = $data['product_id'];//产品id
        $order_data['user_id'] = $data['user_id'];//用户id
        $order_data['share_user_id'] = isset($data['share_user_id']) ? $data['share_user_id'] : 0;//分享推广用户id
        $product_img = "";
        $product_name = "";
        $merchant_id = "";
        $spec_str = "";
        $spec_temp = [];
        switch ($data['type']) {
            case self::TYPE_TOURISM:
                $product_data = TourismProductModel::getProductInfo($data['product_id'], 'id,title,img,tag,merchant_id');
                $product_img = $product_data['img'];
                $product_name = $product_data['title'];
                $product_tag = $product_data['tag'];
                $order_data['product_tag'] = $product_tag;
                $merchant_id = $product_data['merchant_id'];
                $order_data['merchant_id'] = $merchant_id;
                $spec_data = TourismSpecModel::getSpecinfo($data['spec_id']);
                $no_pay = OrderTourismModel::getOrderItemNoPay($data['user_id']);
                if ($no_pay > SysConfigModel::getConfigKeyValue('NoPaymentCount')) {
                    return [
                        'code' => 6014,
                        'msg' => ApiIndexCode::$code['6014']
                    ];
                }
                if (empty($spec_data)) {
                    return [
                        'code' => 6003,
                        'msg' => ApiIndexCode::$code['6003']
                    ];
                }
                if (strtotime($spec_data['start_day']) <= strtotime(date("Y-m-d"))) {
                    return [
                        'code' => 6003,
                        'msg' => ApiIndexCode::$code['6003']
                    ];
                }
                //套餐限制人数
                $people = $spec_data['people'];
                //集合日期
                $start_day = $spec_data['start_day'];
                $order_data['start_day'] = $start_day;
                //集合城市
                $city = $spec_data['city'];
                $order_data['city'] = $city;

                //是否是节假日 1非节假日 2节假日
                $is_holiday = $spec_data['is_holiday'];
                $order_data['is_holiday'] = $is_holiday;
                //当前购买人数
                $buy_number = OrderTourismModel::getOrderItemNum($data['product_id'], $data['spec_id']);
                if ($data['number'] + $data['kid_number'] + $buy_number > $people) {
                    return [
                        'code' => 6007,
                        'msg' => ApiIndexCode::$code['6007']
                    ];
                }
                $spec_str = $spec_data['days_str'];
                $spec_temp = [
                    'days_str' => $spec_data['days_str'],
                    'start_day_week' => Util::getDayWeek($spec_data['start_day']),
                    'start_day' => $spec_data['start_day'],
                    'end_day' => $spec_data['end_day'],
                    'end_day_week' => Util::getDayWeek($spec_data['end_day']),
                    'kid_price' => $spec_data['kid_price'],
                    'kid_number' => $data['kid_number'],
                    'price' => $spec_data['price'],
                    'number' => $data['number'],
                    'city' => $spec_data['city']
                ];
                $order_data['spec_json'] = json_encode($spec_temp);
                $price = $spec_data['price'] * $data['number'];
                $kid_price = $spec_data['kid_price'] * $data['kid_number'];
                $order_data['amount'] = round(floatval($price) + floatval($kid_price), 2);
                $order_data['status'] = OrderTourismModel::STATUS_AUDIT;
                break;
            case self::TYPE_NORMAL:
            case self::TYPE_INTEGRAL:
                $product_data = GoodsProductModel::getProductInfo($data['product_id'], 'id,classify,merchant_id,express_type,title,img');
                $product_img = $product_data['img'];
                $product_name = $product_data['title'];
                $classify = $product_data['classify'];
                $merchant_id = $product_data['merchant_id'];
                if ($classify != $data['type']) {
                    return [
                        'code' => 6002,
                        'msg' => ApiIndexCode::$code['6002']
                    ];
                }
                $spec_data = GoodsSpecModel::getSpecinfo($data['spec_id']);
                $no_pay = OrderGoodsModel::getOrderItemNoPay($data['user_id']);
                if ($no_pay > SysConfigModel::getConfigKeyValue('NoPaymentCount')) {
                    return [
                        'code' => 6014,
                        'msg' => ApiIndexCode::$code['6014']
                    ];
                }
                if (empty($spec_data)) {
                    return [
                        'code' => 6003,
                        'msg' => ApiIndexCode::$code['6003']
                    ];
                }
                if ($spec_data['store'] <= 0) {
                    return [
                        'code' => 6016,
                        'msg' => ApiIndexCode::$code['6016']
                    ];
                }
                $spec_str = $spec_data['spec'];
                //`id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
                //  `product_id` int DEFAULT '0' COMMENT '商品id',
                //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格图片 默认是产品封面图',
                //  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格名称',
                //  `spec` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格',
                //  `store` int DEFAULT '0' COMMENT '商品库存',
                //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '售价',
                //  `cost_price` decimal(20,2) DEFAULT '0.00' COMMENT '进价',
                //  `limit` int DEFAULT '0' COMMENT '限购数量 0表示不限',
                //  `buy` int DEFAULT '0' COMMENT '销量',
                $spec_temp = [
                    'product_id' => $spec_data['product_id'],
                    'img' => $spec_data['img'],
                    'name' => $product_name,
                    'spec' => $spec_data['spec'],
                    'price' => $spec_data['price'],
                    'cost_price' => $spec_data['cost_price'],
                    'limit' => $spec_data['limit'],
                    'store' => $spec_data['store'],
                    'number' => $data['number']
                ];
                $order_data['merchant_id'] = $merchant_id;
                $order_data['spec_json'] = json_encode($spec_temp);
                $price = $spec_data['price'] * $data['number'];
                $order_data['amount'] = round(floatval($price), 2);
                //运费
                $order_data['freight_amount'] = 0;
                $order_data['status'] = OrderGoodsModel::STATUS_PAYMENT;
                $order_data['express_type'] = $product_data['express_type'];
                //自提时间
                $order_data['take_day'] = $data['take_day'];
                break;

        }
        if (empty($product_data)) {
            return [
                'code' => 6002,
                'msg' => ApiIndexCode::$code['6002']
            ];
        }
        $order_data['product_img'] = $product_img;
        $order_data['product_name'] = $product_name;
        $order_data['spec_id'] = $data['spec_id'];//规格属性id
        $order_data['order_keyword'] = $product_data['title'] . "|" . $spec_str;
        $order_data['create_day'] = date('Y-m-d');
        $order_data['create_week'] = date('Y-W');
        $order_data['create_month'] = date('Y-m');
        $order_data['update_time'] = time();
        $order_data['create_time'] = time();
        $this->startTrans();
        $order_id = $this->insertGetId($order_data);
        if (empty($order_id)) {
            $this->rollback();
            return [
                'code' => 6004,
                'msg' => ApiIndexCode::$code['6004']
            ];
        }
        switch ($data['type']) {
            case self::TYPE_TOURISM:
                $order_item = [
                    'order_id' => $order_id,
                    'user_id' => $data['user_id'],
                    'product_id' => $data['product_id'],
                    'product_tag' => $order_data['product_tag'],
                    'merchant_id' => $merchant_id,
                    'spec_id' => $data['spec_id'],
                    'product_img' => $product_img,
                    'product_name' => $product_name,
                    'spec_json' => $order_data['spec_json'],
                    'kid_number' => $spec_temp['kid_number'],
                    'number' => $spec_temp['number'],
                    'kid_price' => $spec_temp['kid_price'],
                    'price' => $spec_temp['price'],
                    'amount' => $order_data['amount'],
                    'contacts' => $data['contacts'],
                    'remarks' => $data['remarks'],
                ];
                $order_tourism_model = new OrderTourismModel();
                $order_item_id = $order_tourism_model->addOrderItem($order_item);
                if (!empty($order_item_id)) {
                    //添加购买记录
                    TourismBuyLogModel::addBuyLog($data['user_id'], $data['product_id'], $data['spec_id'], ($spec_temp['kid_number'] + $spec_temp['number']), $order_data['amount']);
                }
                break;
            case self::TYPE_NORMAL:
            case self::TYPE_INTEGRAL:
                // `order_id` int NOT NULL DEFAULT '0' COMMENT '订单id',
                //  `user_id` int DEFAULT '0' COMMENT '用户id',
                //  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
                //  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
                //  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
                //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
                //  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
                //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '单价',
                //  `number` int DEFAULT '0' COMMENT '购买数量',
                //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
                //  `contacts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收货地址json 数据',
                //  `ver_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
                //  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
                //  `status` tinyint DEFAULT '2' COMMENT '订单状态  0订单关闭   2待付款 3待核验/待收货 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
                $order_item = [
                    'order_id' => $order_id,
                    'user_id' => $data['user_id'],
                    'product_id' => $data['product_id'],
                    'merchant_id' => $merchant_id,
                    'spec_id' => $data['spec_id'],
                    'product_img' => $product_img,
                    'product_name' => $product_name,
                    'spec_json' => $order_data['spec_json'],
                    'number' => $spec_temp['number'],
                    'price' => $spec_temp['price'],
                    'amount' => $order_data['amount'],
                    'contacts' => $data['contacts'],
                    'remarks' => $data['remarks'],
                ];
                $order_tourism_model = new OrderGoodsModel();
                $order_item_id = $order_tourism_model->addOrderItem($order_item);
                if (!empty($order_item_id)) {
                    //添加购买记录
                    GoodsBuyLogModel::addBuyLog($data['user_id'], $data['product_id'], $data['spec_id'], $spec_temp['number'], $order_data['amount']);
                    //减少库存
                    GoodsStoreModel::changeStore($data['product_id'], $data['spec_id'], $spec_temp['number'], GoodsStoreModel::TYPE_BUY, $order_data['user_id']);
                    //清空购物车
                    GoodsCartModel::clearGoodsCart($data['user_id'], $data['spec_id']);
                }
                break;
        }
        if (empty($order_item_id)) {
            $this->rollback();
            return [
                'code' => 6005,
                'msg' => ApiIndexCode::$code['6005']
            ];
        }
        $this->commit();
        return [
            'code' => 1,
            'data' => [
                'order_id' => $order_id,
                'order_no' => $order_no,
                'order_item_id' => $order_item_id
            ]
        ];
    }

    /**
     * 订单状态修改
     * @param $type
     * @param $order_id
     * @param int $status
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function changeOrderStatus($type, $order_id, $status = 0)
    {
        $ret = false;
        switch ($type) {
            case self::TYPE_TOURISM:
                $order_tourism_model = new OrderTourismModel();
                $ret = $order_tourism_model->changeOrderStatus($order_id, $status);
                break;
            case self::TYPE_NORMAL:
            case self::TYPE_INTEGRAL:
                $order_goods_model = new OrderGoodsModel();
                $ret = $order_goods_model->changeOrderStatus($order_id, $status);
                break;
        }
        return $ret;
    }

    /**
     * 订单发货
     * @param $id
     * @param $logistics_id
     * @param $number
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function sendOrder($id, $logistics_id, $number)
    {
        $logistics = GoodsLogisticsModel::where('id', $logistics_id)->field('name,code')->find();
        $logistics_data = [
            'logistics_id' => $logistics_id,
            'send_time' => time(),
            'name' => $logistics['name'],
            'code' => $logistics['code'],
            'number' => $number
        ];
        $up_data = [
            'logistics' => json_encode($logistics_data)
        ];
        $ret = OrderGoodsModel::where('order_id', $id)->update($up_data);
        if ($ret) {
            $order_data = $this->where('id', $id)->find();
            $ret = $this->changeOrderStatus($order_data['type'], $id, OrderGoodsModel::STATUS_SEND);
            if ($ret) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 核销订单
     * @param $order_id
     * @param $code
     * @param $type
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function verOrderCode($order_id, $code, $type)
    {
        if ($type == OrderModel::TYPE_TOURISM) {
            $map = [
                'order_id' => $order_id,
                'ver_code' => $code,
                'status' => OrderTourismModel::STATUS_PAID
            ];
            $order_item_data = OrderTourismModel::where($map)->find();
            if (empty($order_item_data)) {
                return false;
            }
            $ret = $this->changeOrderStatus($type, $order_id, OrderTourismModel::STATUS_VERIFY);
        } else {
            $map = [
                'order_id' => $order_id,
                'ver_code' => $code
            ];
            $order_item_data = OrderGoodsModel::where($map)->whereIn('status', [OrderGoodsModel::STATUS_PAID, OrderGoodsModel::STATUS_SEND])->find();
            if (empty($order_item_data)) {
                return false;
            }
            $ret = $this->changeOrderStatus($type, $order_id, OrderGoodsModel::STATUS_VERIFY);
        }
        if ($ret) {
            if ($type == OrderModel::TYPE_TOURISM) {
                //核验成功在此回调
                //赠送积分 与 佣金
                $order_data = $this->where('id', $order_id)->find();
                $money_log = new MoneyLogRepository($order_data['user_id'], $order_data['amount']);
                $money_log->integralAndCommission($order_data['id'], $order_data['type']);
                $this->sendVerifySms($order_id);
            }
            return true;
        }
        return false;
    }

    /**
     * 修改订单金额
     * @param $order_id
     * @param $amount
     * @return bool
     */
    public function modOrderAmount($order_id, $amount)
    {
        $map = [
            'id' => $order_id
        ];
        $ret = $this->where($map)->whereIn('status', [OrderTourismModel::STATUS_AUDIT, OrderTourismModel::STATUS_PAYMENT])->update([
            'amount' => $amount
        ]);
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 取消订单（用户操作 系统自动取消）
     * @param string $order_id
     * @param int $type
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelOrder($order_id = '', $type = OrderModel::TYPE_TOURISM, $user_id = '')
    {
        if (!empty($user_id)) {
            $map = [
                'id' => $order_id,
                'user_id' => $user_id
            ];
            $order_data = $this->where($map)->field('id')->find();
            if (empty($order_data)) {
                return false;
            }
        }
        return $this->changeOrderStatus($type, $order_id, OrderTourismModel::STATUS_CANCEL);
    }

    /**
     * 取消指定时间内容未支付订单
     * @param float|int $time
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelOrderList($time = 60 * 30)
    {
        // 30分钟未支付取消订单
        //套餐订单
        $map = [
            'type' => OrderModel::TYPE_TOURISM,
            'status' => OrderTourismModel::STATUS_PAYMENT,
        ];
        $order_ids = $this->where($map)->column('id');
        $order_tourism_model = new OrderTourismModel();
        $order_tourism_list = $order_tourism_model->whereIn('order_id', $order_ids)->where('affirm_time', '<', time() - $time)->field('id,order_id')->select();
        foreach ($order_tourism_list as $item) {
            $ret = $this->cancelOrder($item['order_id'], OrderModel::TYPE_TOURISM);
            if ($ret) {
                //到时间未支付取消订单 发送短信提醒
                $this->sendCancelSms($item['order_id']);
            }
        }

        //商品订单
        $map = [
            'status' => OrderGoodsModel::STATUS_PAYMENT,
        ];
        $order_goods_list = $this->where($map)->whereIn('type', [OrderModel::TYPE_NORMAL, OrderModel::TYPE_INTEGRAL])->where('create_time', '<', time() - $time)->field('id,type')->select();
        foreach ($order_goods_list as $item) {
            $this->cancelOrder($item['id'], $item['type']);
        }
        return true;
    }

    /**
     * 15分钟未支付 发送短信提醒
     * @param int $time
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function paymentOrdersSmsList($time = 450)
    {
        // 15分钟未支付 发送消息提醒
        //套餐订单
        $map = [
            'type' => OrderModel::TYPE_TOURISM,
            'sms_payment_status' => 1,
            'status' => OrderTourismModel::STATUS_PAYMENT,
        ];
        $order_ids = $this->where($map)->column('id');
        $order_tourism_model = new OrderTourismModel();
        $order_tourism_list = $order_tourism_model->whereIn('order_id', $order_ids)->where('affirm_time', '<', time() - $time)->field('id,order_id')->select();
        foreach ($order_tourism_list as $item) {
            $this->sendPaymentSms($item['order_id']);
        }
//        //商品订单
//        $map = [
//            'status' => OrderGoodsModel::STATUS_PAYMENT,
//        ];
//        $order_goods_list = $this->where($map)->whereIn('type', [OrderModel::TYPE_NORMAL, OrderModel::TYPE_INTEGRAL])->where('create_time', '<', time() - $time)->field('id,type')->select();
//        foreach ($order_goods_list as $item) {
//            $this->cancelOrder($item['id'], $item['type']);
//        }
        return true;
    }

    /**
     * 发送未支付提醒短信
     * @param string $order_id
     * @return bool
     */
    public function sendPaymentSms($order_id = '')
    {
        $map = [
            'id' => $order_id,
            'sms_payment_status' => 2
        ];
        $contacts = OrderTourismModel::where($map)->value('contacts');
        if (empty($contacts)) {
            return false;
        }
        $contacts = json_decode($contacts, true);
        if (isset($contacts[0])) {
            $contact = $contacts[0];
            //未支付发送短信
            $name = $contact['name'];
            $mobile = $contact['mobile'];
//            $config = Config::get('system.aliyun_sms');
//            $template = [
//                'TemplateCode' => $config['TemplatePayment'],
//                'TemplateParam' => [
//                    'name' => $name,
//                    'order_no' => $order_id
//                ]
//            ];
//            AliyunSms::sendSmsMessage($mobile, $template);
            $up_data = [
                'sms_payment_status' => 2
            ];
            $this->where('id', $order_id)->update($up_data);
            return true;
        }
        return false;
    }

    /**
     * 发送未支付系统取消提醒短信
     * @param string $order_id
     * @return bool
     */
    public function sendCancelSms($order_id = '')
    {
        $map = [
            'id' => $order_id
        ];
        $contacts = OrderTourismModel::where($map)->value('contacts');
        if (empty($contacts)) {
            return false;
        }
        $contacts = json_decode($contacts, true);
        if (isset($contacts[0])) {
//            $contact = $contacts[0];
//            $order_data = OrderModel::where('id', $order_id)->field('order_no,product_name,start_day')->find();
//            //未支付发送短信
//            $mobile = $contact['mobile'];
//            $travel_name = $order_data['product_name'];
//            $travel_date = $order_data['start_day'];
//            $num1 = $order_data['order_no'];
//            $config = Config::get('system.aliyun_sms');
//            $template = [
//                'TemplateCode' => $config['TemplateCancel'],
//                'TemplateParam' => [
//                    'travel_name' => $travel_name,
//                    'travel_date' => $travel_date,
//                    'num1' => $num1,
//                ]
//            ];
//            AliyunSms::sendSmsMessage($mobile, $template);
            return true;
        }
        return false;
    }

    /**
     * 支付完成后发送短信提醒
     * @param string $order_id
     * @return bool
     */
    public function sendPaidSms($order_id = '')
    {
        $map = [
            'id' => $order_id
        ];
        $contacts = OrderTourismModel::where($map)->field('contacts,serve_mobile')->find();
        if (empty($contacts)) {
            return false;
        }
        if (empty($contacts['contacts']) || empty($contacts['serve_mobile'])) {
            return false;
        }
        $contacts = json_decode($contacts['contacts'], true);
        $serve_mobile = json_decode($contacts['serve_mobile'], true);
        if (isset($contacts[0])) {
//            $contact = $contacts[0];
//            $order_data = OrderModel::where('id', $order_id)->field('order_no,product_name,start_day,city')->find();
            //未支付发送短信
//            $mobile = $contact['mobile'];
//            $travel_date1 = $travel_date2 = $order_data['start_day'];
//            $travel_name = $order_data['product_name'];
//            $num1 = $order_data['order_no'];
//            $travel_address = $order_data['city'];
//            $guide_name = $serve_mobile['name'];
//            $guide_phone = $serve_mobile['phone'];
//            //$travel_name 套餐名称
//            //$num1 订单号
//            //$travel_date1 出行日期
//            //$travel_date2 出行日期
//            //$travel_address 集合地点
//            //$guide_name 联系人
//            //$guide_phone 联系电话
//            $config = Config::get('system.aliyun_sms');
//            $template = [
//                'TemplateCode' => $config['TemplatePaid'],
//                'TemplateParam' => [
//                    'travel_name' => $travel_name,
//                    'num1' => $num1,
//                    'travel_date1' => $travel_date1,
//                    'travel_date2' => $travel_date2,
//                    'travel_address' => $travel_address,
//                    'guide_name' => $guide_name,
//                    'guide_phone' => $guide_phone,
//                ]
//            ];
//            AliyunSms::sendSmsMessage($mobile, $template);
            return true;
        }
        return false;
    }

    /**
     * 发送核验短信
     * @param $order_id
     * @return bool
     */
    public function sendVerifySms($order_id = '')
    {
        $map = [
            'id' => $order_id
        ];
        $contacts = OrderTourismModel::where($map)->value('contacts');
        if (empty($contacts)) {
            return false;
        }
        $contacts = json_decode($contacts, true);
        if (isset($contacts[0])) {
//            $contact = $contacts[0];
//            $order_data = OrderModel::where('id', $order_id)->field('order_no,product_name,start_day')->find();
//            //未支付发送短信
//            $mobile = $contact['mobile'];
//            $travel_name = $order_data['product_name'];
//            $travel_date = $order_data['start_day'];
//            $num1 = $order_data['order_no'];
//            $config = Config::get('system.aliyun_sms');
//            $template = [
//                'TemplateCode' => $config['TemplateVerify'],
//                'TemplateParam' => [
//                    'travel_name' => $travel_name,
//                    'travel_date' => $travel_date,
//                    'num1' => $num1,
//                ]
//            ];
//            AliyunSms::sendSmsMessage($mobile, $template);
            return true;
        }
        return false;
    }

    /**
     * 获取支付信息
     * @param string $order_id
     * @param string $order_no
     * @param string $pay_type
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayInfo($order_id = '', $order_no = '', $pay_type = 'weixin')
    {
        $map = [];
        if (!empty($order_id)) {
            $map['id'] = $order_id;
        }
        if (!empty($order_no)) {
            $map['order_no'] = $order_no;
        }
        $order_data = $this->where($map)->field('id,order_no,status,type,user_id,product_name,amount,merchant_id')->find();
        if (empty($order_data)) {
            return [
                'code' => 6013,
                'msg' => ApiIndexCode::$code['6013']
            ];
        }
        if ($order_data['type'] == OrderModel::TYPE_TOURISM) {
            if ($order_data['status'] != OrderTourismModel::STATUS_PAYMENT) {
                return [
                    'code' => 6013,
                    'msg' => ApiIndexCode::$code['6013']
                ];
            }
        } else {
            if ($order_data['status'] != OrderGoodsModel::STATUS_PAYMENT) {
                return [
                    'code' => 6013,
                    'msg' => ApiIndexCode::$code['6013']
                ];
            }
        }
        $data = [
            'order_no' => $order_data['order_no'],
            'pay_type' => $pay_type,
            'order_type' => $order_data['type'],
            'user_id' => $order_data['user_id'],
            'product_name' => $order_data['product_name'],
            'pay_amount' => $order_data['amount'],
            'merchant_id' => $order_data['merchant_id'],
        ];
        $order_pay_model = new OrderPayModel();
        return $order_pay_model->addPayInfo($data);
    }

    /**
     * 获取支付信息
     * @param string $order_ids
     * @param string $pay_type
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayInfos($order_ids = '', $pay_type = 'weixin')
    {
        $map = [];
        if (!empty($order_ids)) {
            $map[] = ['id', 'in', $order_ids];
        }
        $order_data = $this->where($map)->field('id,order_no,status,type,user_id,product_id,product_name,amount,merchant_id')->select();
        if (empty($order_data)) {
            return [
                'code' => 6013,
                'msg' => ApiIndexCode::$code['6013']
            ];
        }
        $data = [];
        foreach ($order_data as $k => $v) {
            $v['product_name'] = GoodsLangModel::getGoodsName($v['product_id']);
            if ($v['type'] == OrderModel::TYPE_TOURISM) {
                if ($v['status'] != OrderTourismModel::STATUS_PAYMENT) {
                    return [
                        'code' => 6013,
                        'msg' => ApiIndexCode::$code['6013']
                    ];
                }
            } else {
                if ($v['status'] != OrderGoodsModel::STATUS_PAYMENT) {
                    return [
                        'code' => 6013,
                        'msg' => ApiIndexCode::$code['6013']
                    ];
                }
            }
            $data[] = [
                'order_no' => $v['order_no'],
                'pay_type' => $pay_type,
                'order_type' => $v['type'],
                'user_id' => $v['user_id'],
                'product_name' => $v['product_name'],
                'pay_amount' => $v['amount']
            ];
        }
        $order_pay_model = new OrderPayModel();
        return $order_pay_model->addPayInfos($data);
    }

    /**
     * 支付完成订单
     * @param string $pay_no
     * @param string $pay_amount 支付金额
     * @param string $pay_type 支付类型
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function endPayOrder($pay_no = '', $pay_amount = 0, $pay_type = OrderPayModel::PAY_TYPE_WEIXIN)
    {
        if (empty($order_id) && empty($pay_no)) {
            return false;
        }
        if (!empty($pay_no)) {
            $map = [
                'pay_no' => $pay_no,
            ];
        } else {
            return false;
        }
        $pay_data = OrderPayModel::where($map)->find();
        if (empty($pay_data)) {
            return false;
        }
        if ($pay_data['status'] == OrderPayModel::STATUS_PAID) {
            //已经支付
            return true;
        }
        $up_data = [
            'pay_type' => $pay_type
        ];
        //更新支付方式
        OrderPayModel::where($map)->update($up_data);
        //'status' => OrderTourismModel::STATUS_PAYMENT
        $order_data = $this->where($map)->field('id,status,type,pay_no,amount')->select();
        if (empty($order_data)) {
            return false;
        }
        $order_pay_amount = 0;
        $end_order_data = [];
        foreach ($order_data as $k => $v) {
            $order_pay_amount += $v['amount'];
            if ($v['type'] == OrderModel::TYPE_TOURISM) {
                if ($order_data['status'] == OrderTourismModel::STATUS_PAYMENT) {
                    $end_order_data[] = [
                        'id' => $v['id']
                    ];
                }
            } else {
                if ($v['status'] == OrderGoodsModel::STATUS_PAYMENT) {
                    $end_order_data[] = [
                        'id' => $v['id']
                    ];
                }
            }
        }
        //支付金额和实际支付金额不相等
        if (floatval($order_pay_amount) > floatval($pay_amount)) {
            return false;
        }
        foreach ($end_order_data as $k => $v) {
            $ret = $this->_endPayOrderItem($v['id']);
            if ($ret) {
                //支付完成发送短信
                $this->sendPaidSms($v['id']);
            }
        }
        return true;
    }

    /**
     * 积分支付
     * @param $pay_no
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function integralPay($pay_no, $user_id = '')
    {
        if (empty($pay_no) && empty($user_id)) {
            return false;
        }
        $map = [
            'pay_no' => $pay_no,
            'user_id' => $user_id
        ];
        $pay_data_list = OrderPayModel::where($map)->select();
        if (empty($pay_data_list)) {
            return false;
        }
        $rets = false;
        foreach ($pay_data_list as $k => $pay_data) {
            if ($pay_data['status'] == OrderPayModel::STATUS_PAID) {
                //已经支付
                continue;
            }
            $up_data = [
                'pay_type' => OrderPayModel::PAY_TYPE_INTEGRAL
            ];
            //更新支付方式
            OrderPayModel::where($map)->update($up_data);
            $order_data = $this->where($map)->field('id,status,type,pay_no,amount')->find();
            if (empty($order_data)) {
                continue;
            }
            $money_log = new MoneyLogRepository($user_id, intval($order_data['amount']));
            $temp = $money_log->integralDeduction(intval($order_data['id']), '积分兑换');
            if ($temp['code'] == 1) {
                $ret = $this->_endPayOrderItem($order_data['id']);
                if ($ret) {
                    $rets = true;
                } else {
                    $rets = false;
                }

            } else {
                $rets = false;
            }
        }
        return $rets;
    }

    /**
     * 余额支付
     * @param $pay_no
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function balancePay($pay_no, $user_id = '')
    {
        if (empty($pay_no) && empty($user_id)) {
            return false;
        }
        $map = [
            'pay_no' => $pay_no,
            'user_id' => $user_id
        ];
        $pay_data_list = OrderPayModel::where($map)->select();
        if (empty($pay_data_list)) {
            return false;
        }
        $rets = false;
        foreach ($pay_data_list as $k => $pay_data) {
            if ($pay_data['status'] == OrderPayModel::STATUS_PAID) {
                //已经支付
                continue;
            }
            $up_data = [
                'pay_type' => OrderPayModel::PAY_TYPE_BALANCE
            ];
            //更新支付方式
            OrderPayModel::where($map)->update($up_data);
            $mapo = [
                'order_no' => $pay_data['order_no'],
                'user_id' => $user_id
            ];
            $order_data = $this->where($mapo)->field('id,status,type,pay_no,amount')->find();
            if (empty($order_data)) {
                continue;
            }
            $money_log = new MoneyLogRepository($user_id, floatval($order_data['amount']));
            $temp = $money_log->balancePay(MoneyLogRepository::FROM_TYPE_BALANCE_PAY, '余额支付');
            if ($temp['code'] == 1) {
                $ret = $this->_endPayOrderItem($order_data['id']);
                if ($ret) {
                    $rets = true;
                } else {
                    $rets = false;
                }
            } else {
                $rets = false;
            }
        }
        return $rets;

    }

    /**
     * 更新支付图片
     * @param $pay_no
     * @param string $pay_img
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updatePayImg($pay_no, $pay_img = '')
    {
        $order_temp = $this->where('pay_no', $pay_no)->select();
        if (empty($order_temp)) {
            return false;
        }
        $ret = $this->where('pay_no', $pay_no)->update(['pay_img' => $pay_img, 'pay_img_time' => time(), 'update_time' => time()]);
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 订单支付完成
     * @param string $order_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function _endPayOrderItem($order_id = '')
    {
        $map = [
            'id' => $order_id,
        ];
        $this->startTrans();
        $order_data = $this->where($map)->field('id,order_no,status,type,user_id,product_id,product_name,amount,merchant_id')->find();
        if (empty($order_data)) {
            $this->rollback();
            return false;
        }
        $order_data['product_name'] = GoodsLangModel::getGoodsName($order_data['product_id']);
        if ($order_data['type'] == OrderModel::TYPE_TOURISM) {
            if ($order_data['status'] != OrderTourismModel::STATUS_PAYMENT) {
                $this->rollback();
                return false;
            }
            $status = OrderTourismModel::STATUS_PAID;
        } else {
            if ($order_data['status'] != OrderGoodsModel::STATUS_PAYMENT) {
                $this->rollback();
                return false;
            }
            $status = OrderGoodsModel::STATUS_PAID;
        }
        $ret = $this->changeOrderStatus($order_data['type'], $order_data['id'], $status);
        if ($ret == true) {
            $order_pay_model = new OrderPayModel();
            $ret = $order_pay_model->endPayInfo('', $order_data['order_no']);
            if ($ret) {
                $this->commit();
                return true;
            } else {
                $this->rollback();
                return false;
            }
        }
        $this->rollback();
        return false;
    }

    /**
     * 申请退款
     * @param string $order_id
     * @param string $refund_remark
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function applyRefundOrder($order_id = '', $refund_remark = '', $user_id = '')
    {
        if (!empty($user_id)) {
            $map = [
                'id' => $order_id,
                'user_id' => $user_id
            ];
            $order_data = $this->where($map)->field('id')->find();
            if (empty($order_data)) {
                return false;
            }
        }
        $order_temp = $this->where($map)->field('type')->find();
        if ($order_temp['type'] == OrderModel::TYPE_TOURISM) {
            $map = [
                'id' => $order_id,
                'type' => $order_temp['type'],
                'status' => OrderTourismModel::STATUS_PAID
            ];
            $status = OrderTourismModel::STATUS_REFUNDING;
        } else {
            $map = [
                'id' => $order_id,
                'type' => $order_temp['type'],
                'status' => OrderGoodsModel::STATUS_PAID
            ];
            $status = OrderGoodsModel::STATUS_REFUNDING;
        }
        $temp = $this->where($map)->find();
        if (empty($temp)) {
            return false;
        }
        $ret = $this->changeOrderStatus($order_temp['type'], $order_id, $status);
        if ($ret) {
            $up_data = [
                'refund_remark' => $refund_remark
            ];
            $this->where('id', $order_id)->update($up_data);
            return true;
        }
        return false;
    }

    /**
     * 申请退货退款
     * @param string $order_id
     * @param string $refund_remark
     * @param string $return_explain
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function applyRefundGoodsOrder($order_id = '', $refund_remark = '', $return_explain = '', $user_id = '')
    {
        if (!empty($user_id)) {
            $map = [
                'id' => $order_id,
                'user_id' => $user_id
            ];
            $order_data = $this->where($map)->field('id')->find();
            if (empty($order_data)) {
                return false;
            }
        } else {
            $map = [
                'id' => $order_id,
            ];
        }
        $order_temp = $this->where($map)->field('type')->find();
        if (empty($order_temp)) {
            return false;
        }
        $map_in = [];
        if ($order_temp['type'] == OrderModel::TYPE_TOURISM) {
            return false;
        } else {
            $map = [
                'id' => $order_id,
                'type' => $order_temp['type'],
            ];
            $map_in[] = [
                'status', 'in', [OrderGoodsModel::STATUS_SEND, OrderGoodsModel::STATUS_VERIFY]
            ];
            $status = OrderGoodsModel::STATUS_REFUNDSALES;
        }
        $temp = $this->where($map)->where($map_in)->find();
        if (empty($temp)) {
            return false;
        }
        $ret = $this->changeOrderStatus($order_temp['type'], $order_id, $status);
        if ($ret) {
            $up_data = [
                'refund_remark' => $refund_remark,
                'return_explain' => json_encode($return_explain)
            ];
            $this->where('id', $order_id)->update($up_data);
            return true;
        }
        return false;
    }

    /**
     * 用户退货物流信息设置
     * @param string $order_id
     * @param string $logistics_id
     * @param string $number
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function setRefundLogisticsOrder($order_id = '', $logistics_id = '', $number = '', $user_id = '')
    {
        if (!empty($user_id)) {
            $map = [
                'id' => $order_id,
                'user_id' => $user_id,
                'status' => OrderGoodsModel::STATUS_REFUND_OK
            ];
            $order_data = $this->where($map)->field('id')->find();
            if (empty($order_data)) {
                return false;
            }
        } else {
            $map = [
                'id' => $order_id,
                'status' => OrderGoodsModel::STATUS_REFUND_OK
            ];
            $order_data = $this->where($map)->field('id')->find();
            if (empty($order_data)) {
                return false;
            }
        }

        $logistics = GoodsLogisticsModel::where('id', $logistics_id)->field('name,code')->find();
        $logistics_data = [
            'logistics_id' => $logistics_id,
            'send_time' => time(),
            'name' => $logistics['name'],
            'code' => $logistics['code'],
            'number' => $number
        ];
        $up_data = [
            'refund_logistics' => json_encode($logistics_data)
        ];
        $ret = OrderGoodsModel::where('order_id', $order_id)->update($up_data);
        if ($ret) {
            $order_data = $this->where('id', $order_id)->find();
            $ret = $this->changeOrderStatus($order_data['type'], $order_id, OrderGoodsModel::STATUS_REFUNDSALESING);
            if ($ret) {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * 取消退款申请
     * @param string $order_id
     * @param string $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelRefundOrder($order_id = '', $user_id = '')
    {
        $map = [
            'id' => $order_id,
            'user_id' => $user_id
        ];
        $order_temp = $this->where($map)->field('type')->find();
        $up_order = [];
        OrderModel::startTrans();
        if ($order_temp['type'] == OrderModel::TYPE_TOURISM) {
            $map = [
                'order_id' => $order_id,
                'status' => OrderTourismModel::STATUS_REFUNDING
            ];
            $up_order['status'] = OrderTourismModel::STATUS_PAID;
            $up_data = [
                'status' => OrderTourismModel::STATUS_PAID,
                'refund_apply_time' => 0
            ];
            $ret = OrderTourismModel::where($map)->update($up_data);
            if ($ret == false) {
                OrderModel::rollback();
                return false;
            }
        } else {
            $map = [
                'order_id' => $order_id,
                'status' => OrderGoodsModel::STATUS_REFUNDING
            ];
            $up_order['status'] = OrderGoodsModel::STATUS_PAID;
            $up_data = [
                'status' => OrderGoodsModel::STATUS_PAID,
                'refund_apply_time' => 0
            ];
            $ret = OrderGoodsModel::where($map)->update($up_data);
            if ($ret == false) {
                OrderModel::rollback();
                return false;
            }
        }
        $ret = $this->where('id', $order_id)->update($up_order);
        if ($ret == false) {
            OrderModel::rollback();
            return false;
        } else {
            OrderModel::commit();
            return true;
        }
    }

    /**
     * 结束退款
     * @param int $type
     * @param string $order_id
     * @param int $check 1 同意 0 拒绝
     * @param float $refund_amount
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function endRefundOrder($type = self::TYPE_TOURISM, $order_id = '', $check = 0, $refund_amount = 0)
    {
        $map = [
            'id' => $order_id,
            'type' => $type,
            'status' => OrderTourismModel::STATUS_REFUNDING
        ];
        $temp = $this->where($map)->find();
        if (empty($temp)) {
            return false;
        }
        if ($check == 1) {
            //同意
            $ret = $this->changeOrderStatus($type, $order_id, OrderTourismModel::STATUS_REFUND);
            if ($ret) {
                $up_data = [
                    'refund_amount' => $refund_amount
                ];
                $ret = $this->where('id', $order_id)->update($up_data);
                //发起退款
                if ($ret) {
                    $order_pay_model = new OrderPayModel();
                    $ret = $order_pay_model->addRefundInfo($temp['order_no']);
                    if ($ret) {
                        return true;
                    }
                }
            }
        } else {
            //拒绝 继续待核验
            $ret = $this->changeOrderStatus($type, $order_id, OrderTourismModel::STATUS_PAID);
        }
        return $ret;
    }


    /**
     * 商品未发货完成退款
     * @param string $order_id
     * @param float $refund_amount
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function endGoodsRefundOrder($order_id = '', $refund_amount = 0)
    {
        $map = [
            'id' => $order_id
        ];
        $temp = $this->where($map)->whereIn('status', [OrderGoodsModel::STATUS_PAID, OrderGoodsModel::STATUS_REFUNDING])->field('type')->find();
        if (empty($temp)) {
            return false;
        }
        $ret = $this->changeOrderStatus($temp['type'], $order_id, OrderGoodsModel::STATUS_REFUND);
        if ($ret) {
            $up_data = [
                'refund_amount' => $refund_amount
            ];
            $ret = $this->where('id', $order_id)->update($up_data);
            //发起退款
            if ($ret) {
                return true;
//                $order_pay_model = new OrderPayModel();
//                $ret = $order_pay_model->addRefundInfo($temp['order_no']);
//                if ($ret) {
//                    return true;
//                }
            }
        }
        return $ret;
    }


    /**
     * 商品已发货同意或者拒绝退款
     * @param string $order_id
     * @param float $refund_amount
     * @param int $check
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function okSendGoodsRefundOrder($order_id = '', $refund_amount = 0, $check = 0)
    {
        $map = [
            'id' => $order_id
        ];
        $temp = $this->where($map)->where('status', OrderGoodsModel::STATUS_REFUNDSALES)->field('type')->find();
        if (empty($temp)) {
            return false;
        }
        if ($check == 0) {
            $ret = $this->changeOrderStatus($temp['type'], $order_id, OrderGoodsModel::STATUS_REFUSESALES);
        } else {
            $ret = $this->changeOrderStatus($temp['type'], $order_id, OrderGoodsModel::STATUS_REFUND_OK);
        }
        if ($ret) {
            $up_data = [
                'refund_amount' => $refund_amount
            ];
            $ret = $this->where('id', $order_id)->update($up_data);
            if ($ret) {
                return true;
            }
        }
        return $ret;
    }

    /**
     * 商品已发货同意或者拒绝退款
     * @param string $order_id
     * @param float $refund_amount
     * @param int $check
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function endSendGoodsRefundOrder($order_id = '', $refund_amount = 0, $check = 0)
    {
        $map = [
            'id' => $order_id
        ];
        $temp = $this->where($map)->where('status', OrderGoodsModel::STATUS_REFUNDSALESING)->field('type')->find();
        if (empty($temp)) {
            return false;
        }
        if ($check == 0) {
            $ret = $this->changeOrderStatus($temp['type'], $order_id, OrderGoodsModel::STATUS_REFUSESALES);
        } else {
            $ret = $this->changeOrderStatus($temp['type'], $order_id, OrderGoodsModel::STATUS_RETURNSALES_OK);
        }
        if ($ret) {
            $up_data = [
                'refund_amount' => $refund_amount
            ];
            $ret = $this->where('id', $order_id)->update($up_data);
            if ($ret) {
                return true;
            }
        }
        return $ret;
    }

    /**
     * 获取订单状态
     * @param $status
     * @param int $type
     */
    public function getOrderStatus($status, $type = self::TYPE_TOURISM)
    {
        $str = "未定义";
        switch ($type) {
            case self::TYPE_TOURISM:
                $statusArr = OrderTourismModel::$STATUS_MAP;
                if (isset($statusArr[$status])) {
                    $str = $statusArr[$status];
                }
                break;
            case self::TYPE_NORMAL:
            case self::TYPE_INTEGRAL:
                $statusArr = OrderGoodsModel::$STATUS_MAP;
                if (isset($statusArr[$status])) {
                    $str = $statusArr[$status];
                }
                break;
        }
        return $str;
    }

    /**
     * 获取订单详情
     * @param $order_id
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getTourismOrderInfo($order_id)
    {
        // `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
        //  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
        //  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
        //  `merchant_id` int DEFAULT '0' COMMENT '服务商家id',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
        // `kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童票价',
        //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '成人票价',
        //  `kid_number` int DEFAULT '0' COMMENT '儿童订购数量',
        //  `number` int DEFAULT '0' COMMENT '成人订购数量',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
        // `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人信息json 数据',
        //  `ver_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
        //  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
        //  `status` tinyint DEFAULT '1' COMMENT '订单状态  0订单关闭  1待确认 2待付款 3待核验 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
        // `affirm_time` int DEFAULT '0' COMMENT '确认时间',
        //  `pay_time` int DEFAULT '0' COMMENT '付款时间',
        //  `ver_time` int DEFAULT '0' COMMENT '核验时间',
        //  `evaluate_time` int DEFAULT '0' COMMENT '评价时间',
        //  `close_time` int DEFAULT '0' COMMENT '订单关闭时间',
        //  `refund_apply_time` int DEFAULT '0' COMMENT '申请退款时间',
        //  `refund_time` int DEFAULT '0' COMMENT '退款时间',
        //  `refuse_time` int DEFAULT '0' COMMENT '拒绝退款时间',
        $field = "id,product_id,product_img,product_name,product_tag,merchant_id,
        spec_id,spec_json,kid_price,price,kid_number,number,amount,contacts,ver_code,remarks,status,
        affirm_time,pay_time,ver_time,evaluate_time,close_time,refund_apply_time,refund_time,refuse_time,create_time";
        $order_data = OrderTourismModel::where('order_id', $order_id)->field($field)->find();
        if (empty($order_data)) {
            return [
                'code' => 6015,
                'msg' => ApiIndexCode::$code['6015']
            ];
        }
        $spec_data = json_decode($order_data['spec_json'], true);
        $out_data = $order_data->toArray();
        unset($out_data['spec_json']);
        $order_temp = OrderModel::where('id', $order_id)->field('order_no,pay_no')->find();
        if (empty($order_temp)) {
            return [
                'code' => 6015,
                'msg' => ApiIndexCode::$code['6015']
            ];
        }
        $out_data['order_no'] = $order_temp['order_no'];
        $out_data['pay_no'] = $order_temp['pay_no'];
        if (!empty($order_data['product_tag'])) {
            $out_data['product_tag'] = explode('|', $order_data['product_tag']);
        } else {
            $out_data['product_tag'] = [];
        }
        $out_data['kid_price'] = Util::getAmountFormat($out_data['kid_price']);
        $out_data['price'] = Util::getAmountFormat($out_data['price']);
        $out_data['amount'] = Util::getAmountFormat($out_data['amount']);
        //获取积分
        $out_data['integral'] = intval($order_data['amount'] * 1);
        $out_data['days_str'] = $spec_data['days_str'];
        $out_data['start_day'] = date("m月d日", strtotime($spec_data['start_day']));
        $out_data['ver_expires_time'] = date("Y-m-d H:i:s", strtotime($spec_data['start_day']) + (86399));
        $out_data['start_week'] = Util::getDayWeek($spec_data['start_day']);
        $out_data['end_day'] = date("m月d日", strtotime($spec_data['end_day']));
        $out_data['end_week'] = Util::getDayWeek($spec_data['end_day']);
        $out_data['contacts'] = json_decode($out_data['contacts'], true);
        $out_data['affirm_time'] = $out_data['affirm_time'] ? date('Y-m-d H:i:s', $out_data['affirm_time']) : '--';
        $out_data['pay_time'] = $out_data['pay_time'] ? date('Y-m-d H:i:s', $out_data['pay_time']) : '--';
        $out_data['ver_time'] = $out_data['ver_time'] ? date('Y-m-d H:i:s', $out_data['ver_time']) : '--';
        $out_data['evaluate_time'] = $out_data['evaluate_time'] ? date('Y-m-d H:i:s', $out_data['evaluate_time']) : '--';
        $out_data['close_time'] = $out_data['close_time'] ? date('Y-m-d H:i:s', $out_data['close_time']) : '--';
        $out_data['refund_apply_time'] = $out_data['refund_apply_time'] ? date('Y-m-d H:i:s', $out_data['refund_apply_time']) : '--';
        $out_data['refund_time'] = $out_data['refund_time'] ? date('Y-m-d H:i:s', $out_data['refund_time']) : '--';
        $out_data['pay_cancel_time'] = strtotime($out_data['create_time']) - SysConfigModel::getConfigKeyValue('OrderCancelTime');
        if ($out_data['pay_cancel_time'] <= 0) {
            $out_data['pay_cancel_time'] = 0;
        }
        $out_data['status_step'] = $this->_getTourismStatusStep($out_data['status'], $out_data);
        $out_data['merchant_info'] = TourismBureauModel::getBureauInfo($out_data['merchant_id']);
        $out_data['policy'] = TourismProductModel::getPolicyInfo($out_data['product_id']);
        return [
            'code' => 1,
            'data' => $out_data
        ];
    }

    /**
     * 获取旅游套餐订单状态步骤
     * @param $status
     * @param $order_data
     */
    public function _getTourismStatusStep($status, $order_data)
    {
        $step = [];
        switch ($status) {
            case OrderTourismModel::STATUS_CANCEL:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_CANCEL],
                    'time' => $order_data['close_time']
                ];
                break;
            case OrderTourismModel::STATUS_AUDIT:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                break;
            case OrderTourismModel::STATUS_PAYMENT:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                break;
            case OrderTourismModel::STATUS_PAID:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                break;
            case OrderTourismModel::STATUS_VERIFY:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_VERIFY],
                    'time' => $order_data['ver_time']
                ];
                break;
            case OrderTourismModel::STATUS_EVALUATED:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_VERIFY],
                    'time' => $order_data['ver_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_EVALUATED],
                    'time' => $order_data['evaluate_time']
                ];
                break;
            case OrderTourismModel::STATUS_REFUNDING:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_apply_time']
                ];
                break;
            case OrderTourismModel::STATUS_REFUND:
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_AUDIT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAYMENT],
                    'time' => $order_data['affirm_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_apply_time']
                ];
                $step[] = [
                    'label' => OrderTourismModel::$STATUS_MAP_APP[OrderTourismModel::STATUS_REFUND],
                    'time' => $order_data['refund_time']
                ];
                break;
        }
        return $step;
    }

    /**
     * 获取订单详情
     * @param $order_id
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsOrderInfo($order_id, $lang = '')
    {
        // `type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
        // `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
        //  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
        //  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
        //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '单价',
        //  `number` int DEFAULT '0' COMMENT '购买数量',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
        //  `contacts` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '收货地址json 数据',
        //  `ver_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
        //  `remarks` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
        //  `status` tinyint DEFAULT '2' COMMENT '订单状态  0订单关闭   2待付款 3待核验/待收货 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
        //  `pay_time` int DEFAULT '0' COMMENT '付款时间',
        //  `ver_time` int DEFAULT '0' COMMENT '核验时间',
        //  `evaluate_time` int DEFAULT '0' COMMENT '评价时间',
        //  `close_time` int DEFAULT '0' COMMENT '订单关闭时间',
        //  `refund_apply_time` int DEFAULT '0' COMMENT '申请退款时间',
        //  `refund_time` int DEFAULT '0' COMMENT '退款时间',
        //  `refuse_time` int DEFAULT '0' COMMENT '拒绝退款时间',
        $field = "id,product_id,product_img,product_name,spec_id,spec_json,price,number,
        amount,contacts,ver_code,remarks,status,pay_time,ver_time,evaluate_time,close_time,
        refund_apply_time,refund_time,refund_sales_time,refund_ing_time,refund_ok_time,send_time,refuse_time,create_time,logistics,refund_logistics";

        $order_data = OrderGoodsModel::where('order_id', $order_id)->field($field)->find();
        if (empty($order_data)) {
            return [
                'code' => 6015,
                'msg' => ApiIndexCode::$code['6015']
            ];
        }
        $out_data = $order_data->toArray();
        if (empty($lang)) {
            // 多语言转换
            $lang = strtoupper(\think\facade\Lang::getLangSet());
        }
        $out_data = GoodsLangModel::getGoodsInfo($out_data, $lang);
        $out_data['spec_json'] = json_decode($order_data['spec_json'], true);
        if (!empty($out_data['spec_json']['spec'])) {
            $spec_arr = explode(';', $out_data['spec_json']['spec']);
            $spec_arr_new = [];
            foreach ($spec_arr as $k1 => $v1) {
                $spec_arr_ = explode('|', $v1);
                $spec_arr_s = [];
                foreach ($spec_arr_ as $k2 => $v2) {
                    if (isChinese($v2)) {
                        $spec_arr_s[] = lang($v2);
                    } else {
                        $spec_arr_s[] = $v2;
                    }
                }
                $spec_arr_new[] = implode('|', $spec_arr_s);
            }
            $out_data['spec_json']['spec'] = implode(';', $spec_arr_new);
        }

        $out_data['spec_json']['integral'] = intval($out_data['spec_json']['price']);
        $order_temp = OrderModel::where('id', $order_id)->field('product_id,merchant_id,type,order_no,pay_no,freight_amount,refund_remark,return_explain,express_type,take_day')->find();
        if (empty($order_temp)) {
            return [
                'code' => 6015,
                'msg' => ApiIndexCode::$code['6015']
            ];
        }
        $out_data['order_no'] = $order_temp['order_no'];
        $out_data['type'] = $order_temp['type'];
        $out_data['refund_remark'] = $order_temp['refund_remark'];
        $out_data['return_explain'] = $order_temp['return_explain'];
        $out_data['pay_no'] = $order_temp['pay_no'];
        $out_data['freight_amount'] = $order_temp['freight_amount'];
        $out_data['express_type'] = $order_temp['express_type'];
        $out_data['take_day'] = $order_temp['take_day'];
        $out_data['express_address'] = GoodsProductModel::getProductInfo($order_temp['product_id'], 'province,city,county,address,lng,lat');
        //获取积分
        $out_data['price'] = Util::getAmountFormat($order_data['price']);
        $out_data['amount'] = Util::getAmountFormat($order_data['amount']);
        $out_data['integral'] = intval($order_data['amount']);
        $out_data['contacts'] = json_decode($out_data['contacts'], true);
        $out_data['pay_time'] = $out_data['pay_time'] ? date('Y-m-d H:i:s', $out_data['pay_time']) : '--';
        $out_data['send_time'] = $out_data['send_time'] ? date('Y-m-d H:i:s', $out_data['send_time']) : '--';
        $out_data['ver_time'] = $out_data['ver_time'] ? date('Y-m-d H:i:s', $out_data['ver_time']) : '--';
        $out_data['evaluate_time'] = $out_data['evaluate_time'] ? date('Y-m-d H:i:s', $out_data['evaluate_time']) : '--';
        $out_data['close_time'] = $out_data['close_time'] ? date('Y-m-d H:i:s', $out_data['close_time']) : '--';
        $out_data['refund_apply_time'] = $out_data['refund_apply_time'] ? date('Y-m-d H:i:s', $out_data['refund_apply_time']) : '--';
        $out_data['refund_sales_time'] = $out_data['refund_sales_time'] ? date('Y-m-d H:i:s', $out_data['refund_sales_time']) : '--';
        $out_data['refund_time'] = $out_data['refund_time'] ? date('Y-m-d H:i:s', $out_data['refund_time']) : '--';
        $out_data['refund_ing_time'] = $out_data['refund_ing_time'] ? date('Y-m-d H:i:s', $out_data['refund_ing_time']) : '--';
        $out_data['pay_cancel_time'] = strtotime($out_data['create_time']) - SysConfigModel::getConfigKeyValue('OrderCancelTime');
        if ($out_data['pay_cancel_time'] <= 0) {
            $out_data['pay_cancel_time'] = 0;
        }

        if (!empty($out_data['return_explain'])) {
            $out_data['return_explain'] = json_decode($out_data['return_explain'], true);
        } else {
            $out_data['return_explain'] = [];
        }
        //物流信息
        if (!empty($out_data['logistics'])) {
            $out_data['logistics'] = json_decode($out_data['logistics'], true);
        } else {
            $out_data['logistics'] = [];
        }
        if (!empty($out_data['refund_logistics'])) {
            $out_data['refund_logistics'] = json_decode($out_data['refund_logistics'], true);
        } else {
            $out_data['refund_logistics'] = [];
        }
        $out_data['status_step'] = $this->_getGoodsStatusStep($out_data['status'], $out_data);
        $out_data['merchant_address'] = TourismBureauModel::getBureauAddress($order_temp['merchant_id']);
        return [
            'code' => 1,
            'data' => $out_data
        ];
    }

    /**
     * 获取订单详情
     * @param $order_id
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGoodsOrderInfos($order_id)
    {
        if (strpos($order_id, ',') != false) {
            $order_id_arr = explode(',', $order_id);
        } else {
            $order_id_arr[] = $order_id;
        }
        $order_list = $this->whereIn('id', $order_id_arr)->where('status', '=', OrderPayModel::STATUS_UNPAID)->field('id,spec_json,amount,status')->select();
        if (empty($order_list)) {
            return [];
        }
        $order_list = $order_list->toArray();
        $spec_list = [];
        $spec_all = [
            "freight_amount" => 0,
            "all_amount" => 0,
            "amount" => 0,
            "all_integral_amount" => 0,
            "integral_amount" => 0
        ];
        $lang = strtoupper(\think\facade\Lang::getLangSet());
        foreach ($order_list as $key => $value) {
            $spec_json = json_decode($value['spec_json'], true);
            $spec_json['name'] = GoodsProductModel::getProductName($spec_json['product_id'], $lang);
            //颜色|白色;尺寸|M"
            $spec_arr = explode(';', $spec_json['spec']);
            $spec_arr_new = [];
            foreach ($spec_arr as $k1 => $v1) {
                $spec_arr_ = explode('|', $v1);
                $spec_arr_s = [];
                foreach ($spec_arr_ as $k2 => $v2) {
                    if (isChinese($v2)) {
                        $spec_arr_s[] = lang($v2);
                    } else {
                        $spec_arr_s[] = $v2;
                    }
                }
                $spec_arr_new[] = implode('|', $spec_arr_s);
            }
            $spec_json['spec'] = implode(';', $spec_arr_new);
            $spec_list[] = $spec_json;
            $spec_all['amount'] += $value['amount'];
            $spec_all['all_amount'] += $value['amount'];
        }
        $spec_all['all_integral_amount'] = intval($spec_all['all_amount']);
        $spec_all['integral_amount'] = intval($spec_all['amount']);

        $pay_type = PaymentChannelModel::field(['id', 'name', 'ident' => 'type', 'src' => 'icon'])->where('status', 1)->select();
        $pay_list = [];
        foreach ($pay_type as $key => $value) {
            if (!in_array($value['type'], [OrderPayModel::PAY_TYPE_BALANCE, OrderPayModel::PAY_TYPE_PROXY])) {
                $pay_list[] = $value;
            }
        }
        return [
            'pay_list' => $pay_list,
            'spec_list' => $spec_list,
            'spec_all' => $spec_all,
            'express_type' => 1
        ];
    }

    /**
     * 获取商品订单状态步骤
     * @param $status
     * @param $order_data
     */
    public function _getGoodsStatusStep($status, $order_data)
    {
        $step = [];
        switch ($status) {
            case OrderGoodsModel::STATUS_CANCEL:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_CANCEL],
                    'time' => $order_data['close_time']
                ];
                break;
            case OrderGoodsModel::STATUS_PAYMENT:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                break;
            case OrderGoodsModel::STATUS_PAID:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                break;
            case OrderGoodsModel::STATUS_SEND:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                break;
            case OrderGoodsModel::STATUS_VERIFY:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_VERIFY],
                    'time' => $order_data['ver_time']
                ];
                break;
            case OrderGoodsModel::STATUS_EVALUATED:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_VERIFY],
                    'time' => $order_data['ver_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_EVALUATED],
                    'time' => $order_data['evaluate_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUNDING:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_apply_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUND:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_apply_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUND],
                    'time' => $order_data['refund_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUSE:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_apply_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUSE],
                    'time' => $order_data['refuse_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUNDSALES:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDSALES],
                    'time' => $order_data['refund_sales_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUND_OK:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDSALES],
                    'time' => $order_data['refund_sales_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUND_OK],
                    'time' => $order_data['refund_ok_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUNDSALESING:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_sales_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUND_OK],
                    'time' => $order_data['refund_ok_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDSALESING],
                    'time' => $order_data['refund_ing_time']
                ];
                break;
            case OrderGoodsModel::STATUS_RETURNSALES_OK:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_sales_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUND_OK],
                    'time' => $order_data['refund_ok_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDSALESING],
                    'time' => $order_data['refund_ing_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_RETURNSALES_OK],
                    'time' => $order_data['refund_end_time']
                ];
                break;
            case OrderGoodsModel::STATUS_REFUSESALES:
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAYMENT],
                    'time' => $order_data['create_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_PAID],
                    'time' => $order_data['pay_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_SEND],
                    'time' => $order_data['send_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUNDING],
                    'time' => $order_data['refund_sales_time']
                ];
                $step[] = [
                    'label' => OrderGoodsModel::$STATUS_MAP_APP[OrderGoodsModel::STATUS_REFUSESALES],
                    'time' => $order_data['refuse_time']
                ];
                break;
        }
        return $step;
    }

    /**
     * 删除订单
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delOrder($id)
    {
        $temp = $this->where('id', $id)->find();
        $ret = $this->destroy(['id' => $id]);
        if ($ret) {
            switch ($temp['type']) {
                case self::TYPE_TOURISM:
                    $order_tourism = new OrderTourismModel();
                    $ret = $order_tourism->destroy(['order_id' => $id]);
                    break;
                case self::TYPE_NORMAL:
                case self::TYPE_INTEGRAL:
                    $order_goods = new OrderGoodsModel();
                    $ret = $order_goods->destroy(['order_id' => $id]);
                    break;
            }
        }
        return $ret ? true : false;
    }

    /**
     * 获取订单号
     * @param int $type
     * @return string
     */
    private function _getOrderNo($type = self::TYPE_TOURISM)
    {
        $order_no = date('mdHis') . mt_rand(1000, 9999);
        $prefix = '';
        switch ($type) {
            case self::TYPE_TOURISM:
                $prefix = 'T';
                break;
            case self::TYPE_NORMAL:
                $prefix = 'G';
                break;
            case self::TYPE_INTEGRAL:
                $prefix = 'I';
                break;
        }
        return $prefix . $order_no;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
