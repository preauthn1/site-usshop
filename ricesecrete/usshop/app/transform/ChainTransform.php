<?php

namespace app\transform;

class ChainTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'      => $item['id'],
            'name'   => $item['name'],
        ];
    }
}
