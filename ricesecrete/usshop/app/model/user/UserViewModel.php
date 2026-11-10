<?php

namespace app\model\user;

use app\base\BaseModel;
use app\libs\Util;
use app\model\goods\GoodsLangModel;
use think\model\concern\SoftDelete;

/**
 * 模型-用户浏览记录
 * Class UserViewModel
 * @package app\model
 */
class UserViewModel extends BaseModel
{
    use SoftDelete;

    //订单类型 1旅游套餐 2正常商品 3积分商品
    const TYPE_TOURISM = 1;//旅游套餐
    const TYPE_NORMAL = 2;//正常商品
    const TYPE_INTEGRAL = 3;//积分商品

    static $TYPE_MAP = [
        self::TYPE_TOURISM => "旅游套餐",
        self::TYPE_NORMAL => "正常商品",
        self::TYPE_INTEGRAL => "积分商品",
    ];

    protected $name = 'user_view';

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
     * 添加浏览记录
     * @param $user_id
     * @param $product_id
     * @param int $type
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addView($user_id, $product_id, $type = self::TYPE_NORMAL)
    {
        $map = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'type' => $type
        ];
        $data = self::where($map)->find();
        if (empty($data)) {
            //`user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
            //  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '产品类型 1旅游套餐 2正常商品 3积分商品',
            //  `product_id` int DEFAULT '0' COMMENT '产品id',
            //  `delete_time` int DEFAULT '0',
            //  `update_time` int DEFAULT '0' COMMENT '更新时间',
            //  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
            $in_data = [
                'user_id' => $user_id,
                'product_id' => $product_id,
                'type' => $type,
                'create_time' => time(),
                'update_time' => time()
            ];
            $ret = self::insert($in_data);
            if ($ret) {
                return true;
            } else {
                return false;
            }
        } else {
            $up_data = [
                'delete_time' => 0,
                'update_time' => time(),
            ];
            $ret = self::where($map)->update($up_data);
            if ($ret) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 删除浏览记录
     * @param $user_id
     * @param $product_id
     * @param int $type
     * @return bool
     */
    public static function delView($user_id, $product_id, $type = self::TYPE_NORMAL)
    {
        $map = [
            'user_id' => $user_id,
            'type' => $type
        ];
        if (!empty($product_id)) {
            $map['product_id'] = $product_id;
        }
        $temp = self::where($map)->find();
        $ret = $temp->force(true)->delete();
        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取浏览记录列表
     * @param $map
     * @param int $page
     * @param int $limit
     * @return array
     */
    public static function getView($map = [], $page = 1, $limit = 10, $type = self::TYPE_NORMAL)
    {
        $order = "a.update_time DESC";
        $table_name = "t_tourism_product";
        if ($type > self::TYPE_TOURISM) {
            $table_name = "t_goods_product";
            $field = "a.product_id,a.update_time,p.id,p.title,p.type_id,p.min_price,p.img";
        } else {
            $field = "a.product_id,a.update_time,p.id,p.title,p.tag,p.path,p.index_id,p.type_id,p.days,p.min_price,p.min_kid_price,p.city,p.img";
        }
        $dataList = self::where($map)
            ->alias('a')
            ->leftJoin($table_name . ' p', 'p.id=a.product_id')
            ->field($field)
            ->where('p.status', 1)
            ->where('p.delete_time', 0)
            ->order($order)
            ->paginate(['page' => $page, 'list_rows' => $limit], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as $k => $v) {
            $items[$k] = GoodsLangModel::getGoodsInfo($v);
//            if (isset($v['days'])) {
//                $items[$k]['spec_name'] = Util::getDaysNumStr($v['days']);
//            }
//            if (isset($v['city'])) {
//                $items[$k]['city'] = str_replace('市', '', $v['city']) . "出发";
//            }
//            if (!empty($v['tag'])) {
//                if (strpos($v['tag'], "|") >= 0) {
//                    $items[$k]['tag_arr'] = explode("|", $v['tag']);
//                } else {
//                    $items[$k]['tag_arr'] = [$v['tag']];
//                }
//            } else {
//                $items[$k]['tag_arr'] = [];
//            }
        }
        return [
            'current' => $current,
            'total' => $total,
            'limit' => $limit,
            'items' => $items
        ];
    }

    /**
     * 判断是否浏览记录
     * @param $user_id
     * @param $product_id
     * @param int $type
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function isCollect($user_id, $product_id, $type = self::TYPE_NORMAL)
    {
        if (empty($user_id)) {
            return 0;
        }
        $map = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'type' => $type
        ];
        $data = self::where($map)->find();
        if (empty($data)) {
            return 0;
        } else {
            return 1;
        }
    }
}
