<?php

namespace app\transform;

class BulletinTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'      => $item['id'],
            'title'   => $item['title'],
            'content' => $item['content'],
        ];
    }
}
