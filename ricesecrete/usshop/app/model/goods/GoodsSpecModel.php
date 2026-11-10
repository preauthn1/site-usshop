<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\libs\Util;

/**
 * 模型-GoodsSpecModel
 * Class GoodsSpecModel
 * @package app\model
 */
class GoodsSpecModel extends BaseModel
{
    protected $name = 'goods_spec';

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
    public function getPageData($map, $current_page, $list_rows, $order = 'update_time DESC', $field = '*')
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
     * 获取商品规格详情
     * @param $spec_id
     * @param string $field
     * @return GoodsSpecModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecInfo($spec_id, $field = "*")
    {
        $map = [
            'id' => $spec_id
        ];
        $temp = self::where($map)->field($field)->find();
        if (empty($temp)) {
            return [];
        }
        $spec_name = $temp['spec'];
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
        $temp['spec'] = implode(';', $spec_arr_new);
        return $temp;
    }

    /**
     * 添加规格信息
     * @param $goods_list
     * @return mixed
     */
    public static function addSpecInfo($goods_list, $product_id)
    {
        $goods_list_temp = self::getSpecList($product_id, 'id,spec');

        $del_ids = [];
        foreach ($goods_list_temp as $temp_value) {
            $temp_no = '';
            foreach ($goods_list as $value) {
                if ($temp_value['spec'] == $value['spec']) {
                    $temp_no = $value['id'];
                }
            }
            if (empty($temp_no)) {
                $del_ids[] = $temp_value['id'];
            }
        }
        if (!empty($del_ids)) {
            foreach ($del_ids as $del_id) {
                self::where('id', $del_id)->delete();
            }
        }
        foreach ($goods_list as &$value) {
            if (empty($value['id'])) {
                $in_data = [
                    'product_id' => $value['product_id'],
                    'img' => $value['img'],
                    'name' => $value['name'],
                    'spec' => $value['spec'],
                    'store' => $value['store'],
                    'price' => $value['price'],
                    'cost_price' => $value['cost_price'],
                    'limit' => $value['limit'],
                    'create_time' => time(),
                    'update_time' => time()
                ];
                $value['id'] = self::insertGetId($in_data);
            } else {
                $up_data = [
                    'img' => $value['img'],
                    'name' => $value['name'],
                    'spec' => $value['spec'],
                    'store' => $value['store'],
                    'price' => $value['price'],
                    'cost_price' => $value['cost_price'],
                    'limit' => $value['limit'],
                    'update_time' => time()
                ];
                self::where('id', $value['id'])->update($up_data);
            }
        }
        return $goods_list;
    }

    /**
     * 规格列表
     * @param $id
     * @param string $field
     * @param int $limit
     * @return GoodsSpecModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecList($id, $field = "*", $limit = 3)
    {
        $map = [];
        $map[] = ['product_id', '=', $id];
        $temp_list = self::where($map)->limit($limit)->field($field)->order('update_time desc')->group('spec')->select();
        if (empty($temp_list)) {
            return [];
        }
        foreach ($temp_list as &$value) {
            if (!empty($value['spec'])) {
                $spec_arr = explode(';', $value['spec']);
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
                $value['spec'] = implode('|', $spec_arr_new);
            }
            $value['price'] = Util::getAmountFormat($value['price']);
            $value['integral'] = $value['price'];
        }
        return $temp_list;
    }

    /**
     * 获取规格信息
     * @param $product_ids
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecInfoData($product_ids)
    {
        $spec_list = [];
        $spec_all = [
            'freight_amount' => 0,
            'all_amount' => 0,
            'amount' => 0
        ];
        $take_address = [];
        foreach ($product_ids as $product_data) {
            $temp_spec = self::getSpecInfo($product_data['spec_id'], 'id,product_id,img,name,spec,price,store');
            if (empty($temp_spec)) {
                continue;
            }
            $temp_spec['product_name'] = GoodsProductModel::getProductName($temp_spec['product_id']);
            $take_address[$temp_spec['product_id']] = GoodsProductModel::getProductAddress($temp_spec['product_id']);
            $temp_spec['number'] = $product_data['number'];
            $temp_spec['amount'] = floatval($product_data['number'] * $temp_spec['price']);
            $temp_spec['integral_amount'] = intval($product_data['number'] * $temp_spec['price']);
            $temp_spec['freight_amount'] = 0;
            $temp_spec['price'] = Util::getAmountFormat($temp_spec['price']);
            $spec_list[] = $temp_spec;
            $spec_all['freight_amount'] += $temp_spec['freight_amount'];
            $spec_all['all_amount'] += $temp_spec['amount'];
            $spec_all['amount'] += $temp_spec['amount'];
        }
        $spec_all['all_integral_amount'] = intval($spec_all['all_amount']);
        $spec_all['integral_amount'] = intval($spec_all['amount']);
        return [
            'spec_list' => $spec_list,
            'spec_all' => $spec_all,
            'take_address' => $take_address
        ];
    }

    /**
     * 更新规格价格区间
     * @param $product_id
     * @return bool
     */
    public static function upMinMaxPrice($product_id)
    {
        $map = [
            'product_id' => $product_id
        ];
        $min_price = self::where($map)->order('price ASC')->value('price');
        $max_price = self::where($map)->order('price DESC')->value('price');
        $goods_product_model = new GoodsProductModel();
        $up_data = [
            'min_price' => $min_price,
            'max_price' => $max_price
        ];
        $ret = $goods_product_model->where('id', $product_id)->update($up_data);
        if (false !== $ret) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取商品库存
     * @param $product_id
     * @return float
     */
    public static function getGoodsStore($product_id)
    {
        $map = [
            'product_id' => $product_id
        ];
        return self::where($map)->sum('store');
    }

}
