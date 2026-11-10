<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\Alipay\Alipay;
use app\libs\api\ApiIndexCode;
use app\libs\WechatPay;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderPayModel
 * Class OrderPayModel
 * @package app\model
 */
class OrderPayModel extends BaseModel
{
    use SoftDelete;

    //支付状态 0取消支付 1待支付 2支付成功 3支付失败 4退款成功
    const STATUS_CANCEL = 0;//取消支付
    const STATUS_UNPAID = 1;//待支付
    const STATUS_PAID = 2;//支付成功
    const STATUS_FAIL = 3;//支付失败
    const STATUS_REFUND = 4;//退款成功

    static $STATUS_MAP = [
        self::STATUS_CANCEL => "取消支付",//取消支付
        self::STATUS_UNPAID => "待支付",//待支付
        self::STATUS_PAID   => "支付成功",//支付成功
        self::STATUS_FAIL   => "支付失败",//支付失败
        self::STATUS_REFUND => "退款成功",//退款成功
    ];

    //支付方式 weixin微信支付,alipay支付宝支付,unionpay银联支付
    const PAY_TYPE_WEIXIN = 'weixin';//微信支付
    const PAY_TYPE_ALIPAY = 'alipay';//支付宝支付
    const PAY_TYPE_UNIONPAY = 'unionpay';//银联支付
    const PAY_TYPE_INTEGRAL = 'integral';//积分兑换
    const PAY_TYPE_BALANCE = 'balance';//使用余额支付
    const PAY_TYPE_PROXY = 'proxy';//其他人代支付

    static $PAY_TYPE_MAP = [
//        self::PAY_TYPE_WEIXIN => "weixin",//微信支付
//        self::PAY_TYPE_ALIPAY => "alipay",//支付宝支付
//        self::PAY_TYPE_UNIONPAY => "unionpay",//银联支付
//        self::PAY_TYPE_INTEGRAL => "integral",//积分兑换
        self::PAY_TYPE_BALANCE => "balance",//余额
        self::PAY_TYPE_PROXY   => "proxy",//其他人代支付
    ];
    static $PAY_TYPE_STR_MAP = [
//        self::PAY_TYPE_WEIXIN => "微信支付",//微信支付
//        self::PAY_TYPE_ALIPAY => "支付宝支付",//支付宝支付
//        self::PAY_TYPE_UNIONPAY => "银联支付",//银联支付
//        self::PAY_TYPE_INTEGRAL => "积分兑换",//积分兑换
        self::PAY_TYPE_BALANCE => "Wallet",//余额支付
        self::PAY_TYPE_PROXY   => "Find someone to pay",//找人代付
    ];

    static $PAY_TYPE_LOG_MAP = [
        self::PAY_TYPE_BALANCE => "https://usheen.s3.amazonaws.com/goods_type/2025-02-26/ae8369de7fca1490df4beee8e9383d91744ccd56.png",//余额支付 log
        self::PAY_TYPE_PROXY => "https://usheen.s3.amazonaws.com/goods_type/2025-02-26/f0ef1170d535dfcc6c7d8a2dd7798a98be4f71b8.png",//找人代付 log
    ];

    protected $name = 'order_pay';

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
            ->withSearch(['team'], [
                'team' => 'user_id'
            ])
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $statusArr = self::$STATUS_MAP;
            $status_str = '';
            if (isset($statusArr[$item['status']])) {
                $status_str = $statusArr[$item['status']];
            }
            $item['status_str'] = $status_str;
            $paytypeArr = self::$PAY_TYPE_STR_MAP;
            $pay_type_str = '';
            if (isset($paytypeArr[$item['pay_type']])) {
                $pay_type_str = $paytypeArr[$item['pay_type']];
            }
            $item['pay_type_str'] = $pay_type_str;
            $item['user_info'] = UserModel::getNameHead($item['user_id']);
            $item['pay_time_str'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : '--';
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取支付信息
     * @param string $order_no
     * @param string $pay_no
     * @param string $pay_type
     * @param int $order_type
     * @return OrderPayModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayInfo($order_no = '', $pay_no = '', $pay_type = self::PAY_TYPE_WEIXIN, $order_type = OrderModel::TYPE_TOURISM)
    {
        $map = [
            'pay_type' => $pay_type,
            'order_type' => $order_type
        ];
        if (!empty($order_no)) {
            $map['order_no'] = $order_no;
        }
        if (!empty($pay_no)) {
            $map['pay_no'] = $pay_no;
        }
        return $this->where($map)->find();
    }

    /**
     * 添加支付信息
     * @param array $data
     * @return array|false
     */
    public function addPayInfo($data = [])
    {
//        $data = [
//            'order_no' => $data['order_no'],
//            'pay_type' => $data['pay_type'],
//            'order_type' => $data['order_type'],
//            'user_id' => $data['user_id'],
//            'pay_amount' => $data['pay_amount'],
//            'merchant_id' => $data['merchant_id'],
//        ];
        //  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
        //  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
        //  `pay_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付方式  weixin微信支付,alipay支付宝支付,unionpay银联支付',
        //  `order_type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `pay_amount` decimal(20,2) DEFAULT '0.00' COMMENT '实际支付金额',
        //  `refund_amount` decimal(20,2) DEFAULT '0.00' COMMENT '退款金额',
        //  `status` tinyint DEFAULT '1' COMMENT '订单状态 0取消支付 1待支付 2支付成功 3退款成功',
        //  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
        //  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
        //  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
        $map = [
            'order_no' => $data['order_no']
        ];
        $temp = $this->where($map)->find();
        $pay_no = $this->_getPayNo($data['order_type']);
        if (empty($temp)) {
            $in_data = [
                'order_no' => $data['order_no'],
                'pay_no' => $pay_no,
                'pay_type' => $data['pay_type'],
                'order_type' => $data['order_type'],
                'user_id' => $data['user_id'],
                'pay_amount' => $data['pay_amount'],
                'merchant_id' => $data['merchant_id'],
                'refund_amount' => 0,
                'status' => self::STATUS_UNPAID,
                'create_day' => date('Y-m-d'),
                'create_week' => date('Y-W'),
                'create_month' => date('Y-m'),
                'update_time' => time(),
                'create_time' => time()

            ];
            $pid = $this->insertGetId($in_data);
        } else {
            if ($temp['status'] == self::STATUS_UNPAID) {
                $up_data = [
                    'pay_no' => $pay_no,
                    'create_day' => date('Y-m-d'),
                    'create_week' => date('Y-W'),
                    'create_month' => date('Y-m'),
                    'update_time' => time(),
                    'create_time' => time()
                ];
                $ret = $this->where($map)->update($up_data);
                if (!$ret) {
                    return [
                        'code' => 6012,
                        'msg' => ApiIndexCode::$code['6012']
                    ];
                }
                $pid = $temp['id'];
            } else {
                return [
                    'code' => 6010,
                    'msg' => ApiIndexCode::$code['6010']
                ];
            }
        }
        if (empty($pid)) {
            return [
                'code' => 6011,
                'msg' => ApiIndexCode::$code['6011']
            ];
        }
        $order_model = new OrderModel();
        $order_model->where('order_no', $data['order_no'])->update(['pay_no' => $pay_no]);
        return [
            'code' => 1,
            'data' => [
                'id' => $pid,
                'pay_type' => $data['pay_type'],
                'product_name' => $data['product_name'],
                'pay_amount' => $data['pay_amount'],
                'pay_no' => $pay_no,
            ]
        ];
    }

    /**
     * 添加支付信息(多单合并支付)
     * @param array $data
     * @return array|false
     */
    public function addPayInfos($data = [])
    {
//        $data = [
//            'order_no' => $data['order_no'],
//            'pay_type' => $data['pay_type'],
//            'order_type' => $data['order_type'],
//            'user_id' => $data['user_id'],
//            'product_name' => $data['product_name'],
//            'pay_amount' => $data['pay_amount'],
//            'merchant_id' => $data['merchant_id'],
//        ];
        //  `order_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '订单编号',
        //  `pay_no` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
        //  `pay_type` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付方式  weixin微信支付,alipay支付宝支付,unionpay银联支付',
        //  `order_type` tinyint DEFAULT '1' COMMENT '订单类型 1旅游套餐 2正常商品 3积分商品',
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `pay_amount` decimal(20,2) DEFAULT '0.00' COMMENT '实际支付金额',
        //  `refund_amount` decimal(20,2) DEFAULT '0.00' COMMENT '退款金额',
        //  `status` tinyint DEFAULT '1' COMMENT '订单状态 0取消支付 1待支付 2支付成功 3退款成功',
        //  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
        //  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
        //  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
        if (empty($data)) {
            return [
                'code' => 6013,
                'msg' => ApiIndexCode::$code['6013']
            ];
        }
        $order_nos = [];
        $pay_type = $data[0]['pay_type'];
        $order_type = $data[0]['order_type'];
        $pay_amount = 0;
        $pids = [];
        $pay_no = $this->_getPayNo($order_type);
        $product_name_arr = [];
        foreach ($data as $k => $v) {
            $order_nos[] = $v['order_no'];
            $pay_amount += $v['pay_amount'];
            $product_name_arr[] = $v['product_name'];
            $temp = $this->where('order_no', $v['order_no'])->find();
            if (empty($temp)) {
                $in_data = [
                    'order_no' => $v['order_no'],
                    'pay_no' => $pay_no,
                    'pay_type' => $v['pay_type'],
                    'order_type' => $v['order_type'],
                    'user_id' => $v['user_id'],
                    'pay_amount' => $v['pay_amount'],
                    'refund_amount' => 0,
                    'status' => self::STATUS_UNPAID,
                    'create_day' => date('Y-m-d'),
                    'create_week' => date('Y-W'),
                    'create_month' => date('Y-m'),
                    'update_time' => time(),
                    'create_time' => time()
                ];
                $pids[] = $this->insertGetId($in_data);
            } else {
                if ($temp['status'] == self::STATUS_UNPAID) {
                    $up_data = [
                        'pay_no' => $pay_no,
                        'create_day' => date('Y-m-d'),
                        'create_week' => date('Y-W'),
                        'create_month' => date('Y-m'),
                        'update_time' => time(),
                        'create_time' => time()
                    ];
                    $ret = $this->where('order_no', $v['order_no'])->update($up_data);
                    if (!$ret) {
                        return [
                            'code' => 6012,
                            'msg' => ApiIndexCode::$code['6012']
                        ];
                    }
                    $pids[] = $temp['id'];
                } else {
                    return [
                        'code' => 6010,
                        'msg' => ApiIndexCode::$code['6010']
                    ];
                }
            }
        }
        $order_model = new OrderModel();
        $order_model->whereIn('order_no', $order_nos)->update(['pay_no' => $pay_no]);
        return [
            'code' => 1,
            'data' => [
                'id' => $pids,
                'pay_type' => $pay_type,
                'product_name' => implode(',', $product_name_arr),
                'pay_amount' => $pay_amount,
                'pay_no' => $pay_no,
            ]
        ];
    }

    /**
     * 完成支付
     * @param string $pay_no
     * @param string $order_no
     * @return bool
     */
    public function endPayInfo($pay_no = '', $order_no = '')
    {
        if (empty($order_no) && empty($pay_no)) {
            return false;
        }
        if (!empty($pay_no)) {
            $map = [
                'pay_no' => $pay_no,
            ];
        } else {
            $map = [
                'order_no' => $order_no,
            ];
        }
        $up_data = [
            'status' => self::STATUS_PAID,
            'pay_time' => time(),
        ];
        $ret = $this->where($map)->update($up_data);
        if ($ret) {
            return true;
        }
        return false;
    }

    /**
     * 添加退款信息
     * @param string $order_no
     * @return bool
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     * @throws \Yansongda\Pay\Exception\ServiceNotFoundException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addRefundInfo($order_no = '')
    {
        $pay_data = $this->where('order_no', $order_no)->find();
        if (empty($pay_data)) {
            return false;
        }
        $pay_no = $pay_data['pay_no'];
        $refund_no = $this->_getRefundNo($pay_data['order_type']);
        $refund_amount = $pay_data['pay_amount'];
        if ($pay_data['pay_type'] == OrderPayModel::PAY_TYPE_WEIXIN) {
            //微信退款
            $ret = (new WechatPay())->refund($pay_no, $refund_no, $refund_amount);
//            $ret = [
//                'return_code' => 'SUCCESS',
//                'result_code' => 'SUCCESS',
//            ];
            if ($ret['return_code'] == 'SUCCESS' && $ret['result_code'] == 'SUCCESS') {
                $up_data = [
                    'refund_no' => $refund_no,
                    'refund_amount' => $refund_amount,
                    'status' => self::STATUS_REFUND,
                    'refund_time' => time(),
                ];
                $ret = $this->where('order_no', $order_no)->update($up_data);
                if ($ret) {
                    return true;
                }
            } else {
                throw new \Exception($ret['err_code_des']);
            }
        }
        if ($pay_data['pay_type'] == OrderPayModel::PAY_TYPE_ALIPAY) {
            //支付宝退款
            $ret = (new Alipay())->refund($pay_no, $refund_no, $refund_amount);
//            $ret = [
//                'alipay_trade_refund_response' =>[
//                    'code' => '10000',
//                    'msg' => 'Success'
//                ]
//            ];
            if ($ret['alipay_trade_refund_response']['code'] == '10000') {
                $up_data = [
                    'refund_no' => $refund_no,
                    'refund_amount' => $refund_amount,
                    'status' => self::STATUS_REFUND,
                    'refund_time' => time(),
                ];
                $ret = $this->where('order_no', $order_no)->update($up_data);
                if ($ret) {
                    return true;
                }
            } else {
                throw new \Exception($ret['alipay_trade_refund_response']['sub_msg']);
            }
        }
        return true;
    }

    /**
     * 获取支付金额
     * @param string $pay_no
     * @return float|int
     */
    public static function getPayAmount($pay_no = '')
    {
        $map = [
            'pay_no' => $pay_no,
            'status' => self::STATUS_UNPAID
        ];
        $pay_amount = self::where($map)->sum('pay_amount');
        if (empty($pay_amount)) {
            return 0;
        }
        return $pay_amount;
    }

    /**
     * 获取支付单号
     * @param int $type
     * @return string
     */
    public function _getPayNo($type = OrderModel::TYPE_TOURISM)
    {
        $order_no = date('mdHis') . mt_rand(1000, 9999);
        $prefix = '';
        switch ($type) {
            case OrderModel::TYPE_TOURISM:
                $prefix = 'PT';
                break;
            case OrderModel::TYPE_NORMAL:
                $prefix = 'PG';
                break;
            case OrderModel::TYPE_INTEGRAL:
                $prefix = 'PI';
                break;
        }
        return $prefix . $order_no;
    }

    /**
     * 获取支付单号
     * @param int $type
     * @return string
     */
    public function _getRefundNo($type = OrderModel::TYPE_TOURISM)
    {
        $order_no = date('mdHis') . mt_rand(1000, 9999);
        $prefix = '';
        switch ($type) {
            case OrderModel::TYPE_TOURISM:
                $prefix = 'RT';
                break;
            case OrderModel::TYPE_NORMAL:
                $prefix = 'RG';
                break;
            case OrderModel::TYPE_INTEGRAL:
                $prefix = 'RI';
                break;
        }
        return $prefix . $order_no;
    }
}
