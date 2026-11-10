<?php

namespace app\transform;

class RightTransform extends Transform
{
    public function transform($item): array
    {
        $data = [
            'id'      => $item['id'],
            'name'    => $item['name'],
            'content' => $item['content'],
            'src'     => $item['src'],
        ];

        if (isset($item['vips'])) {
            $data['vips'] = [];
            if ($item['id'] < 3) {
                $data['vips'] = (new VipTransform())->transformCollection($item['vips']);
            }
        }

        if (isset($item['pivot'])) {
            $data['pivot_id'] = $item['pivot']['id'];
            $data['is_open'] = $item['pivot']['is_open'];
            $data['number'] = $item['pivot']['number'];
            $data['unit'] = $item['pivot']['unit'];
        }
        return $data;
    }
}
