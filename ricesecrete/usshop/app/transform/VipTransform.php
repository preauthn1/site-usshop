<?php

namespace app\transform;

class VipTransform extends Transform
{
    public function transform($item): array
    {
        $data = [
            'id'         => $item['id'],
//            'vip_id'   => $item['vip_id'],
            'level'      => $item['level'],
            'name'       => $item['name'],
            'src'        => $item['src'],
            'money'      => $item['money'],
            'invite_num' => $item['invite_num'],
            'status'     => $item['status'],
        ];
        if (isset($item['rights'])) {
            $data['rights'] = (new RightTransform())->transformCollection($item['rights']);
        }

        if (isset($item['pivot'])) {
            $data['number'] = $item['pivot']['number'];
            $data['unit'] = $item['pivot']['unit'];
        }
        return $data;
    }
}
