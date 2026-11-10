<?php

declare(strict_types=1);

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\facade\Cache;

class CurrencyModel extends BaseModel
{
    protected $name = 'currency';

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

    public function resetIsDefault($id)
    {
        // 把其他的is_default改为2
        $this->where('id', '<>', $id)->update(['is_default' => 2]);
    }

    public static function getCurrencyRate($code = 'USD')
    {
        $exchange_rate = Cache::get("get_currency_rate_" . $code, 0);
        if (empty($exchange_rate)) {
            $exchange_rate = self::where('code', $code)->value('exchange_rate');
            if (empty($exchange_rate)) {
                $exchange_rate = 1;
            }
            Cache::set("get_currency_rate_" . $code, $exchange_rate);
        }
        return $exchange_rate;
    }
}
