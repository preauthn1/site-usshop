<?php

namespace app\model\user;

use think\model\Pivot;

/**
 * 模型-会员-权益关联表
 * Class HobbyModel
 * @package app\model
 */
class VipRightModel extends Pivot
{
    protected $autoWriteTimestamp = true;

    protected $name = 'vip_rights';

}
