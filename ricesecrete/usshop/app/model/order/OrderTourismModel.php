<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\Util;
use app\model\tourism\TourismWorkerModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderTourismModel
 * Class OrderTourismModel
 * @package app\model
 */
class OrderTourismModel extends BaseModel
{
    use SoftDelete;

    //订单状态  0订单关闭  1待确认 2待付款 3待核验 4待评价 5退款中 6退款成功 7订单完成 8删除订单
    const STATUS_CANCEL = 0;//订单关闭
    const STATUS_AUDIT = 1;//待确认
    const STATUS_PAYMENT = 2;//待付款
    const STATUS_PAID = 3;//待核验
    const STATUS_VERIFY = 4;//待评价
    const STATUS_REFUNDING = 5;//退款中
    const STATUS_REFUND = 6;//退款成功
    const STATUS_EVALUATED = 7;//订单完成
    const STATUS_DELETE = 8;//用户删除订单
    const STATUS_REFUSE = 9;//订单拒绝退款

    static $STATUS_MAP = [
        self::STATUS_CANCEL => "订单关闭",
        self::STATUS_AUDIT => "待确认",
        self::STATUS_PAYMENT => "待付款",
        self::STATUS_PAID => "待核验",
        self::STATUS_VERIFY => "待评价",
        self::STATUS_REFUNDING => "退款中",
        self::STATUS_REFUND => "退款成功",
        self::STATUS_EVALUATED => "订单完成",
        self::STATUS_DELETE => "删除订单",
        self::STATUS_REFUSE => "订单拒绝",
    ];

    static $STATUS_MAP_APP = [
        self::STATUS_CANCEL => '已取消订单',
        self::STATUS_AUDIT => '已报名，待审核',
        self::STATUS_PAYMENT => '已审核，待付款',
        self::STATUS_PAID => '已付款，待核验',
        self::STATUS_VERIFY => '已核验，待评价',
        self::STATUS_REFUNDING => '申请退款',
        self::STATUS_REFUND => '退款成功',
        self::STATUS_EVALUATED => '订单完成',
        self::STATUS_DELETE => '用户删除订单',
        self::STATUS_REFUSE => '拒绝退款'
    ];

    protected $name = 'order_tourism';

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
        //  `merchant_id` int DEFAULT '0' COMMENT '服务商家id',
        //  `spec_id` int DEFAULT '0' COMMENT '规格属性id',
        //  `spec_json` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '规格属性基本数据json值',
        //  `kid_price` decimal(20,2) DEFAULT '0.00' COMMENT '儿童票价',
        //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '成人票价',
        //  `kid_number` int DEFAULT '0' COMMENT '儿童订购数量',
        //  `number` int DEFAULT '0' COMMENT '成人订购数量',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '订单总金额',
        //  `contacts` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系人信息json 数据',
        //  `ver_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '核验码',
        //  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',
        //  `status` tinyint DEFAULT '0' COMMENT '订单状态  0订单关闭  1待确认 2待付款 3待核验 4待评价 5退款中 6退款成功 7订单完成 8删除订单',
        if (empty($data['order_id']) || empty($data['product_id']) || empty($data['spec_id'])) {
            return false;
        }
        $order_data['order_id'] = $data['order_id'];//订单id
        $order_data['user_id'] = $data['user_id'];//用户id
        $order_data['product_id'] = $data['product_id'];//产品id
        $order_data['product_img'] = $data['product_img'];//产品图片
        $order_data['product_name'] = $data['product_name'];//产品名称
        $order_data['product_tag'] = $data['product_tag'];//产品名称
        $order_data['merchant_id'] = $data['merchant_id'];//商家id
        $order_data['spec_id'] = $data['spec_id'];//规格属性id
        $order_data['spec_json'] = $data['spec_json'];//规格属性基本数据json值
        $order_data['kid_price'] = $data['kid_price'];//儿童票价
        $order_data['price'] = $data['price'];//成人票价
        $order_data['kid_number'] = $data['kid_number'];//儿童订购数量
        $order_data['number'] = $data['number'];//成人订购数量
        $order_data['amount'] = $data['amount'];//订单总金额
        $order_data['contacts'] = json_encode($data['contacts']);//联系人信息json 数据
        $order_data['remarks'] = $data['remarks'];//备注信息
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
                break;
            case self::STATUS_PAYMENT://待付款
                $up_data_item['status'] = self::STATUS_PAYMENT;//待付款
                $up_data_item['affirm_time'] = time();//关闭时间
                $up_data['status'] = self::STATUS_PAYMENT;
                break;
            case self::STATUS_PAID://已付款
                $up_data_item['status'] = self::STATUS_PAID;//已付款
                $up_data_item['pay_time'] = time();//付款时间
                $up_data_item['ver_code'] = $this->getVerCode($order_item['product_id']);//核验码;
                $up_data['status'] = self::STATUS_PAID;
                break;
            case self::STATUS_VERIFY://待评价(核验)
                $up_data_item['status'] = self::STATUS_VERIFY;//待评价(核验)
                $up_data_item['ver_time'] = time();//核验时间
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
                $up_data_item['status'] = self::STATUS_PAID;//返回待核验状态
                $up_data_item['refuse_time'] = time();//退款取消时间
                $up_data['status'] = self::STATUS_PAID;//返回待核验状态
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
     * 添加服务人员（接待核销）电话
     * @param $id
     * @param $phone
     * @return bool
     */
    public static function addServeMobile($id, $phone)
    {
        $map = [
            'id' => $id
        ];
        $order_item = OrderModel::where($map)->field('merchant_id')->find();
        if (empty($order_item)) {
            return false;
        }
        $map = [
            'bureau_id' => $order_item['merchant_id'],
            'phone' => $phone
        ];
        $work_data = TourismWorkerModel::where($map)->find();
        if (empty($work_data)) {
            return false;
        }
        // `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作人员 账号',
        //  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '工作人员姓名',
        $serve_mobile = [
            'phone' => $work_data['phone'],
            'name' => $work_data['name']
        ];
        $ret = OrderModel::where(['id' => $id])->update([
            'serve_mobile' => $phone,
            'serve_json' => json_encode($serve_mobile)
        ]);
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 获取订单联系人
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
            return [
                'name' => '',
                'mobile' => ''
            ];
        }
        return json_decode($data['contacts'], true);

    }

    /**
     * 获取服务人员电话
     * @param string $order_id
     * @return mixed|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderServe($order_id = '')
    {
        $map = [
            'order_id' => $order_id,
        ];
        $data = self::where($map)->field('serve_mobile')->find();
        if (empty($data) || empty($data['serve_mobile'])) {
            return [
                'name' => '',
                'phone' => ''
            ];
        }
        return json_decode($data['serve_mobile'], true);
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
        $data = self::where($map)->whereIn('status', [self::STATUS_AUDIT, self::STATUS_PAYMENT, self::STATUS_PAID])->field('sum(kid_number) as all_kid_number,sum(number) as all_number')->find();
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
        $data = self::where($map)->whereIn('status', [self::STATUS_AUDIT, self::STATUS_PAYMENT])->count();
        if (empty($data)) {
            return 0;
        }
        return $data;
    }
}
