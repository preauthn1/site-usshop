<?php

namespace app\transform;

class CustomerServiceTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'type'    => $item['type'],
            'title'   => $item['title'],
            'content' => $item['content'],
            'sort'    => $item['sort'],
        ];
    }
}
