<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\goods\GoodsProductModel;
use app\model\order\OrderCommentModel;
use app\model\order\OrderModel;
use app\model\tourism\TourismProductModel;
use think\Request;

/**
 * 订单评价
 * OrderComment
 * Class OrderComment
 * @package app\controller
 */
class OrderComment extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new OrderCommentModel();
    }

    /**
     * 分页列表
     * @param int $type
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param string $init
     * @return \think\response\Json
     */
    public function getPage($type = '', $keyword = '', $page = 1, $limit = 10, $init = '')
    {
        if (!empty($init)) {
            $typeList = OrderModel::$TYPE_MAP;
            $out_data['typeList'] = $typeList;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
        }
        if (!empty($keyword)) {
            $map[] = ['user_name|content', 'like', '%' . trim($keyword) . '%'];
        }
        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $dataList
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $data = $this->model->getInfo($id);
        if (!empty($data)) {
            if ($data['type'] == OrderModel::TYPE_TOURISM) {
                $product_info = TourismProductModel::getProductInfo($data['product_id']);
            } else {
                $product_info = GoodsProductModel::getProductInfo($data['product_id']);
            }
            if (!empty($data['images'])) {
                $data['images'] = json_decode($data['images'], true);
            } else {
                $data['images'] = [];
            }
            $data['product_name'] = $product_info['title'];
            $data['product_img'] = $product_info['img'];
            return self::returnMsg(1, $data);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存
     * @param Request $request
     * @return \think\response\Json
     */
    public function onSave(Request $request)
    {
        return self::returnMsg(1);
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function onStatus(Request $request)
    {
        return self::returnMsg(1);
    }
}
