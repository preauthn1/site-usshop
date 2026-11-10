<?php

declare(strict_types=1);

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\facade\Cache;

class LanguageModel extends BaseModel
{
    protected $name = 'language';

    // 缓存key
    public static $cacheKeyTemp = "lang_translate_list";
    // 缓存时间
    public static $cacheKeyTempTime = "lang_translate_time";

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if ($status !== '') {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @return array
     */
    public function getPageData($status, $map, $current_page, $list_rows, string $field = '*'): array
    {
        $data = ['total' => 0, 'current' => 0, 'limit' => 0, 'dataList' => []];
        try {
            $dataList = $this
                ->field($field)
                ->status($status)
                ->where($map)
                ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        } catch (DbException $e) {
            return $data;
        }

        $data['total'] = $dataList->total();
        $data['current'] = $dataList->currentPage();
        $data['limit'] = $dataList->listRows();
        $data['dataList'] = $dataList->items();
        return $data;
    }

    /**
     * 获取缓存列表
     * @param string $status
     * @return LanguageModel[]|array|mixed|\think\Collection
     * @throws DbException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCacheList($status = 1)
    {
        $map = [];
        if (!empty($status)) {
            $map['status'] = $status;
        }
        $list = Cache::get('lang_list_' . $status);
        if (!empty($list)) {
            return $list;
        }
        $list = self::where($map)->select();
        Cache::set('lang_list_' . $status, $list, 3600);
        return $list;
    }

    public function resetIsDefault($id)
    {
        // 把其他的is_default改为2
        $this->where('id', '<>', $id)->update(['is_default' => 2]);
    }

    /**
     * 获取默认语言
     * @return mixed
     */
    public static function getDefault()
    {
        return self::where('is_default', 1)->value('code');
    }
}
