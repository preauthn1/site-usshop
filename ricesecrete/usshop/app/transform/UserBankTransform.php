<?php

namespace app\transform;

class UserBankTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'        => $item['id'],
            'bank_name' => $item['bank']['name'],
            'card_no'   => $item['card_no']
        ];
    }
}
