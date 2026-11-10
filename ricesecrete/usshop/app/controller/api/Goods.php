<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\api\ApiIndexCode;
use app\libs\Util;
use app\model\goods\GoodsBuyLogModel;
use app\model\goods\GoodsCartModel;
use app\model\goods\GoodsProductModel;
use app\model\goods\GoodsSpecModel;
use app\model\goods\GoodsTypeModel;
use app\model\order\OrderCommentModel;
use app\model\order\OrderModel;
use app\model\order\OrderPayModel;
use app\model\system\PaymentChannelModel;
use app\model\user\UserAddressModel;
use app\model\user\UserCollectModel;
use app\model\user\UserSearchModel;
use app\model\user\UserViewModel;
use think\facade\Cache;
use think\Request;
use think\response\Json;


/**
 * 商品接口
 * Class Goods
 * @package app\controller
 */
class Goods extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 测试
     * @return Json
     */
    public function index()
    {
        return self::returnMsg(1, []);
    }

    /**
     * 获取商品分类
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onType(Request $request)
    {
        $limit = $request->param('limit', 1000);
        $type_id = $request->param('type_id/d', 0);
        $data = GoodsTypeModel::getAllDataList($type_id, $limit);

        return self::returnMsg(1, $data);
    }

    /**
     * 获取商品列表
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DbException
     */
    public function onProductList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $classify = $request->param('classify', OrderModel::TYPE_NORMAL);
        $type_id = $request->param('type_id', '');
        $rank = $request->param('rank', 'asc');//asc 升序，desc 降序
        $ortab = $request->param('order', 'hot');// hot 热门商品 ,all 全部 ，new 新品上架
        $tab = $request->param('tab', '');// all 综合 ，buy销量 ，price 价格
        $day = $request->param('day', '');// 0-6 3条
        $keyword = $request->param('keyword', '');
        if (!empty($keyword)) {
            if (mb_strlen($keyword) > 255) {
                return self::returnMsg(ApiIndexCode::FAIL, [], '搜索关键词过长');
            }
        }

        $goods_product_model = new GoodsProductModel();
        $map = [];
        $map[] = ['status', '=', 1];
        $map[] = ['delete_time', '=', 0];
        $map[] = ['classify', '=', $classify];
        $order = "sort DESC,update_time DESC";
        $order_field = 'update_time';
        switch ($ortab) {
            case 'hot';
                //热门商品
                $order_field = 'hot DESC';
                break;
            case 'all';
                //新品上架
                $order_field = '';
                break;
            case 'new';
                //新品上架
                $order_field = 'create_time DESC';
                break;
        }
        switch ($tab) {
            case 'all';
                //综合
                if (!empty($order_field)) {
                    $order = "sort DESC,{$order_field},update_time DESC";
                } else {
                    $order = "sort DESC,update_time DESC";
                }
                break;
            case 'buy';
                //销量
                if (!empty($order_field)) {
                    $order = "buy DESC,{$order_field},update_time DESC";
                } else {
                    $order = "buy DESC,update_time DESC";
                }
                break;
            case 'price';
                if (empty($rank)) {
                    $rank = 'asc';
                }
                //价格
                if (!empty($order_field)) {
                    $order = "min_price $rank,{$order_field},update_time DESC";
                } else {
                    $order = "min_price $rank,update_time DESC";
                }
                break;
        }
        if (!empty($day)) {
            $page += intval($day);
        }
        if (!empty($type_id)) {
            $type_id_arr = GoodsTypeModel::getChildId($type_id);
            $map[] = ['type_id', 'in', $type_id_arr];
        }
        if (!empty($keyword)) {
            $map[] = ['title', 'like', "%{$keyword}%"];
            // 增加搜索记录
            if (isset($this->currentUser['user_id'])) {
                UserSearchModel::addKeyword($this->currentUser['user_id'], $keyword, UserSearchModel::TYPE_NORMAL);
            }
        }
        $field = "id,title,classify,express_type,type_id,min_price,img,buy";
        $data = $goods_product_model->getPageData($map, $page, $limit, $order, $field);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取搜索商品推荐关键词
     * @param Request $request
     */
    public function onSearchRecommend(Request $request)
    {
        $user_id = '';
        if (isset($this->currentUser['user_id'])) {
            $user_id = $this->currentUser['user_id'];
        }
        if (empty($user_id)) {
            $keyword = [];
        } else {
            $keyword = UserSearchModel::getKeyword($user_id, UserSearchModel::TYPE_NORMAL);
        }
        $keyword_sys = UserSearchModel::getKeyword(0, UserSearchModel::TYPE_NORMAL);
        $out_data = [
            'keyword' => $keyword,
            'keyword_sys' => $keyword_sys,
        ];
        return self::returnMsg(1, $out_data);
    }

    /**
     * 删除搜索关键词
     * @param Request $request
     * @return \think\response\Json
     */
    public function onDelKeyword(Request $request)
    {
        $keyword = $request->param('keyword', '');
        $type = $request->param('type', UserSearchModel::TYPE_NORMAL);
        if (empty($keyword)) {
            return self::returnMsg(0, '参数错误');
        }
        $user_id = '';
        if (isset($this->currentUser['user_id'])) {
            $user_id = $this->currentUser['user_id'];
        }
        UserSearchModel::delKeyword($user_id, $keyword, $type);
        return self::returnMsg(1, '');
    }

    /**
     * 删除搜索关键词
     * @param Request $request
     * @return \think\response\Json
     */
    public function onClearKeyword(Request $request)
    {
        $type = $request->param('type', UserSearchModel::TYPE_NORMAL);
        $user_id = '';
        if (isset($this->currentUser['user_id'])) {
            $user_id = $this->currentUser['user_id'];
        }
        UserSearchModel::clearKeyword($user_id, $type);
        return self::returnMsg(1, '');
    }

    /**
     * 商品详情
     * @param Request $request
     * @return Json
     */
    public function onProductDetails(Request $request)
    {
        $id = $request->param('id', '');
        if (empty($id)) {
            return self::returnMsg(0, '参数错误');
        }
        $goods_product_model = new GoodsProductModel();
        $data = $goods_product_model->getProductDetails($id);
        if (empty($data)) {
            return self::returnMsg(0, '套餐不存在');
        } else {
            $data['buy_log'] = GoodsBuyLogModel::getBuyLog($id, 3);
            $data['comment_log'] = OrderCommentModel::getCommentList($id, 1);
            $data['is_collect'] = 0;
            if (isset($this->currentUser['user_id'])) {
                if (!empty($this->currentUser['user_id'])) {
                    $data['is_collect'] = UserCollectModel::isCollect($this->currentUser['user_id'], $id, $data['classify']);
                    //添加浏览记录
                    UserViewModel::addView($this->currentUser['user_id'], $id, $data['classify']);
                }
            }
        }
        return self::returnMsg(1, $data);
    }

    /**
     * 获取下单前详情
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onOrderInfo(Request $request)
    {
        //[{"product_id":56,"spec_id":1,"number":1},{"product_id":56,"spec_id":2,"number":2}]
//        $product_ids = [
//            [
//                'product_id' => 1,//产品id
//                'spec_id' => 1,//产品规格id
//                'number' => 1//购买数量
//            ]
//        ];
        $product_ids = $request->param('product_ids', '');
        if (empty($product_ids)) {
            return self::returnMsg(0, [], '参数错误');
        }
        //多个商品添加订单
        $product_ids = json_decode($product_ids, true);
        if (empty($product_ids)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $goods_spec_model = new GoodsSpecModel();
        $out_data = $goods_spec_model->getSpecInfoData($product_ids);
        if (empty($out_data)) {
            return self::returnMsg(0, '套餐不存在');
        } else {
            $map = [
                'user_id' => $this->currentUser['user_id'],
                'is_default' => 1
            ];
            $address = UserAddressModel::where($map)->find();
            if (empty($address)) {
                $out_data['address'] = UserAddressModel::where('user_id', $this->currentUser['user_id'])->order('id DESC')->find();
            } else {
                $out_data['address'] = $address;
            }
            $product_ids_arr = [];
            foreach ($product_ids as $value) {
                $product_ids_arr[] = $value['product_id'];
            }
            $express_count = GoodsProductModel::whereIn('id', $product_ids_arr)->where('express_type', 1)->count();
            $out_data['express_type'] = $express_count > 0 ? 1 : 2;
        }
        $out_data['pay_list'] = PaymentChannelModel::field(['id', 'name', 'ident' => 'type', 'src' => 'icon','type'=>"pay_type"])->where('status', 1)->select();
        return self::returnMsg(1, $out_data);
    }

    /**
     * 创建订单
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCreateOrder(Request $request)
    {
        //[{"product_id":1,"spec_id":1,"number":1,"take_day":"2024-10-11"},{"product_id":1,"spec_id":2,"number":2,"take_day":"2024-10-11"}]
//        $product_ids = [
//            [
//                'product_id' => 1,//产品id
//                'spec_id' => 1,//产品规格id
//                'number' => 1,//购买数量
//                'take_day'=>'2024-10-11',//取货日期
//            ]
//        ];
        $product_ids = $request->param('product_ids', '');
        $order_product_key = "create_order_" . md5(json_encode($product_ids) . $this->currentUser['user_id']);
        $order_data = Cache::get($order_product_key, '');
        if (!empty($order_data)) {
            $order_data = unserialize($order_data);
            return self::returnMsg(1, $order_data);
        }
        //订单类型
        $type = $request->param('type', OrderModel::TYPE_NORMAL);
        if (empty($product_ids)) {
            return self::returnMsg(0, [], '参数错误');
        }
        $contacts = $request->param('contacts');
        if (!empty($contacts)) {
            //        $contacts = [[
//            'name' => '姓名',
//            'mobile' => '18785169822',
//            'province' => '贵州省',
//            'city' => '贵阳市',
//            'area' => '观山湖区',
//            'address' => '俊发中心'
//        ]];
            $contacts = json_decode($contacts, true);
        } else {
            $contacts = [];
        }
        $remarks = $request->param('remarks', '');
        $res = ['code' => 0];
        $order_data = [];
        //多个商品添加订单
        $product_ids = json_decode($product_ids, true);
        if (empty($product_ids)) {
            return self::returnMsg(0, [], '参数错误');
        }
        foreach ($product_ids as $k => $v) {
            $map = [
                'product_id' => $v['product_id'],
                'id' => $v['spec_id'],
            ];
            $store = GoodsSpecModel::where($map)->value('store');
            if ($store < $v['number']) {
                return self::returnMsg(0, [], '库存不足');
            }
        }
        $order_id = "";
        foreach ($product_ids as $v) {
            $data = [
                'type' => $type,//正常商品
                'product_id' => $v['product_id'],//产品id
                'user_id' => $this->currentUser['user_id'],//
                'spec_id' => $v['spec_id'],//套餐id
                'number' => $v['number'],//数量
                'take_day' => $v['take_day'],//自提时间
                'contacts' => $contacts,
                'remarks' => $remarks
            ];
            $order_model = new OrderModel();
            $res = $order_model->addOrder($data);
            if ($res['code'] == 1) {
                $order_data[] = $res['data'];
                $order_id = $res['data']['order_id'];
            }
        }
        if ($res['code'] == 1) {
            Cache::set($order_product_key, serialize($order_data), 160);
            Cache::set(md5($order_id), $order_product_key, 160);
            return self::returnMsg(1, $order_data);
        } else {
            return self::returnMsg($res);
        }
    }

    /**
     * 获取订单详情
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderDetails(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, '参数错误');
        }
        $order_model = new OrderModel();
        $temp = $order_model->getGoodsOrderInfo($order_id);
        if ($temp['code'] == 1) {
            $pay_type = PaymentChannelModel::field(['id', 'name', 'ident' => 'type', 'src' => 'icon','type'=>"pay_type"])->where('status', 1)->select();
            $pay_list = [];
            foreach ($pay_type as $key => $value) {
                $pay_list[] = $value;
            }
            $temp['data']['pay_list'] = $pay_list;
            return self::returnMsg(1, $temp['data']);
        } else {
            return self::returnMsg($temp);
        }
    }

    /**
     * 获取分享订单详情
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOrderShareDetails(Request $request)
    {
        $order_id = $request->param('order_id', '');
        if (empty($order_id)) {
            return self::returnMsg(0, '参数错误');
        }
        $order_model = new OrderModel();
        $temp = $order_model->getGoodsOrderInfos($order_id);
        return self::returnMsg(1, $temp);
    }

    /**
     * 获取购物车列表
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCartList(Request $request)
    {
        $user_id = $this->currentUser['user_id'];
        $res = GoodsCartModel::getCartList($user_id);
        return self::returnMsg(1, $res);
    }

    /**
     * 添加购物车
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onAddCart(Request $request)
    {
        $product_id = $request->param('product_id');
        $spec_id = $request->param('spec_id');
        $number = $request->param('number', 0);
        $change = $request->param('change', 'add');//add 自增，sub自减,mod 修改
        $user_id = $this->currentUser['user_id'];
        // 判断库存
        $spec_info = GoodsSpecModel::getSpecInfo($spec_id);
        if ($spec_info['store'] <= 0) {
            return self::returnMsg(ApiIndexCode::FAIL, [], '库存不足');
        }
        $express_type = GoodsProductModel::where('id', $product_id)->value('express_type');
        $res = GoodsCartModel::changeCart($user_id, $product_id, $express_type, $spec_id, $number, $change);
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 修改购物车
     * @param Request $request
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onEditCart(Request $request)
    {
        $id = $request->param('id');
        $spec_id = $request->param('spec_id');
        $number = $request->param('number');
        $radio = $request->param('radio', 0);
        $res = GoodsCartModel::editCart($id, $spec_id, $number, $radio);
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 清空购物车
     * @param Request $request
     * @return Json
     */
    public function onClearCart(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            return self::returnMsg(0, '参数错误');
        }
        if (strpos($id, ',')) {
            $id = explode(',', $id);
        } else {
            $id = [$id];
        }
        $user_id = $this->currentUser['user_id'];
        $res = GoodsCartModel::clearCart($user_id, $id);
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 商品分类带商品
     * @param Request $request
     * @return Json
     */
    public function onTypeWithGoods(Request $request): Json
    {
        $type_id = $request->param('type_id', 0);
        $res = GoodsTypeModel::getTypeWithGoodsByParentId($type_id);
        return self::returnMsg(1, $res);
    }
}
