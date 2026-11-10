<?php

namespace app\model\tourism;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismBureauModel
 * Class TourismBureauModel
 * @package app\model
 */
class TourismBureauModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_bureau';

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
     * 获取名称
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBureauName($id)
    {
        $temp = self::where('id', $id)->field('name')->find();
        if (empty($temp)) {
            return '';
        } else {
            return $temp['name'];
        }
    }

    /**
     * 获取列表
     * @param string $id
     * @param string $status
     * @return TourismExplainModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBureauList($id = '', $status = '')
    {
        $map = [];
        if (!empty($status)) {
            $map['status'] = $status;
        }
        if (!empty($id)) {
            $map['id'] = $id;
        }
        return self::where($map)->field('id,name')->select();
    }

    /**
     * 获取地址信息
     * @param $id
     * @return TourismBureauModel|array|mixed|string[]|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBureauAddress($id)
    {
        // `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '旅行社营业地址',
        //  `province` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '省',
        //  `city` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '市',
        //  `county` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '区县',
        //  `lng` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '经度',
        //  `lat` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '纬度',
        $map['id'] = $id;
        $temp = self::where($map)->field('name,mobile,address,lng,lat')->find();
        if (empty($temp)) {
            return [
                'name' => '',
                'mobile' => '',
                'address' => '',
                'lng' => '',
                'lat' => '',
            ];
        }
        return $temp;
    }

    /**
     * 获取旅行社
     * @param string $id
     * @return TourismBureauModel|array|mixed|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBureauInfo($id = '')
    {
        //`name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '旅行社名称',
        //  `mobile` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '联系电话',
        //  `license` text COLLATE utf8mb4_unicode_ci COMMENT '营业执照',
        //  `place` text COLLATE utf8mb4_unicode_ci COMMENT '营业场所图片',
        $map['id'] = $id;
        $temp = self::where($map)->field('id,name,mobile,license,place')->find();
        if (empty($temp)) {
            return [];
        }
        return $temp;
    }
}
