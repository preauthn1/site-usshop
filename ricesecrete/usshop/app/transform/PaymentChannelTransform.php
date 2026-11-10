<?php

namespace app\transform;

class PaymentChannelTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'                => $item['id'],
            'name'              => $item['name'],
            'ident'             => $item['ident'],
            'src'               => $item['src'],
            'pay_status'        => $item['pay_status'],
            'withdrawal_status' => $item['withdrawal_status'],
        ];
    }
}
