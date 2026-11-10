<?php

namespace app\transform;

class BankTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'      => $item['id'],
            'name'   => $item['name'],
        ];
    }
}
