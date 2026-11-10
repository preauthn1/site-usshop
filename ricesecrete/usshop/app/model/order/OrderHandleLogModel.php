<?php

namespace app\model\order;

use app\base\BaseModel;
use app\libs\api\ApiIndexCode;
use app\model\system\AuthUserModel;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderHandleLogModel
 * Class OrderHandleLogModel
 * @package app\model
 */
class OrderHandleLogModel extends BaseModel
{
    use SoftDelete;

    //操作类型 0其他  1审核 2修改金额 3后台付款 4核销订单 5取消订单 6删除订单 7审核退款 8商品发货 9拒绝退款 10同意退款退货 11退货退款完成 12拒绝退货退款
    const TYPE_OTHER = 0;//其他
    const TYPE_AUDIT = 1;//审核
    const TYPE_MODIFY_AMOUNT = 2;//修改金额
    const TYPE_PAY = 3;//付款
    const TYPE_VERIFY = 4;//核销
    const TYPE_CANCEL = 5;//取消订单
    const TYPE_DELETE = 6;//删除订单
    const TYPE_REFUND = 7;//审核退款
    const TYPE_SEND = 8;//商品发货
    const TYPE_REFUSE_REFUND = 9;//拒绝退款
    const TYPE_AGREE_REFUND = 10;//同意退款退货
    const TYPE_AGREE_REFUND_RETURN = 11;//退货退款完成
    const TYPE_REFUND_RETURN = 12;//拒绝退货退款


    static $TYPE_MAP = [
        self::TYPE_OTHER => "其他",//其他
        self::TYPE_AUDIT => "审核",//审核
        self::TYPE_MODIFY_AMOUNT => "修改金额",//修改金额
        self::TYPE_PAY => "付款",//付款
        self::TYPE_VERIFY => "核销",//核销/签收
        self::TYPE_CANCEL => "取消订单",//取消订单
        self::TYPE_DELETE => "删除订单",//删除订单
        self::TYPE_REFUND => "审核退款",//审核退款
        self::TYPE_SEND => "商品发货",//商品发货
        self::TYPE_REFUSE_REFUND => "拒绝退款",//拒绝退款
        self::TYPE_AGREE_REFUND => "同意退款退货",//同意退款退货
        self::TYPE_AGREE_REFUND_RETURN => "退货退款完成",//退货退款完成
        self::TYPE_REFUND_RETURN => "拒绝退货退款"//拒绝退货退款
    ];

    protected $name = 'order_handle_log';

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
        foreach ($items as &$item) {
            $typeArr = self::$TYPE_MAP;
            $type_str = '';
            if (isset($typeArr[$item['type']])) {
                $type_str = $typeArr[$item['type']];
            }
            $item['type_str'] = $type_str;
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取订单操作日志
     * @param $order_id
     * @param $order_type //订单类型 1旅游套餐 2正常商品 3积分商品
     * @return OrderHandleLogModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getOrderHandleLog($order_id = '', $order_type = OrderModel::TYPE_TOURISM)
    {
        $map = [
            'order_id' => $order_id
        ];
        $dataList = self::where($map)
            ->order('id DESC')
            ->select();
        foreach ($dataList as &$item) {
            $typeArr = self::$TYPE_MAP;
            $type_str = '';
            if (isset($typeArr[$item['type']])) {
                $type_str = $typeArr[$item['type']];
            }
            $item['type_str'] = $type_str;
            if ($order_type != OrderModel::TYPE_TOURISM) {
                if ($item['type'] == OrderHandleLogModel::TYPE_VERIFY) {
                    $item['type_str'] = "确认签收";
                }
            }
            $admin_info = AuthUserModel::getAdminName($item['admin_id']);
            $item['admin_username'] = $admin_info['username'];
            $item['admin_realname'] = $admin_info['realname'];
        }
        return $dataList;
    }

    /**
     * 获取订单操作日志
     * @param $order_id
     * @return OrderHandleLogModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getGoodsOrderHandleLog($order_id = '')
    {
        $map = [
            'order_id' => $order_id
        ];
        $dataList = self::where($map)
            ->order('id DESC')
            ->select();
        foreach ($dataList as &$item) {
            $typeArr = self::$TYPE_MAP;
            $typeArr[self::TYPE_VERIFY] = "签收";
            $type_str = '';
            if (isset($typeArr[$item['type']])) {
                $type_str = $typeArr[$item['type']];
            }
            $item['type_str'] = $type_str;
            $admin_info = AuthUserModel::getAdminName($item['admin_id']);
            $item['admin_username'] = $admin_info['username'];
            $item['admin_realname'] = $admin_info['realname'];
        }
        return $dataList;
    }

    /**
     * 添加订单操作日志
     * @param string $order_id
     * @param int $type
     * @param string $content
     * @param int $admin_id
     * @return int|string
     */
    public static function addOrderHandleLog($order_id = '', $type = self::TYPE_OTHER, $content = '', $admin_id = 0)
    {
        if (empty($type)) {
            return false;
        }
        $data = [
            'order_id' => $order_id,
            'admin_id' => $admin_id,
            'type' => $type,
            'content' => $content,
            'update_time' => time(),
            'create_time' => time()
        ];
        return self::insert($data);
    }
}
