<?php

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\concern\SoftDelete;

/**
 * 模型-内容管理
 * Class AppVersionModel
 * @package app\model
 */
class ArticleModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'article';

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if (!empty($status)) {
            $query->where('status', '=', $status);
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param  string  $order
     * @param  string  $field
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
}
