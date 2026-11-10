<?php

namespace app\model\user;

use app\model\system\HobbyModel;
use think\model\Pivot;
use think\model\relation\BelongsTo;

/**
 * 模型-用户-兴趣
 * Class HobbyModel
 * @package app\model
 */
class UserHobbyModel extends Pivot
{
    protected $autoWriteTimestamp = true;

    protected $name = 'user_hobby';

    public function hobby(): BelongsTo
    {
        return $this->belongsTo(HobbyModel::class, 'hobby_id', 'id');
    }
}
