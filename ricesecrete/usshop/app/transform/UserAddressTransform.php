<?php

namespace app\transform;

class UserAddressTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'         => $item['id'],
            'name'       => $item['name'],
            'mobile'     => $item['mobile'],
            'province'   => $item['province'],
            'city'       => $item['city'],
            'area'       => $item['area'],
            'address'    => $item['address'],
            'is_default' => $item['is_default'] == 1, // 1-是 2-否
        ];
    }
}
