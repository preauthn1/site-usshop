<?php

namespace app\transform;

class NotificationTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'      => $item['id'],
            'type'    => $item['type'],
            'title'   => $item['title'],
            'content' => $item['content'],
            'read'    => $item['read'],
        ];
    }
}
