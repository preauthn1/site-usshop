<?php

namespace app\model\order;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderDamagesModel
 * Class OrderDamagesModel
 * @package app\model
 */
class OrderDamagesModel extends BaseModel
{
    use SoftDelete;

    //规则类型 1非节假日 2节假日 3平台取消赔付违约金
    const TYPE_NON_HOLIDAYS = 1;//非节假日
    const TYPE_HOLIDAYS = 2;//节假日
    const TYPE_PLATFORM_CANCEL = 3;//平台取消赔付违约金

    static $TYPE_MAP = [
        self::TYPE_NON_HOLIDAYS => "非节假日",//非节假日
        self::TYPE_HOLIDAYS => "节假日",//节假日
        self::TYPE_PLATFORM_CANCEL => "平台取消赔付违约金",//平台取消赔付违约金
    ];

    protected $name = 'order_damages';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            $item['type_str'] = self::$TYPE_MAP[$item['type']];
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 获取赔付金额
     * @param $day
     * @param $amount
     * @param int $is_holiday 1非节假日 2节假日 3平台取消赔付违约金
     * @return float
     */
    public static function getRateAmount($day = 0, $amount = 0, $is_holiday = self::TYPE_HOLIDAYS)
    {
        $map = [
            'type' => $is_holiday
        ];
        if ($day < 0) {
            return 0;
        }
        if ($day == 0) {
            $data = self::where($map)->where('max_day', '=', $day)->where('min_day', '=', $day)->find();
        } else {
            $data = self::where($map)->where('max_day', '>', $day)->where('min_day', '<=', $day)->find();
        }
        $rate_amount = 0;
        if (!empty($data)) {
            $rate_amount = floatval($amount) * ($data['ratio'] / 100);
        }
        return $rate_amount;
    }

    /**
     * 获取赔付金额列表
     * @param int $is_holiday 1非节假日 2节假日 3平台取消赔付违约金
     * @return array
     */
    public static function getListAttr($is_holiday = self::TYPE_HOLIDAYS)
    {
        $map = [
            'type' => $is_holiday
        ];
        $data = self::where($map)->select();
        if (empty($data)) {
            return [];
        }
        return $data;
    }
}
