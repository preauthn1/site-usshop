<?php

namespace app\transform;

class UserHobbyTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'       => $item['id'],
            'title'    => $item['title'],
            'src'      => $item['src'],
            'selected' => (bool) $item['user_id'],
        ];
    }
}
