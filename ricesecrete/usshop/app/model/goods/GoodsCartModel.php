<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\libs\Util;
use think\model\concern\SoftDelete;

/**
 * 购物车
 * 模型-GoodsCartModel
 * Class GoodsCartModel
 * @package app\model
 */
class GoodsCartModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_cart';

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
     * 获取购物车列表
     * @param $user_id
     * @return array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCartList($user_id = '')
    {
        if (empty($user_id)) {
            return ['take_list' => [], 'logistics_list' => []];
        }
        $card_list = self::alias('c')->where('c.user_id', $user_id)
            ->join('t_goods_product gp', 'c.product_id = gp.id')
            ->join('t_goods_spec gs', 'c.spec_id = gs.id')
            ->field('c.*,gp.title,gs.img,gp.photo,gp.spec,gs.price,gs.name,gs.spec as spec_name,gs.limit,gs.store')
            ->where('c.user_id', $user_id)
            ->where('gp.status', '=', 1)
            ->where('gp.delete_time', '=', 0)
            ->where('c.number', '>', 0)
            ->order('c.update_time DESC')
            ->select();
        $take_list = [];
        $logistics_list = [];
        foreach ($card_list as &$v) {
            $v = GoodsLangModel::getGoodsInfo($v);
            $field = "id,product_id,img,name,spec,store,price,cost_price,limit,buy";
            $v['goods_spec'] = GoodsSpecModel::getSpecList($v['product_id'], $field, 100);
            if (!empty($v['photo'])) {
                $photo = explode(',', $v['photo']);
                $temp_photo = [];
                foreach ($photo as $k => $vx) {
                    $type = pathinfo($vx, PATHINFO_EXTENSION);
                    if ($type == 'mp4') {
                        $temp_photo[] = [
                            'type' => 'video',
                            'url' => $vx,
                            'img' => $vx
                        ];
                    } else {
                        $temp_photo[] = [
                            'type' => 'image',
                            'url' => $vx
                        ];
                    }
                }
                $v['photo'] = $photo;
            } else {
                $v['photo'] = [];
            }
            if (!empty($v['spec'])) {
                $spec = json_decode($v['spec'], true);
                foreach ($spec as $ke => $va) {
                    if (isChinese($va['name'])) {
                        $spec[$ke]['name'] = lang($va['name']);
                        foreach ($va['list'] as $k1 => $v1) {
                            if (isChinese($v1['name'])) {
                                $spec[$ke]['list'][$k1]['name'] = lang($v1['name']);
                            }
                        }
                    }
                }
                $v['spec'] = $spec;
            } else {
                $v['spec'] = [];
            }
            $spec_name = $v['spec_name'];
            $spec_arr = explode(';', $spec_name);
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
            $v['spec_name'] = implode(';', $spec_arr_new);
            $v['price'] = Util::getAmountFormat($v['price']);
            if ($v['express_type'] == 2) {
                $take_list[] = $v;
            } else {
                $logistics_list[] = $v;
            }
        }
        return ['take_list' => $take_list, 'logistics_list' => $logistics_list];
    }

    /**
     * 添加购物车
     * @param $user_id
     * @param $product_id
     * @param $express_type
     * @param $spec_id
     * @param $num
     * @param string $change
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function changeCart($user_id, $product_id, $express_type, $spec_id, $num, $change = 'add')
    {
        $map = [
            'user_id' => $user_id,
            'product_id' => $product_id,
            'spec_id' => $spec_id,
        ];
        $cart = self::where($map)->withTrashed()->field('number')->find();
        if ($change == 'add') {
            if (empty($cart)) {
                $in_data = [
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'express_type' => $express_type,
                    'spec_id' => $spec_id,
                    'number' => $num,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                self::insert($in_data);
                return true;
            }
            $up_data = [
                'express_type' => $express_type,
//                'number' => 0,
                'update_time' => time(),
            ];
            self::where($map)->update($up_data);
            self::where($map)->inc('number', $num)->update();
        } else if ($change == 'mod') {
            if (empty($cart)) {
                $in_data = [
                    'user_id' => $user_id,
                    'product_id' => $product_id,
                    'express_type' => $express_type,
                    'spec_id' => $spec_id,
                    'number' => $num,
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                self::insert($in_data);
                return true;
            }
            $up_data = [
                'express_type' => $express_type,
                'number' => 0,
                'update_time' => time(),
            ];
            self::where($map)->update($up_data);
            self::where($map)->inc('number', $num)->update();
        } else {
            if (empty($cart)) {
                return true;
            }
            if ($cart['number'] <= $num) {
                $up_data = [
                    'express_type' => $express_type,
                    'number' => 0,
                    'update_time' => time(),
                ];
                self::where($map)->update($up_data);
            } else {
                self::where($map)->dec('number', $num)->update();
            }
        }
        return true;
    }

    /**
     * 修改购物车
     * @param $id
     * @param $spec_id
     * @param $num
     * @param $radio
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function editCart($id, $spec_id, $num, $radio = 0)
    {
        $map = [
            'id' => $id,
        ];
        $cart = self::where($map)->withTrashed()->field('number')->find();
        if (empty($cart)) {
            return true;
        }
        $up_data = [
            'spec_id' => $spec_id,
            'number' => $num,
            'radio' => $radio,
            'update_time' => time(),
        ];
        self::where($map)->update($up_data);
        return true;
    }

    /**
     * 清空购物车
     * @param $user_id
     * @param array $ids
     * @return bool
     */
    public static function clearCart($user_id, $ids = [])
    {
        $map = [
            'user_id' => $user_id,
        ];
        $up_data = [
            'number' => 0,
            'update_time' => time(),
        ];
        self::where($map)->whereIn('id', $ids)->update($up_data);
        return true;
    }

    /**
     * 清空指定商品购物车
     * @param $user_id
     * @param array $ids
     * @return bool
     */
    public static function clearGoodsCart($user_id, $spec_id)
    {
        $map = [
            'user_id' => $user_id,
            'spec_id' => $spec_id,
        ];
        $up_data = [
            'number' => 0,
            'update_time' => time(),
        ];
        self::where($map)->update($up_data);
        return true;
    }

}
