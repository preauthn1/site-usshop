<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\Alipay\Alipay;
use app\libs\api\ApiIndexCode;
use app\libs\WechatPay;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderCustomModel
 * Class OrderCustomModel
 * @package app\model
 */
class OrderCustomModel extends BaseModel
{
    use SoftDelete;

    //定制类型 1企业定制 2私人定制
    const TYPE_ENTERPRISE = 1;
    const TYPE_PRIVATE = 2;

    const TYPE_MAP = [
        self::TYPE_ENTERPRISE => "企业定制",
        self::TYPE_PRIVATE => "私人定制",
    ];

    //状态：0 取消定制 1申请中 2接待中 3接待成功 4拒绝接待 5用户删除
    const STATUS_CANCEL = 0;//取消定制
    const STATUS_APPLY = 1;//申请中
    const STATUS_RECEIVE = 2;//接待中
    const STATUS_SUCCESS = 3;//接待成功
    const STATUS_REFUSE = 4;//拒绝接待
    const STATUS_DELETE = 5;//用户删除

    static $STATUS_MAP = [
        self::STATUS_CANCEL => "取消定制",//取消定制
        self::STATUS_APPLY => "申请中",//申请中
        self::STATUS_RECEIVE => "接待中",//接待中
        self::STATUS_SUCCESS => "接待成功",//接待成功
        self::STATUS_REFUSE => "拒绝接待",//拒绝接待
    ];
    protected $name = 'order_custom';

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
        $items = $dataList->items();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 添加定制信息
     * @param $data
     * @return array|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addCustom($data)
    {
        // `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '姓名',
        //  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '手机号',
        //  `enterprise` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '企业名称',
        //  `type` tinyint(1) DEFAULT '1' COMMENT '定制类型 1企业定制 2私人定制  ',
        //  `address` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发地址',
        //  `destination` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '目的地',
        //  `start_day` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '出发时间',
        //  `number` int DEFAULT '0' COMMENT '人数',
        //  `budget` decimal(11,2) DEFAULT '0.00' COMMENT '人均预算',
        //  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：0取消 1申请中  2接待成功 3拒绝接待',
        //  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '备注信息',

        if (!empty($data['id'])) {
            $map = [
                'id' => $data['id'],
                'user_id' => $data['user_id'],
                'status' => self::STATUS_APPLY
            ];
            $temp = $this->where($map)->find();
            if (empty($temp)) {
                return ['code' => 0];
            }
            $up_data = [];
            $up_data['name'] = $data['name'];
            $up_data['phone'] = $data['phone'];
            $up_data['enterprise'] = $data['enterprise'];
            $up_data['type'] = $data['type'];
            $up_data['address'] = $data['address'];
            $up_data['destination'] = $data['destination'];
            $up_data['start_day'] = $data['start_day'];
            $up_data['number'] = $data['number'];
            $up_data['budget'] = $data['budget'];
            $up_data['status'] = self::STATUS_APPLY;
            $up_data['remark'] = $data['remark'];
            $up_data['update_time'] = time();
            $this->where('id', $data['id'])->update($up_data);
            return ['code' => 1, 'data' => $data['id']];
        }
        $map = [
            'user_id' => $data['user_id'],
            'status' => self::STATUS_APPLY
        ];
        $temp = $this->where($map)->find();
        if (!empty($temp)) {
            return ['code' => 0];
        }
        $in_data['user_id'] = $data['user_id'];
        $in_data['name'] = $data['name'];
        $in_data['phone'] = $data['phone'];
        $in_data['enterprise'] = $data['enterprise'];
        $in_data['type'] = $data['type'];
        $in_data['address'] = $data['address'];
        $in_data['destination'] = $data['destination'];
        $in_data['start_day'] = $data['start_day'];
        $in_data['number'] = $data['number'];
        $in_data['budget'] = $data['budget'];
        $in_data['status'] = self::STATUS_APPLY;
        $in_data['remark'] = $data['remark'];
        $in_data['create_time'] = time();
        $in_data['update_time'] = time();
        $id = $this->insertGetId($in_data);
        if (empty($id)) {
            return ['code' => 0];
        }
        return ['code' => 1, 'data' => $id];
    }

    /**
     * 取消定制信息
     * @param $id
     * @param $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cancelCustom($id, $user_id)
    {
        $map = [
            'id' => $id,
            'user_id' => $user_id,
            'status' => self::STATUS_APPLY
        ];
        $temp = $this->where($map)->find();
        if (empty($temp)) {
            return false;
        }
        return $this->changeStatus($id, self::STATUS_CANCEL);
    }

    /**
     * 删除定制信息
     * @param $id
     * @param $user_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteCustom($id, $user_id)
    {
        $map = [
            'id' => $id,
            'user_id' => $user_id
        ];
        $temp = $this->where($map)->find();
        if (empty($temp)) {
            return false;
        }
        return $this->changeStatus($id, self::STATUS_DELETE);
    }

    /**
     * 获取定制信息
     * @param $id
     * @param $user_id
     * @return OrderCustomModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCustomInfo($id, $user_id = '')
    {
        $map = [
            'id' => $id
        ];
        if (!empty($user_id)) {
            $map['user_id'] = $user_id;
        }
        $temp = $this->where($map)->find();
        if (empty($temp)) {
            return [];
        }
        $temp['status_str'] = self::$STATUS_MAP[$temp['status']];
        $temp['type_str'] = self::TYPE_MAP[$temp['type']];
        //`cancel_time` int DEFAULT '0' COMMENT '取消时间',
        //  `receive_time` int DEFAULT '0' COMMENT '接待时间',
        //  `success_time` int DEFAULT '0' COMMENT '接待完成时间',
        //  `refuse_time` int DEFAULT '0' COMMENT '拒绝接待时间',
        if (!empty($temp['cancel_time'])) {
            $temp['cancel_time'] = date('Y-m-d H:i:s', $temp['cancel_time']);
        } else {
            $temp['cancel_time'] = "--";
        }
        if (!empty($temp['receive_time'])) {
            $temp['receive_time'] = date('Y-m-d H:i:s', $temp['receive_time']);
        } else {
            $temp['receive_time'] = "--";
        }
        if (!empty($temp['success_time'])) {
            $temp['success_time'] = date('Y-m-d H:i:s', $temp['success_time']);
        } else {
            $temp['success_time'] = "--";
        }
        if (!empty($temp['refuse_time'])) {
            $temp['refuse_time'] = date('Y-m-d H:i:s', $temp['refuse_time']);
        } else {
            $temp['refuse_time'] = "--";
        }
        if (empty($temp['number'])) {
            $temp['number'] = "";
        }
        if (floatval($temp['budget']) == 0) {
            $temp['budget'] = "";
        }
        return $temp;
    }

    /**
     * 修改定制状态
     * @param $id
     * @param $status
     * @param int $admin_id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function changeStatus($id, $status, $admin_id = 0)
    {
        $map = [
            'id' => $id
        ];
        $temp = $this->where($map)->find();
        $up_data = [];
        switch ($status) {
            case self::STATUS_CANCEL:
                if ($temp['status'] != self::STATUS_APPLY) {
                    return false;
                }
                $up_data['status'] = self::STATUS_CANCEL;
                $up_data['cancel_time'] = time();
                $up_data['admin_id'] = $admin_id;
                break;
            case self::STATUS_RECEIVE:
                if ($temp['status'] != self::STATUS_APPLY) {
                    return false;
                }
                $up_data['status'] = self::STATUS_RECEIVE;
                $up_data['receive_time'] = time();
                $up_data['admin_id'] = $admin_id;
                break;
            case self::STATUS_SUCCESS:
                if ($temp['status'] != self::STATUS_APPLY) {
                    return false;
                }
                $up_data['status'] = self::STATUS_SUCCESS;
                $up_data['success_time'] = time();
                $up_data['admin_id'] = $admin_id;
                break;
            case self::STATUS_REFUSE:
                if ($temp['status'] != self::STATUS_APPLY) {
                    return false;
                }
                $up_data['status'] = self::STATUS_REFUSE;
                $up_data['refuse_time'] = time();
                $up_data['admin_id'] = $admin_id;
                break;
            case self::STATUS_DELETE:
                $up_data['status'] = self::STATUS_DELETE;
                break;
            default:
                return false;
        }
        $up_data['update_time'] = time();
        $ret = $this->where($map)->update($up_data);
        if ($ret === false) {
            return false;
        }
        return true;
    }

    /**
     * 获取定制列表
     * @param $user_id
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getCustomList($user_id, $current_page, $list_rows, $order = 'create_time desc', $field = "*")
    {
        $map = [
            'user_id' => $user_id
        ];
        $dataList = $this
            ->field($field)
            ->where($map)
            ->where('status', '<', self::STATUS_DELETE)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['status_str'] = self::$STATUS_MAP[$item['status']];
            $item['type_str'] = self::TYPE_MAP[$item['type']];
            //`cancel_time` int DEFAULT '0' COMMENT '取消时间',
            //  `receive_time` int DEFAULT '0' COMMENT '接待时间',
            //  `success_time` int DEFAULT '0' COMMENT '接待完成时间',
            //  `refuse_time` int DEFAULT '0' COMMENT '拒绝接待时间',
            if (!empty($item['cancel_time'])) {
                $item['cancel_time'] = date('Y-m-d H:i:s', $item['cancel_time']);
            } else {
                $item['cancel_time'] = "--";
            }
            if (!empty($item['receive_time'])) {
                $item['receive_time'] = date('Y-m-d H:i:s', $item['receive_time']);
            } else {
                $item['receive_time'] = "--";
            }
            if (!empty($item['success_time'])) {
                $item['success_time'] = date('Y-m-d H:i:s', $item['success_time']);
            } else {
                $item['success_time'] = "--";
            }
            if (!empty($item['refuse_time'])) {
                $item['refuse_time'] = date('Y-m-d H:i:s', $item['refuse_time']);
            } else {
                $item['refuse_time'] = "--";
            }
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }
}
