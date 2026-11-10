<?php

namespace app\model\goods;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-GoodsStoreModel
 * Class GoodsStoreModel
 * @package app\model
 */
class GoodsStoreModel extends BaseModel
{
    use SoftDelete;

    //变化类型 1购买  2退回 3入库 4出库
    const TYPE_BUY = 1;//购买
    const TYPE_RETURN = 2;//退回
    const TYPE_IN = 3;//入库
    const TYPE_OUT = 4;//出库

    public static $change_type = [
        self::TYPE_BUY => '购买',
        self::TYPE_RETURN => '退回',
        self::TYPE_IN => '入库',
        self::TYPE_OUT => '出库',
    ];

    protected $name = 'goods_store';

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
     * 库存变更
     * @param $product_id
     * @param $spec_id
     * @param $num
     * @param int $change
     * @param string $user_id
     * @param string $admin_id
     * @return bool
     */
    public static function changeStore($product_id, $spec_id, $num, $change = 1, $user_id = '', $admin_id = '')
    {
        // `admin_id` int DEFAULT '0' COMMENT '操作员',
        //  `user_id` int DEFAULT '0' COMMENT '购买用户id',
        //  `product_id` int DEFAULT '0' COMMENT '产品id',
        //  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
        //  `num` int DEFAULT '0' COMMENT '变化数量',
        //  `change` tinyint(1) DEFAULT '1' COMMENT '变化类型 1购买  2退回 3入库 4出库',
        $map = [
            'product_id' => $product_id,
            'id' => $spec_id,
        ];
        $store = GoodsSpecModel::where($map)->value('store');
        $is_change = false;
        $spec_store = 0;
        switch ($change) {
            case 1:
            case 4:
                if ($store < $num) {
                    $is_change = false;
                } else {
                    $spec_store = $store - $num;
                    $is_change = true;
                }
                break;
            case 2:
            case 3:
                $spec_store = $store + $num;
                $is_change = true;
                break;
        }
        if ($is_change) {
            $in_data = [
                'admin_id' => $admin_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'spec_id' => $spec_id,
                'store' => $store,
                'num' => $num,
                'change' => $change,
                'create_time' => time(),
                'update_time' => time(),
            ];
            //$change 变化类型 1购买  2退回 3入库 4出库
            $ret = self::insert($in_data);
            if ($ret) {
                GoodsSpecModel::where($map)->update(['store' => $spec_store]);
                return true;
            } else {
                return false;
            }
        }
        return false;
    }
}
