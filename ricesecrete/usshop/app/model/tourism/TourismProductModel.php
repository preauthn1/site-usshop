<?php

namespace app\model\tourism;

use app\base\BaseModel;
use app\libs\Util;
use app\model\user\UserCollectModel;
use think\model\concern\SoftDelete;

/**
 * 模型-TourismProductModel
 * Class TourismProductModel
 * @package app\model
 */
class TourismProductModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'tourism_product';

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
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }


    /**
     * 获取分页列表
     * @param $map
     * @param $likeSql
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageDataLike($map, $likeSql, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
//        $dataList = $this->where(function ($query) use ($mapArr) {
//            $query->where($mapArr[0]); // 初始条件
//            for ($i = 1; $i < count($mapArr); $i++) {
//                $query->whereOr($mapArr[$i]); // 追加或条件
//            }
//        })->field($field);
        $dataList = $this->where($map)
            ->where($likeSql)
            ->field($field)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    public function getIndexData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 获取套餐详情
     * @param string $id
     */
    public function getProductDetails($id = '')
    {
        $field = "id,tag,path,title,min_price,min_kid_price,img,photo,details,policy_id,integral_id,merchant_id,fees,notice";
        $temp = $this->where('id', $id)->field($field)->find();
        if (empty($temp)) {
            return [];
        } else {
            $out_data = $temp->toArray();
            $out_data['img'] = $temp['img'] ?? '';
            if (!empty($temp['tag'])) {
                $temp['tag'] = str_replace(' ', '', $temp['tag']);
                $out_data['tag'] = explode('|', $temp['tag']);
            }
            if (!empty($temp['path'])) {
                $temp['path'] = str_replace(' ', '', $temp['path']);
                $out_data['path'] = explode('|', $temp['path']);
            }
            if (!empty($temp['photo'])) {
                $out_data['photo'] = explode(',', $temp['photo']);
                $temp_photo = [];
                foreach ($out_data['photo'] as $k => $v) {
                    $type = pathinfo($v, PATHINFO_EXTENSION);
                    if ($type == 'mp4') {
                        $temp_photo[] = [
                            'type' => 'video',
                            'url' => $v,
                            'img' => $v . '?x-oss-process=video/snapshot,t_5000,f_jpg'
                        ];
                    } else {
                        $temp_photo[] = [
                            'type' => 'image',
                            'url' => $v
                        ];
                    }
                }
                $out_data['photo'] = $temp_photo;
            } else {
                $out_data['photo'] = [];
            }
            if (!empty($temp['details'])) {
                $out_data['details'] = explode(',', $temp['details']);
            } else {
                $out_data['details'] = [];
            }
            if (!empty($temp['fees'])) {
                $out_data['fees'] = json_decode($temp['fees'], true);
            }
            if (!empty($temp['notice'])) {
                $out_data['notice'] = json_decode($temp['notice'], true);
                //{
                //	"apply_id": 2,//报名须知
                //	"restrict_id": 3,//报名限制
                //	"equip_id": 5,//旅游装备
                //	"policy_id": 1//退订政策
                //}
                foreach ($out_data['notice'] as $k => $v) {
                    if (!empty($v)) {
                        $ks = str_replace('_id', '_html', $k);
                        $out_data['notice_html'][$ks] = TourismExplainModel::getExplainInfo($v);
                    }
                }
                unset($out_data['notice']);
            }
            $out_data['min_price'] = Util::getAmountFormat($out_data['min_price']);
            $out_data['min_kid_price'] = Util::getAmountFormat($out_data['min_kid_price']);
            //policy_id,integral_id,merchant_id
            $out_data['policy_html'] = TourismExplainModel::getExplainInfo($temp['policy_id']);
            $out_data['integral_html'] = TourismExplainModel::getExplainInfo($temp['integral_id']);
            $out_data['merchant_info'] = TourismBureauModel::getBureauInfo($temp['merchant_id']);
            $field = "id,days_str,start_day,end_day,kid_price,price,city,product_id,journey,people,buy_people";
            $out_data['tourism_spec'] = TourismSpecModel::getSpecList($temp['id'], $field, 100, true);
        }
        return $out_data;
    }

    /**
     * 获取套餐名称
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductName($id)
    {
        $temp = self::where('id', $id)->field('title')->find();
        if (empty($temp)) {
            return '';
        } else {
            return $temp['title'];
        }
    }

    /**
     * 获取退订政策
     * @param $id
     * @return array|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getPolicyInfo($id)
    {
        $temp = self::where('id', $id)->field('policy_id')->find();
        if (empty($temp)) {
            return [
                'id' => '',
                'name' => '退订政策',
                'html' => ''
            ];
        } else {
            return [
                'id' => $temp['policy_id'],
                'name' => '退订政策',
                'html' => TourismExplainModel::getExplainInfo($temp['policy_id'])
            ];
        }
    }

    /**
     * 获取套餐id
     * @param string $merchant_id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductIdList($merchant_id = '')
    {
        $temp = self::where('merchant_id', $merchant_id)->column('id');
        if (empty($temp)) {
            return [];
        } else {
            return $temp;
        }
    }

    /**
     * 获取套餐分类id
     * @param string $tourism_id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getTourismTypePid($tourism_id = '')
    {
        $temp = self::where('id', $tourism_id)->field('type_id')->find();
        if (empty($temp)) {
            return '';
        } else {
            $type_temp = TourismTypeModel::where('id', $temp['type_id'])->find();
            if (empty($type_temp['parent_id'])) {
                return $type_temp['id'];
            }
            return TourismTypeModel::getTopId($type_temp['parent_id']);
        }
    }

    /**
     * 获取套餐信息
     * @param $id
     * @param string $field
     * @return TourismProductModel|array|mixed|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getProductInfo($id, $field = 'id,title,img')
    {
        $temp = self::where('id', $id)->field($field)->find();
        if (empty($temp)) {
            return '';
        } else {
            return $temp;
        }
    }
}
