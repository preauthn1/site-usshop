<?php

namespace app\transform;

class CommissionTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'          => $item['id'],
            'amount'      => abs($item['amount']),
            'create_time' => $item['create_time'],
            'from'        => $item['balance_type'],
        ];
    }
}
