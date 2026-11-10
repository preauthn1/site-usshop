<?php

namespace app\model\system;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-活动管理
 * Class ActivityModel
 * @package app\model
 */
class ActivityModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'activity';

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
}
