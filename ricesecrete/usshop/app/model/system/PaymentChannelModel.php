<?php

namespace app\model\system;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-支付渠道
 * Class SysBulletinModel
 * @package app\model
 */
class PaymentChannelModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'payment_channel';

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
     * 保存数据
     * @param $data
     * @return array
     */
    public function saveData($data): array
    {
        if (!empty($data['id'])) {
            $flag = $this->update($data, ['id' => $data['id']]);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $flag = $this->strict(false)->insertGetId($data);
            $id = $flag;
        }
        return ['flag' => $flag, 'id' => $id];
    }

}
