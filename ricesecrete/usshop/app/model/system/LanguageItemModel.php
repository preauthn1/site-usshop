<?php

declare(strict_types=1);

namespace app\model\system;

use app\base\BaseModel;
use think\db\exception\DbException;
use think\model\relation\HasMany;

class LanguageItemModel extends BaseModel
{
    protected $name = 'language_item';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @return array
     */
    public function getPageData($map, $current_page, $list_rows, string $field = '*'): array
    {
        $data = ['total' => 0, 'current' => 0, 'limit' => 0, 'dataList' => []];
        try {
            // 获取开启的语言
            $language_ids = LanguageModel::where(['status' => BaseModel::STATUS_ENABLE])->column('id');
            $dataList = $this
                ->field($field)
                ->where($map)
                ->order('id DESC')
                ->with(['details' => function ($query) use ($language_ids) {
                    $query->where('language_id', 'in', $language_ids);
                }])
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

    public function details(): HasMany
    {
        return $this->hasMany(LanguageItemDetailModel::class, 'item_id', 'id');
    }
}
