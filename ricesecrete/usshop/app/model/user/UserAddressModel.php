<?php

namespace app\model\user;

use app\base\BaseModel;

/**
 * 模型-用户-收货地址
 * Class HobbyModel
 * @package app\model
 */
class UserAddressModel extends BaseModel
{
    protected $autoWriteTimestamp = true;

    protected $name = 'user_address';

    public function resetIsDefault($id)
    {
        // 把其他的is_default改为2
        $this->where('id', '<>', $id)->update(['is_default' => 2]);
    }
}
