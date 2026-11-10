<?php

namespace app\transform;

class ArticleTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'      => $item['id'],
            'title'   => $item['title'],
            'type'    => $item['type'],
            'content' => $item['content'],
        ];
    }
}
