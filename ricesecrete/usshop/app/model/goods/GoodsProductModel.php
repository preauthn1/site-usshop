<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\libs\Util;
use think\facade\Lang;
use think\model\concern\SoftDelete;

/**
 * 模型-GoodsProductModel
 * Class GoodsProductModel
 * @package app\model
 */
class GoodsProductModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'goods_product';

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
            $item = GoodsLangModel::getGoodsInfo($item);
            $item['store'] = GoodsSpecModel::getGoodsStore($item['id']);
            $item['min_price'] = Util::getAmountFormat($item['min_price']);
            $item['min_integral'] = intval($item['min_price']);
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取商品id
     * @param string $merchant_id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductIdList($merchant_id = '')
    {
        $temp = self::where('merchant_id', $merchant_id)->column('id');
        if (empty($temp)) {
            return [];
        } else {
            return $temp;
        }
    }

    /**
     * 获取商品名称
     * @param $id
     * @param $lang
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductName($id, $lang = '')
    {
        $temp = self::where('id', $id)->field('title')->find();
        if (empty($temp)) {
            return '';
        } else {
            if (empty($lang)) {
                $lang = Lang::getLangSet();
            }
            $temp = GoodsLangModel::getGoodsInfo($temp, $lang);
            return $temp['title'];
        }
    }

    /**
     * 获取商品自取地址
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductAddress($id)
    {
        //`province` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
        //  `city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
        //  `county` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
        //  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '自提具体地址',
        //  `lng` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
        //  `lat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
        $temp = self::where('id', $id)->field('province,city,county,address,lng,lat')->find();
        if (empty($temp)) {
            return [];
        } else {
            return $temp;
        }
    }

    /**
     * 获取商品信息
     * @param $id
     * @param string $field
     * @return GoodsProductModel|array|mixed|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductInfo($id, $field = 'id,title,img')
    {
        $temp = self::where('id', $id)->field($field)->find();
        if (empty($temp)) {
            return '';
        } else {
            return $temp;
        }
    }

    /**
     * 获取商品详情
     * @param string $id
     * @param string $lang
     */
    public function getProductDetails($id = '', $lang = '')
    {
        $field = "id,title,classify,express_type,min_price,buy,img,photo,spec,details,province,city,county,address,lng,lat";
        $temp = $this->where('id', $id)->field($field)->find();
        if (empty($temp)) {
            return [];
        } else {
            $out_data = $temp->toArray();
            $out_data['img'] = $temp['img'] ?? '';
            if (empty($lang)) {
                // 多语言转换
                $lang = strtoupper(\think\facade\Lang::getLangSet());
            }
            $out_data = GoodsLangModel::getGoodsInfo($out_data, $lang);
            if (!empty($temp['photo'])) {
                $out_data['photo'] = explode(',', $temp['photo']);
                $temp_photo = [];
                foreach ($out_data['photo'] as $k => $v) {
                    $type = pathinfo($v, PATHINFO_EXTENSION);
                    if ($type == 'mp4') {
                        $temp_photo[] = [
                            'type' => 'video',
                            'url' => $v,
                            'img' => $v . '?x-oss-process=video/snapshot,t_5000,f_jpg'
                        ];
                    } else {
                        $temp_photo[] = [
                            'type' => 'image',
                            'url' => $v
                        ];
                    }
                }
                $out_data['photo'] = $temp_photo;
            } else {
                $out_data['photo'] = [];
            }
            if (!empty($temp['details'])) {
                $out_data['details'] = explode(',', $temp['details']);
            } else {
                $out_data['details'] = [];
            }
            $out_data['explain_html'] = GoodsExplainModel::getExplainInfos();
            if (!empty($temp['spec'])) {
                $spec = json_decode($temp['spec'], true);
                foreach ($spec as $ke => $va) {
                    if (isChinese($va['name'])) {
                        $spec[$ke]['name'] = lang($va['name']);
                        foreach ($va['list'] as $k => $v) {
                            if (isChinese($v['name'])) {
                                $spec[$ke]['list'][$k]['name'] = lang($v['name']);
                            }
                        }
                    }
                }
                $out_data['spec'] = $spec;
            } else {
                $out_data['spec'] = [];
            }
            $out_data['min_price'] = Util::getAmountFormat($out_data['min_price']);
            $out_data['min_integral'] = intval($temp['min_price']);
            // `product_id` int DEFAULT '0' COMMENT '商品id',
            //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格图片 默认是产品封面图',
            //  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格名称',
            //  `spec` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '规格',
            //  `store` int DEFAULT '0' COMMENT '商品库存',
            //  `price` decimal(20,2) DEFAULT '0.00' COMMENT '售价',
            //  `cost_price` decimal(20,2) DEFAULT '0.00' COMMENT '进价',
            //  `limit` int DEFAULT '0' COMMENT '限购数量 0表示不限',
            //  `buy` int DEFAULT '0' COMMENT '销量',
            $field = "id,product_id,img,name,spec,store,price,cost_price,limit,buy";
            $out_data['goods_spec'] = GoodsSpecModel::getSpecList($temp['id'], $field, 100);
        }
        return $out_data;
    }
}
