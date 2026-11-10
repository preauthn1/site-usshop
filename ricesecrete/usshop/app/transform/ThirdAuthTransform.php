<?php

namespace app\transform;

class ThirdAuthTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'name'  => $item['name'],
            'ident' => $item['ident'],
        ];
    }
}
