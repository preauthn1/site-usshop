<?php

namespace app\model\tourism;

use app\base\BaseModel;
use app\libs\Oss;
use think\facade\Cache;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismBannerModel
 * Class TourismBannerModel
 * @package app\model
 */
class TourismBannerModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_banner';

    /**
     * 删除redis
     */
    public function delAllRedis()
    {
        $this->redis->del($this->redis_key);
        $this->redis->del($this->redis_key . ':sort');
    }

    /**
     * 彻底删除
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delComplete($id)
    {
        $banner = self::onlyTrashed()->find($id);
        Oss::delOssFile($banner['src']);
        return $banner->force()->delete();
    }

    /**
     * 还原数据
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function backData($id)
    {
        $banner = self::onlyTrashed()->find($id);
        $this->setRedis($banner->toArray());
        if ($banner['status'] == 1) {
            $this->redis->zAdd($this->redis_key . ':sort', $banner['sort'], $banner['id']);
        }
        return $banner->restore();
    }

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if ($status != null) {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 范围查询-status
     * @param $query
     * @param $tourismType
     */
    public function scopeTourismType($query, $tourismType)
    {
        if (!empty($tourismType)) {
            $query->where('tourism_type', '=', $tourismType);
        }
    }

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('title', '%' . trim($keyword) . '%');
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $keyword
     * @param $tourism_type
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @return array
     */
    public function getPageData($status, $tourism_type = '', $keyword = '', $current_page = 1, $list_rows = 10, $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->status($status)
            ->tourismType($tourism_type)
            ->keyword($keyword)
            ->order('sort', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);

        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['tourism_name'] = TourismProductModel::getProductName($item['tourism_id']);
            $item['tourism_type_str'] = TourismTypeModel::getTypeName($item['tourism_type']);
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 获取所有数据
     * @param int $limit
     * @param string $field
     * @return TourismBannerModel[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllData($limit = 10, $field = 'title,src,jump_link,tourism_type,tourism_id')
    {
        $data = Cache::get('sys_tourism_banner');
        if (!empty($data)) {
            return unserialize($data);
        }
        $map = [
            'status' => 1,
            'index' => 1
        ];
        $dataList = self::field($field)->where($map)
            ->order('sort', 'desc')->limit($limit)->select();
        Cache::set('sys_tourism_banner', serialize($dataList));
        return $dataList;
    }

    /**
     * 获取所有数据
     * @param string $type_id
     * @param string $lng
     * @param string $lat
     * @param int $limit
     * @param string $field
     * @param string $order
     * @return TourismBannerModel[]|array|mixed|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTypeData($type_id = '', $lng = '', $lat = '', $limit = 10, $field = 'title,src,jump_link,tourism_type,tourism_id', $order = 'sort DESC,update_time DESC')
    {
        $data = Cache::get('sys_tourism_banner_' . $type_id);
        if (!empty($data)) {
            return unserialize($data);
        }
        $map = [
            'status' => 1,
        ];
        if ($type_id == 'circum') {
            //周边游
            $field = "ST_Distance_Sphere(point(lng,lat),point($lng,$lat)) as distance," . $field;
            $order = "distance ASC,sort DESC,update_time DESC";
        } else {
            $map['tourism_type'] = $type_id;
        }
        $dataList = self::field($field)->where($map)->order($order)->limit($limit)->select();

        Cache::set('sys_tourism_banner_' . $type_id, serialize($dataList), 600);
        return $dataList;
    }
}
