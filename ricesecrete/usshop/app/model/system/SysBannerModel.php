<?php

namespace app\model\system;

use app\base\BaseModel;
use app\libs\Oss;
use think\facade\Cache;
use think\model\concern\SoftDelete;

/**
 * 模型-banner
 * Class SysBannerModel
 * @package app\model
 */
class SysBannerModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'sys_banner';

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
     * 范围查询-jump_type
     * @param $query
     * @param $jump_type
     */
    public function scopeJumptype($query, $jump_type)
    {
        if ($jump_type != null) {
            $query->where('jump_type', '=', $jump_type);
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
            $query->whereLike('title', '%' . trim($keyword) . '%', 'OR');
            $query->whereLike('detail', '%' . trim($keyword) . '%');
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $jump_type
     * @param $keyword
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @param string $area
     * @return array
     */
    public function getPageData($status, $jump_type, $keyword, $current_page, $list_rows, $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->status($status)
            ->jumptype($jump_type)
            ->keyword($keyword)
            ->order('sort', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);

        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 获取所有数据
     * @param int $limit
     * @param string $field
     * @return SysBannerModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAllData($limit = 10, $field = 'title,src,jump_type,jump_link,goods_type,goods_id')
    {
        $data = Cache::get('sys_banner');
        if (!empty($data)) {
            return unserialize($data);
        }
        $dataList = self::field($field)->where('status', 1)
            ->where('start_time', '<', time())
            ->where('end_time', '>', time())
            ->order('sort', 'desc')->limit($limit)->select();
        Cache::set('sys_banner', serialize($dataList));
        return $dataList;
    }
}
