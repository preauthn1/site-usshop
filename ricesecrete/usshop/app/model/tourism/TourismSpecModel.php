<?php

namespace app\model\tourism;

use app\base\BaseModel;
use app\libs\Util;
use app\model\order\OrderModel;
use app\model\order\OrderTourismModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismSpecModel
 * Class TourismSpecModel
 * @package app\model
 */
class TourismSpecModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_spec';

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
     * start_day
     * @param $id
     * @param string $field
     * @param int $limit
     * @param false $is_effectivity
     * @return TourismSpecModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecList($id, $field = "id,days_str,start_day,end_day,kid_price,price,city,product_id,journey,people,buy_people", $limit = 3, $is_effectivity = false)
    {
        $map = [];
        $map[] = ['product_id', '=', $id];
        if ($is_effectivity) {
            $map[] = ['start_day', '>', date("Y-m-d")];
        }
        $temp_list = self::where($map)->limit($limit)->field($field)->order('start_day asc')->select();
        foreach ($temp_list as $k => &$v) {
            $v['start_day'] = date("m/d", strtotime($v['start_day']));
            $v['start_week'] = Util::getDayWeek($v['start_day']);
            $v['city'] = str_replace('市', '', $v['city']) . "集合";
            $v['end_day'] = date("m/d", strtotime($v['end_day']));
            $v['journey'] = json_decode($v['journey'], true);
            $journey = $v['journey'];
            foreach ($journey as &$v1) {
                $v1['label'] = "第" . $v1['day'] . "天";
            }
            $v['journey'] = $journey;
            $v['buy_people'] = OrderTourismModel::getOrderItemNum($v['product_id'], $v['id']);
        }
        return $temp_list;
    }

    /**
     * 获取套餐详情
     * @param $spec_id
     * @param string $field
     * @return TourismSpecModel|array|mixed|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecInfo($spec_id, $field = "*")
    {
        $map = [
            'id' => $spec_id
        ];
        return self::where($map)->field($field)->find();
    }

    /**
     * 获取套餐详情数据
     * @param string $spec_id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getSpecInfoData($spec_id = '')
    {
        $temp = self::getSpecInfo($spec_id, 'id,product_id,start_day,end_day,days,price,kid_price,people,buy_people,city');
        if (empty($temp)) {
            return [];
        }
        if ($temp['start_day'] <= date("Y-m-d")) {
            return [];
        }
        $out_data = $temp->toArray();
        $out_data['start_day_str'] = date("Y/m/d", strtotime($out_data['start_day']));
        $out_data['start_day'] = date("m月d日", strtotime($out_data['start_day']));
        $out_data['start_week'] = Util::getDayWeek($out_data['start_day']);
        $out_data['end_day_str'] = date("Y/m/d", strtotime($out_data['end_day']));
        $out_data['end_day'] = date("m月d日", strtotime($out_data['end_day']));
        $out_data['end_week'] = Util::getDayWeek($out_data['end_day']);
        $out_data['product'] = TourismProductModel::getProductInfo($out_data['product_id'], 'id,title,img,tag,notice');
        $out_data['buy_people'] = OrderTourismModel::getOrderItemNum($out_data['product_id'], $out_data['id']);
        $out_data['surplus_people'] = $out_data['people'] - $out_data['buy_people'];
        if (!empty($out_data['product']['tag'])) {
            $out_data['product']['tag'] = explode("|", $out_data['product']['tag']);
        }
        if (!empty($out_data['product']['notice'])) {
            $notice = json_decode($out_data['product']['notice'], true);
            //{
            //	"apply_id": 2,//报名须知
            //	"restrict_id": 3,//报名限制
            //	"equip_id": 5,//旅游装备
            //	"policy_id": 1//退订政策
            //}
            $out_data['notice'] = [
                'apply' => [
                    'name' => '报名须知',
                    'html' => TourismExplainModel::getExplainInfo($notice['apply_id'])
                ],
                'restrict' => [
                    'name' => '报名限制',
                    'html' => TourismExplainModel::getExplainInfo($notice['restrict_id'])
                ],
                'policy' => [
                    'name' => '退订政策',
                    'html' => TourismExplainModel::getExplainInfo($notice['policy_id'])
                ]
            ];
        }
        return $out_data;
    }

    /**
     * 更新旅游套餐价格区间
     * @param $product_id
     * @return bool
     */
    public static function upMinMaxPricePeopleDays($product_id)
    {
        $map = [
            'product_id' => $product_id
        ];
        $min_price = self::where($map)->order('price ASC')->value('price');
        $min_kid_price = self::where($map)->order('kid_price ASC')->value('kid_price');
        $min_people = self::where($map)->order('people ASC')->value('people');
        $min_days = self::where($map)->order('days ASC')->value('days');

        $max_price = self::where($map)->order('price DESC')->value('price');
        $max_kid_price = self::where($map)->order('kid_price DESC')->value('kid_price');
        $max_people = self::where($map)->order('people DESC')->value('people');
        $max_days = self::where($map)->order('days DESC')->value('days');
        $start_day = self::where($map)->order('start_day DESC')->value('start_day');
        $end_day = self::where($map)->order('start_day DESC')->value('end_day');
        $tourism_product_model = new TourismProductModel();
        $up_data = [
            'min_price' => $min_price,
            'min_kid_price' => $min_kid_price,
            'min_people' => $min_people,
            'min_days' => $min_days,
            'max_price' => $max_price,
            'max_kid_price' => $max_kid_price,
            'max_people' => $max_people,
            'max_days' => $max_days,
            'start_day' => $start_day,
            'end_day' => $end_day,
        ];
        $ret = $tourism_product_model->where('id', $product_id)->update($up_data);
        if (false !== $ret) {
            return true;
        } else {
            return false;
        }
    }
}
