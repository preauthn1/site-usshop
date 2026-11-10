<?php

namespace app\transform\api;

use app\transform\Transform;

class CurrencyTransform extends Transform
{

    public function transform($item): array
    {
        return [
            'id'            => $item['id'],
            'name'          => $item['name'],
            'symbol'        => $item['symbol'],
            'exchange_rate' => $item['exchange_rate'],
            'code'          => $item['code'],
            'is_default'    => $item['is_default'],
        ];
    }
}
