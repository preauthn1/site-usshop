<?php

namespace app\transform;

class UserChinaTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'        => $item['id'],
            'chain_name' => $item['chain']['name'],
            'address'   => $item['address']
        ];
    }
}
