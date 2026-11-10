<?php

namespace app\transform\api;

use app\libs\Util;
use app\model\order\OrderGoodsModel;
use app\transform\Transform;

class UserTransform extends Transform
{

    public function transform($item): array
    {
        return [
            'id'                                              => $item['id'],
            'username'                                        => $item['username'],
            'phone'                                           => $item['phone'],
            'email'                                           => $item['email'],
            'nick_name'                                       => $item['nick_name'],
            'head_img'                                        => $item['head_img'],
            'gender'                                          => $item['gender'],
            'birthday'                                        => $item['birthday'],
            'province'                                        => $item['province'],
            'city'                                            => $item['city'],
            'commission'                                      => Util::getAmountFormat($item['commission'],$item['currency']),
            'balance'                                         => Util::getAmountFormat($item['balance'],$item['currency']),
            'is_agent'                                        => $item['is_agent'],
            'language'                                        => $item['language'],
            'currency'                                        => $item['currency'],
            'notification_count'                              => $item['notification_count'],
            'address_count'                                   => $item['address_count'],
            'currency_info'                                   => $item['currency_info'] ? (new CurrencyTransform())->transform($item['currency_info']) : '',
            'order_status_'.OrderGoodsModel::STATUS_PAYMENT   => $item['order_status_'.OrderGoodsModel::STATUS_PAYMENT],
            'order_status_'.OrderGoodsModel::STATUS_PAID      => $item['order_status_'.OrderGoodsModel::STATUS_PAID],
            'order_status_'.OrderGoodsModel::STATUS_SEND      => $item['order_status_'.OrderGoodsModel::STATUS_SEND],
            'consumption_amount'.OrderGoodsModel::STATUS_SEND => $item['consumption_amount'],
            'hobby'.OrderGoodsModel::STATUS_SEND              => $item['hobby'],
        ];
    }
}
