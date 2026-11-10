<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\libs\Util;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-GoodsBuyLogModel
 * Class GoodsBuyLogModel
 * @package app\model
 */
class GoodsBuyLogModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_buy_log';

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
            ->withSearch(['team'], [
                'team' => 'user_id'
            ])
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['product_name'] = GoodsProductModel::where('id', $item['product_id'])->value('title');
            $item['spec_name'] = GoodsSpecModel::where('id', $item['spec_id'])->value('spec');
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 添加购买记录
     * @param $user_id
     * @param $tourism_id
     * @param $spec_id
     * @param int $num
     * @param float $price
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addBuyLog($user_id, $goods_id, $spec_id, $num = 1, $price = 0.00)
    {
        $user_data = UserModel::getNameHead($user_id);
        // `user_id` int NOT NULL DEFAULT '0' COMMENT '购买用户',
        //  `user_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户昵称',
        //  `user_head` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '购买用户头像',
        //  `product_id` int DEFAULT '0' COMMENT '产品id',
        //  `spec_id` int DEFAULT '0' COMMENT '产品规格id',
        //  `num` int DEFAULT '0' COMMENT '购买数量',
        //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '购买费用',
        $in_data = [
            'user_id' => $user_id,
            'user_name' => $user_data['nick_name'],
            'user_head' => $user_data['head_img'],
            'product_id' => $goods_id,
            'spec_id' => $spec_id,
            'num' => $num,
            'price' => $price,
            'create_time' => time(),
            'update_time' => time(),
        ];
        $ret = self::insert($in_data);
        if ($ret) {
            GoodsProductModel::where('id', $goods_id)->inc('buy', $num)->update();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取购买人数
     * @param string $product_id
     * @return int
     */
    public static function getBuyNum($product_id = '')
    {
        $temp = self::where('product_id', $product_id)->count();
        if (empty($temp)) {
            return 0;
        } else {
            return $temp;
        }
    }

    /**
     * 获取购买记录
     * @param $product_id
     * @param int $limit
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBuyLog($product_id, $limit = 5)
    {
        $map = [
            'product_id' => $product_id,
        ];
        $temp = self::where($map)->field('id,user_name,user_head')->limit($limit)->select();
        if (empty($temp)) {
            return [
                'total' => 0,
                'dataList' => []
            ];
        }
        foreach ($temp as &$item) {
            $item['user_name'] = Util::getUserNameAno($item['user_name']);
        }
        return [
            'total' => self::where($map)->count(),
            'dataList' => $temp
        ];
    }
}
