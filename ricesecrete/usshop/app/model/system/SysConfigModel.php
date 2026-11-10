<?php

namespace app\model\system;

use app\base\BaseModel;

/**
 * 模型-系统配置
 * Class SysConfigModel
 * @package app\model
 */
class SysConfigModel extends BaseModel
{

    protected $name = 'sys_config';

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('name', '%' . trim($keyword) . '%');
        }
    }

    /**
     * 获取分页列表
     * @param $keyword
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @return array
     */
    public function getPageData($keyword, $current_page, $list_rows, $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->keyword($keyword)
            ->order('add_time', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 获取配置key对应的value
     * @param $key
     * @return mixed
     */
    public static function getConfigKeyValue($key)
    {
        return self::where('key', $key)->value('value');
    }
}
