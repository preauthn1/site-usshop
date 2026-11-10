<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\Util;
use app\model\system\SysBannerModel;
use app\model\tourism\TourismBannerModel;
use app\model\tourism\TourismIndexModel;
use app\model\tourism\TourismProductModel;
use app\model\tourism\TourismTypeModel;
use think\facade\Cache;
use think\Request;


/**
 * 首页请求接口
 * Class Index
 * @package app\controller
 */
class Index extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 测试
     * @return \think\response\Json
     */
    public function index()
    {
        return self::returnMsg(1, []);
    }

    /**
     * 获取banner
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onBanner(Request $request)
    {
        $limit = $request->param('limit', 3);
        $data = SysBannerModel::getAllData($limit);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取首页分类
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onType(Request $request)
    {
        $limit = $request->param('limit', 4);
        $data = TourismTypeModel::getAllData($limit);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取首页推荐旅游套餐banner
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onTourismBanner(Request $request)
    {
        $limit = $request->param('limit', 10);
        $data = TourismBannerModel::getAllData($limit);
        return self::returnMsg(1, $data);
    }

    /**
     * 获取首页分类
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onTourismIndexType(Request $request)
    {
        $limit = $request->param('limit', 10);
        $data = TourismIndexModel::getAllData($limit);
        $list = [];
        $list[] = [
            'id' => '',
            'name' => '全部',
            'img' => '',
        ];
        foreach ($data as $item) {
            $list[] = $item;
        }
        return self::returnMsg(1, $list);
    }

    /**
     * 获取套餐列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function onProductList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $index_id = $request->param('index_id', '');
        $tourism_product_model = new TourismProductModel();
        $map = [];
        $map[] = ['status', '=', 1];
        $map[] = ['index_id', '>', 0];
        if (!empty($index_id)) {
            $map[] = ['index_id', 'like', "%:" . $index_id . ";%"];
        }
        $order = "update_time DESC";
        $field = "id,title,tag,path,index_id,type_id,days,min_price,min_kid_price,city,img,buy";
        $data = $tourism_product_model->getPageData($map, $page, $limit, $order, $field);
        foreach ($data['dataList'] as $k => $v) {
            $data['dataList'][$k]['spec_name'] = Util::getDaysNumStr($v['days']);
            $data['dataList'][$k]['city'] = str_replace('市', '', $v['city']) . "出发";
            $data['dataList'][$k]['min_price'] = Util::getAmountFormat($v['min_price']);
            $data['dataList'][$k]['min_kid_price'] = Util::getAmountFormat($v['min_kid_price']);
            if (!empty($v['tag'])) {
                if (strpos($v['tag'], "|") >= 0) {
                    $data['dataList'][$k]['tag_arr'] = explode("|", $v['tag']);
                } else {
                    $data['dataList'][$k]['tag_arr'] = [$v['tag']];
                }
            } else {
                $data['dataList'][$k]['tag_arr'] = [];
            }
        }
        return self::returnMsg(1, $data);
    }

    /**
     * 获取城市经纬度
     * @param Request $request
     * @return \think\response\Json
     */
    public function onIpLocation(Request $request)
    {
        $ip = Util::get_real_ip();
        $key_md5 = md5("ip_" . $ip);
        $temp = Cache::get($key_md5, '');
        if (empty($temp)) {
            $data = Util::getIPAddress($ip);
            Cache::set($key_md5, serialize($data), 3600 * 24 * 30);
        } else {
            $data = unserialize($temp);
        }
        return self::returnMsg(1, $data);
    }

}
