<?php

namespace app\transform;

use app\model\money\WithdrawalModel;

class WithdrawalTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'              => $item['id'],
            'original_amount' => $item['original_amount'],
            'create_time'     => $item['create_time'],
            'channel_indent'  => $item['channel_indent'],
            'status'          => $item['status'],
            'status_str'      => WithdrawalModel::$STATUS_MAP[$item['status']],
        ];
    }
}
