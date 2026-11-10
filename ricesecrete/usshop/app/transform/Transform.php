<?php

namespace app\transform;

abstract class Transform
{
    /**
     * @param $items
     * @return array
     */
    public function transformPages($items): array
    {
        if (isset($items['current'])) {
            return [
                'total'  => $items['total'],
                'current' => $items['current'],
                'limit' => $items['limit'],
                'dataList'    => array_map([$this, 'transform'], $items['dataList'])
            ];
        } else {
            return [
                'total'  => $items['total'],
                'current' => $items['current_page'],
                'limit' => $items['per_page'],
                'dataList'    => array_map([$this, 'transform'], $items['data'])
            ];
        }
    }

    /**
     * @param $items
     * @return array
     */
    public function transformCollection($items): array
    {
        return array_map([$this, 'transform'], $items);
    }


    public abstract function transform($item);

}
