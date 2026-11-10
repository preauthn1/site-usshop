<?php

namespace app\transform;

class MoneyLogTransform extends Transform
{
    public function transform($item): array
    {
        if (isset($item['user'])) {
            $data = [
                'id'          => $item['id'],
                'phone'       => $item['user']['phone'],
                'nick_name'   => $item['user']['nick_name'],
                'amount'      => abs($item['amount']),
                'create_date' => date('Y-m-d', $item['create_time']),
            ];
        } else {
            $data = [
                'id'                 => $item['id'],
                'phone'              => $item['from_user']['phone'],
                'head_img'           => $item['from_user']['head_img'],
                'nick_name'          => $item['from_user']['nick_name'],
                'amount'             => abs($item['amount']),
                'create_date'        => date('Y-m-d', $item['create_time']),
                'top_user_nick_name' => $item['from_user']['top_user']['nick_name'],
                'consumption_amount' => $item['from_user_money'],
            ];
        }
        return $data;
    }
}
