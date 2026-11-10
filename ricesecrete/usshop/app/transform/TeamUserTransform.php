<?php

namespace app\transform;

class TeamUserTransform extends Transform
{
    public function transform($item): array
    {
        return [
            'id'            => $item['id'],
            'nick_name'     => $item['nick_name'],
            'phone'         => $item['phone'],
            'level'         => $item['level'],
            'register_date' => date('Y-m-d', strtotime($item['create_time'])),
        ];
    }
}
