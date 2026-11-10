<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\Util;
use app\model\goods\GoodsStoreModel;
use think\facade\Cache;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderGoodsModel
 * Class OrderGoodsModel
 * @package app\model
 */
class OrderGoodsModel extends BaseModel
{
    use SoftDelete;

    //订单状态  0订单关闭 1待付款 2待发货 3待签收 4待评价 5退款中 6退款成功 7订单完成 8删除订单 9拒绝退款 10申请退货退款 11同意退款退货 12退货物流中 13退货退款完成 14拒绝退货退款
    const STATUS_CANCEL = 0;//订单关闭
    const STATUS_PAYMENT = 1;//待付款
    const STATUS_PAID = 2;//待发货
    const STATUS_SEND = 3;//待签收
    const STATUS_VERIFY = 4;//待评价
    const STATUS_REFUNDING = 5;//申请退款
    const STATUS_REFUND = 6;//退款成功
    const STATUS_EVALUATED = 7;//订单完成
    const STATUS_DELETE = 8;//用户删除订单
    const STATUS_REFUSE = 9;//订单拒绝退款

    const STATUS_REFUNDSALES = 10;//申请退货退款
    const STATUS_REFUND_OK = 11;//同意退款退货
    const STATUS_REFUNDSALESING = 12;//退货物流中
    const STATUS_RETURNSALES_OK = 13;//退货退款完成
    const STATUS_REFUSESALES = 14;//拒绝退货退款


    static $STATUS_MAP = [
        self::STATUS_CANCEL => '订单关闭',
        self::STATUS_PAYMENT => '待付款',
        self::STATUS_PAID => '待发货',
        self::STATUS_SEND => '待签收',
        self::STATUS_VERIFY => '待评价',
        self::STATUS_REFUNDING => '申请退款',
        self::STATUS_REFUND => '退款成功',
        self::STATUS_EVALUATED => '订单完成',
        self::STATUS_DELETE => '用户删除订单',
        self::STATUS_REFUSE => '拒绝退款',

        self::STATUS_REFUNDSALES => '申请退货退款',
        self::STATUS_REFUND_OK => '同意退款退货',
        self::STATUS_REFUNDSALESING => '退款退货物流中',
        self::STATUS_RETURNSALES_OK => '退货退款完成',
        self::STATUS_REFUSESALES => '拒绝退货退款'
    ];
    static $STATUS_MAP_APP = [
        self::STATUS_CANCEL => '已取消订单',
        self::STATUS_PAYMENT => '已下单，待付款',
        self::STATUS_PAID => '已付款，待发货',
        self::STATUS_SEND => '已发货，待收货',
        self::STATUS_VERIFY => '已收货，待评价',
        self::STATUS_REFUNDING => '申请退款',
        self::STATUS_REFUND => '退款成功',
        self::STATUS_EVALUATED => '订单完成',
        self::STATUS_DELETE => '用户删除订单',
        self::STATUS_REFUSE => '拒绝退款',

        self::STATUS_REFUNDSALES => '申请退货退款',
        self::STATUS_REFUND_OK => '同意退款退货',
        self::STATUS_REFUNDSALESING => '退款退货物流中',
        self::STATUS_RETURNSALES_OK => '退货退款完成',
        self::STATUS_REFUSESALES => '拒绝退货退款'
    ];

    protected $name = 'order_goods';

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
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 添加旅游套餐订单
     * @param $data
     * @return array|false|int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addOrderItem($data)
    {
        //`order_id` int NOT NULL DEFAULT '0' COMMENT '订单id',
        // `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `product_id` int DEFAULT '0' COMMENT '产品id  （套餐id 商品id 积分商品id）',
        //  `product_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品图片',
        //  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '产品名称',
        //  `product_tag` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标签',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
        //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '单价',
        //  `number` int DEFAULT '0' COMMENT '购买数量',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
        //  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人信息json 数据',
        //  `ver_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
        //  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
        //  `status` tinyint DEFAULT '2' COMMENT '订单状态  0订单关闭  2待付款 3待核验 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
        if (empty($data['order_id']) || empty($data['product_id']) || empty($data['spec_id'])) {
            return false;
        }
        $order_data['order_id'] = $data['order_id'];//订单id
        $order_data['user_id'] = $data['user_id'];//用户id
        $order_data['product_id'] = $data['product_id'];//产品id
        $order_data['product_img'] = $data['product_img'];//产品图片
        $order_data['product_name'] = $data['product_name'];//产品名称
        $order_data['spec_id'] = $data['spec_id'];//规格属性id
        $order_data['spec_json'] = $data['spec_json'];//规格属性基本数据json值
        $order_data['price'] = $data['price'];//单价
        $order_data['number'] = $data['number'];//购买数量
        $order_data['amount'] = $data['amount'];//订单总金额
        $order_data['contacts'] = json_encode($data['contacts']);//联系人信息json 数据
        $order_data['remarks'] = $data['remarks'];//备注信息
        $order_data['status'] = self::STATUS_PAYMENT;//待付款
        $order_data['create_time'] = time();
        $order_data['update_time'] = time();
        $id = $this->insertGetId($order_data);
        if (!empty($id)) {
            return $id;
        }
        return false;
    }


    /**
     * 修改订单状态
     * @param $order_id
     * @param $status
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function changeOrderStatus($order_id, $status)
    {
        $order_item = $this->where('order_id', $order_id)->find();
        if (empty($order_item)) {
            return false;
        }
        $order_product_key = Cache::get(md5($order_id), '');
        if (!empty($order_product_key)) {
            Cache::delete($order_product_key);
        }
        $up_data_item = [
            'update_time' => time(),
        ];
        $up_data = [
            'update_time' => time(),
        ];
        switch ($status) {
            case self::STATUS_CANCEL://订单关闭
                $up_data_item['status'] = self::STATUS_CANCEL;//订单关闭
                $up_data_item['close_time'] = time();//关闭时间
                $up_data['status'] = self::STATUS_CANCEL;
                //退还库存
                GoodsStoreModel::changeStore($order_item['product_id'], $order_item['spec_id'], $order_item['number'], GoodsStoreModel::TYPE_RETURN, $order_item['user_id']);
                break;
            case self::STATUS_PAID://已付款
                $up_data_item['status'] = self::STATUS_PAID;//已付款
                $up_data_item['pay_time'] = time();//付款时间
                $up_data_item['ver_code'] = $this->getVerCode($order_item['product_id']);//核验码;
                $up_data['status'] = self::STATUS_PAID;
                break;
            case self::STATUS_SEND://已发货
                $up_data_item['status'] = self::STATUS_SEND;//已发货
                $up_data_item['send_time'] = time();//发货时间
                $up_data['status'] = self::STATUS_SEND;
                break;
            case self::STATUS_VERIFY://待评价(已收货)
                $up_data_item['status'] = self::STATUS_VERIFY;//待评价(已收货)
                $up_data_item['ver_time'] = time();//收货时间
                $up_data['status'] = self::STATUS_VERIFY;
                break;
            case self::STATUS_EVALUATED://已完成
                $up_data_item['status'] = self::STATUS_EVALUATED;//待评价(核验)
                $up_data_item['evaluate_time'] = time();//完成时间
                $up_data['status'] = self::STATUS_EVALUATED;
                break;
            case self::STATUS_REFUNDING://退款中
                $up_data_item['status'] = self::STATUS_REFUNDING;//退款中
                $up_data_item['refund_apply_time'] = time();//退款中时间
                $up_data['status'] = self::STATUS_REFUNDING;
                break;
            case self::STATUS_REFUND://退款完成
                $up_data_item['status'] = self::STATUS_REFUND;//退款完成
                $up_data_item['refund_time'] = time();//退款完成时间
                $up_data['status'] = self::STATUS_REFUND;
                break;
            case self::STATUS_DELETE://删除订单
                $up_data_item['status'] = self::STATUS_DELETE;//用户删除订单
                $up_data_item['user_deltime'] = time();//用户删除数据
                $up_data['status'] = self::STATUS_DELETE;
                break;
            case self::STATUS_REFUSE://拒绝退款
                $up_data_item['status'] = self::STATUS_REFUSE;//拒绝退款
                $up_data_item['refuse_time'] = time();//拒绝时间
                $up_data['status'] = self::STATUS_REFUSE;//拒绝退款
                break;

            case self::STATUS_REFUNDSALES://申请退货退款
                $up_data_item['status'] = self::STATUS_REFUNDSALES;//申请退货退款
                $up_data_item['refund_sales_time'] = time();//申请退货退款时间
                $up_data['status'] = self::STATUS_REFUNDSALES;//申请退货退款
                break;
            case self::STATUS_REFUND_OK://同意退款/退货
                $up_data_item['status'] = self::STATUS_REFUND_OK;//同意退款/退货
                $up_data_item['refund_ok_time'] = time();//同意时间
                $up_data['status'] = self::STATUS_REFUND_OK;//同意退款/退货
                break;
            case self::STATUS_REFUNDSALESING://退货退款物流中
                $up_data_item['status'] = self::STATUS_REFUNDSALESING;//退货退款物流中
                $up_data_item['refund_ing_time'] = time();//退货退款物流中时间
                $up_data['status'] = self::STATUS_REFUNDSALESING;//退货退款物流中
                break;
            case self::STATUS_RETURNSALES_OK://退货退款成功
                $up_data_item['status'] = self::STATUS_RETURNSALES_OK;//退货退款成功
                $up_data_item['refund_time'] = time();//退货退款成功时间
                $up_data['status'] = self::STATUS_RETURNSALES_OK;//退货退款成功
                break;
            case self::STATUS_REFUSESALES://拒绝退货退款
                $up_data_item['status'] = self::STATUS_REFUSESALES;//拒绝退货退款
                $up_data_item['refuse_time'] = time();//拒绝退货退款时间
                $up_data['status'] = self::STATUS_REFUSESALES;//拒绝退货退款
                break;
        }
        if (!empty($up_data_item)) {
            $this->startTrans();
            $ret = $this->where('id', $order_item['id'])->update($up_data_item);
            if ($ret) {
                if (!empty($up_data)) {
                    $order_model = new OrderModel();
                    $retx = $order_model->where('id', $order_item['order_id'])->update($up_data);
                    if ($retx) {
                        $this->commit();
                        return true;
                    } else {
                        $this->rollback();
                        return false;
                    }
                }
            }
            $this->rollback();
        }
        return false;
    }

    /**
     * 获取核验码
     * @param string $product_id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getVerCode($product_id = '')
    {
        $code = Util::getRandomString(6, '0123456789');
        $where = [
            'product_id' => $product_id,
            'ver_code' => $code,
        ];
        $temp = $this->where($where)->find();
        if (!empty($temp)) {
            $code = Util::getRandomString(6, '0123456789');
            $where = [
                'product_id' => $product_id,
                'ver_code' => $code,
            ];
            $temp = $this->where($where)->find();
            if (!empty($temp)) {
                $code = Util::getRandomString(6, '0123456789');
            }
        }
        return $code;
    }

    /**
     * 获取订单收货人
     * @param string $order_id
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderContacts($order_id = '')
    {
        $map = [
            'order_id' => $order_id,
        ];
        $data = self::where($map)->field('contacts')->find();
        if (empty($data)) {
            // `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '收货人',
            //  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '收货人电话',
            //  `province` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '省编码',
            //  `city` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '市编码',
            //  `area` char(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '区编码',
            //  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '详细地址',
            return [
                'name' => '',
                'mobile' => '',
                'province' => '',
                'city' => '',
                'area' => '',
                'address' => '',
            ];
        }
        return json_decode($data['contacts'], true);

    }

    /**
     * 获取物流信息
     * @param string $order_id
     * @return mixed|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderLogistics($order_id = '')
    {
        $map = [
            'order_id' => $order_id,
        ];
        $data = self::where($map)->field('logistics')->find();
        if (empty($data)) {
            return [
                'logistics_id' => '',//物流id
                'logistics_name' => '',//物流名称
                'time' => '',//发货时间
                'code' => '',//发货单号
            ];
        }
        return json_decode($data['logistics'], true);
    }

    /**
     * 获取产品总下单人数
     * @param string $product_id
     * @param string $spec_id
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderItemNum($product_id = '', $spec_id = '')
    {
        $map = [
            'product_id' => $product_id,
            'spec_id' => $spec_id
        ];
        $data = self::where($map)->where('status', '>=', self::STATUS_PAYMENT)->field('sum(kid_number) as all_kid_number,sum(number) as all_number')->find();
        if (empty($data)) {
            return 0;
        }
        return $data['all_number'] + $data['all_kid_number'];
    }

    /**
     * 获取用户未支付订单数量
     * @param string $user_id
     * @return int
     * @throws \think\db\exception\DbException
     */
    public static function getOrderItemNoPay($user_id = '')
    {
        $map = [
            'user_id' => $user_id
        ];
        $data = self::where($map)->whereIn('status', [self::STATUS_PAYMENT])->count();
        if (empty($data)) {
            return 0;
        }
        return $data;
    }
}
