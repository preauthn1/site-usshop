<?php

namespace app\transform;

class IntegralTransform extends Transform
{
    public function transform($item): array
    {
        $data = [
            'id'          => $item['id'],
            'amount'      => abs($item['amount']),
            'create_time' => $item['create_time'],
            'from'        => $item['balance_type'],
            'status'      => 'success'
        ];

        if (is_array($item) && isset($item['tourism_order'])) {
            $data['order_no'] = $item['tourism_order']['order_no'];
        }
        return $data;
    }
}
