<?php

namespace app\model\system;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-虚拟币链管理
 * Class ChainModel
 * @package app\model\system
 */
class ChainModel extends BaseModel
{
  use SoftDelete;

  protected $name = 'chain';

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
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param  string  $order
     * @param  string  $field
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

}
