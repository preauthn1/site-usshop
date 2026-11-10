<?php

namespace app\transform;

class ActivityTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'       => $item['id'],
            'type'     => $item['type'],
            'title'    => $item['title'],
            'head_img' => $item['head_img'],
            'summary'  => $item['summary'],
            'content'  => $item['content'],
        ];
    }
}
