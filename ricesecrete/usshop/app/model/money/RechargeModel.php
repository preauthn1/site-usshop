<?php

namespace app\model\money;

use app\base\BaseModel;
use app\libs\api\ApiIndexCode;
use app\model\user\UserModel;
use app\repository\MoneyLogRepository;
use think\model\concern\SoftDelete;

/**
 * 模型-RechargeModel
 * Class RechargeModel
 * @package app\model
 */
class RechargeModel extends BaseModel
{

    use SoftDelete;

    //支付状态 0取消支付 1待支付 2支付成功 3支付失败
    const STATUS_CANCEL = 0;//取消支付
    const STATUS_UNPAID = 1;//待支付
    const STATUS_PAID = 2;//支付成功
    const STATUS_FAIL = 3;//支付失败

    static $STATUS_MAP = [
        self::STATUS_CANCEL => "取消支付",//取消支付
        self::STATUS_UNPAID => "待支付",//待支付
        self::STATUS_PAID => "支付成功",//支付成功
        self::STATUS_FAIL => "支付失败",//支付失败
    ];

    protected $name = 'balance';

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
    public function getPageData($map, $current_page, $list_rows, $order = 'create_time DESC', $field = '*')
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
            $item['user_info'] = UserModel::getUserInfo($item['user_id'], 'username,phone,email');
            $item['pay_time'] = $item['pay_time'] ? date('Y-m-d H:i:s', $item['pay_time']) : '';
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 添加充值订单
     * @param $user_id
     * @param $money
     * @return array|boolean
     */
    public function addBalance($user_id, $money)
    {
        //`id` int NOT NULL AUTO_INCREMENT,
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '充值单号',
        //  `pay_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '支付单号',
        //  `amount` decimal(20,2) DEFAULT '0.00' COMMENT '充值金额',
        //  `end_amount` decimal(20,2) DEFAULT '0.00' COMMENT '到账金额',
        //  `status` tinyint DEFAULT '1' COMMENT '支付状态 0取消支付 1待支付 2支付成功 3支付失败',
        //  `pay_time` int DEFAULT '0' COMMENT '支付时间',
        //  `create_day` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '订单创建年月日',
        //  `create_week` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-周',
        //  `create_month` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '年-月',
        //  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
        //  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
        //  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
        $data = [
            'user_id' => $user_id,
            'order_no' => $this->_getBalanceNo(),
            'amount' => $money,
            'end_amount' => $money,
            'status' => self::STATUS_UNPAID,
            'create_day' => date('Ymd'),
            'create_week' => date('Y-W'),
            'create_month' => date('Ym'),
            'create_time' => time(),
            'update_time' => time(),
        ];
        $ret =  $this->insertGetId($data);
        if($ret){
            return $data;
        }
        return false;
    }

    /**
     * 获取充值信息
     * @param string $order_id
     * @param string $order_no
     * @return array|false
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getPayInfo($order_id = '', $order_no = '')
    {
        $map = [];
        if (!empty($order_id)) {
            $map['id'] = $order_id;
        }
        if (!empty($order_no)) {
            $map['order_no'] = $order_no;
        }
        $order_data = $this->where($map)->field('id,order_no,status,user_id,amount')->find();
        if (empty($order_data)) {
            return [
                'code' => 6013,
                'msg' => ApiIndexCode::$code['6013']
            ];
        }
        $pay_no = $this->_getBalancePayNo();
        $pay_data = [
            'pay_no' => $pay_no,
            'update_time' => time(),
        ];
        $this->where($map)->update($pay_data);
        return [
            'order_no' => $order_data['order_no'],
            'pay_no' => $pay_no,
            'user_id' => $order_data['user_id'],
            'product_name' => lang("用户充值"),
            'pay_amount' => $order_data['amount'],
        ];
    }

    /**
     * 支付完成订单
     * @param string $id
     * @param string $pay_no
     * @param string $pay_amount 支付金额
     * @param string $pay_type 支付类型
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function endPay($id = '', $pay_no = '', $pay_amount = 0, $pay_type = '')
    {
        if (empty($id) && empty($pay_no)) {
            return false;
        }
        if (!empty($pay_no)) {
            $map = [
                'pay_no' => $pay_no,
            ];
        } else {
            $map = [
                'id' => $id,
            ];
        }
        $pay_data = RechargeModel::where($map)->find();
        if (empty($pay_data)) {
            return false;
        }
        if ($pay_data['status'] == RechargeModel::STATUS_PAID) {
            //已经支付
            return true;
        }
        $order_data = $this->where($map)->field('id,user_id,status,pay_no,amount')->find();
        if (empty($order_data)) {
            return false;
        }
        //支付金额和实际支付金额不相等
        if (floatval($order_data['amount']) > floatval($pay_amount)) {
            return false;
        }
        $update_data = [
            'status' => RechargeModel::STATUS_PAID,
            'pay_time' => time(),
            'pay_no' => $pay_no,
            'pay_type' => $pay_type,
            'update_time' => time(),
        ];
        $ret = $this->where($map)->update($update_data);
        if (false === $ret) {
            return false;
        }
        $money_log = new MoneyLogRepository($order_data['user_id'], $order_data['amount']);
        $money_log->setFromUserId($order_data['user_id']);
        $money_log->setFromUserMoney($order_data['amount']);
        $money_log->balanceIncome();
        return true;
    }

    /**
     * 取消订单
     * @param string $order_id
     * @param string $order_no 支付金额
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelPay($order_id = '', $order_no = '')
    {
        if (empty($order_id) && empty($order_no)) {
            return false;
        }
        if (!empty($order_no)) {
            $map = [
                'order_no' => $order_no,
            ];
        } else {
            return false;
        }
        $pay_data = RechargeModel::where($map)->find();
        if (empty($pay_data)) {
            return false;
        }
        if ($pay_data['status'] == RechargeModel::STATUS_PAID) {
            //已经支付
            return true;
        }
        $order_data = $this->where($map)->field('id,status,pay_no,amount')->find();
        if (empty($order_data)) {
            return false;
        }
        $update_data = [
            'status' => RechargeModel::STATUS_CANCEL,
            'pay_time' => time(),
            'update_time' => time(),
        ];
        $this->where($map)->update($update_data);
        return true;
    }


    /**
     * 获取订单号
     * @return string
     */
    private function _getBalanceNo()
    {
        $order_no = date('mdHis') . mt_rand(1000, 9999);
        $prefix = 'B';
        return $prefix . $order_no;
    }

    /**
     * 获取支付订单号
     * @return string
     */
    private function _getBalancePayNo()
    {
        $order_no = date('mdHis') . mt_rand(1000, 9999);
        $prefix = 'BP';
        return $prefix . $order_no;
    }

}
