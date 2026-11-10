<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\SysBannerModel;
use think\facade\Cache;
use think\Request;

/**
 * SysBanner
 * Class SysBanner
 * @package app\controller
 */
class SysBanner extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new SysBannerModel();
    }

    /**
     * 分页列表
     * @param string $status
     * @param string $jump_type
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @param string $area
     * @return \think\response\Json
     */
    public function getPage($status = '', $jump_type = '', $keyword = '', $page = 1, $limit = 10)
    {
        $data = $this->model->getPageData($status, $jump_type, $keyword, $page, $limit, '*');
        $dataList = $data['dataList'];
        foreach ($dataList as $value) {
            $value['start_time'] = date("Y-m-d H:i:s", $value['start_time']);
            $value['end_time'] = date("Y-m-d H:i:s", $value['end_time']);
        }
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
        $banner = $this->model->getInfo($id);
        $banner['start_time'] = $banner['start_time'] * 1000;
        $banner['end_time'] = $banner['end_time'] * 1000;
        if (!empty($banner)) {
            return self::returnMsg(1, $banner);
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
        $formData = [
            'id' => $request->param('id'),
            'title' => $request->param('title'),
            'src' => $request->param('src'),
            'detail' => $request->param('detail'),
            'jump_type' => $request->param('jump_type'),
            'jump_link' => $request->param('jump_link'),
            'goods_type' => $request->param('goods_type'),
            'goods_id' => $request->param('goods_id'),
            'status' => $request->param('status'),
            'area' => $request->param('area'),
            'start_time' => $request->param('start_time'),
            'end_time' => $request->param('end_time'),
            'sort' => $request->param('sort')
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\SysBannerValidate.add';
        } else {
            $validate = 'app\validate\SysBannerValidate';
        }
        $result = $this->validate($formData, $validate);

        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            if (empty($formData['start_time'])) {
                $formData['start_time'] = time();
            } else {
                $formData['start_time'] = $formData['start_time'] / 1000;
            }
            if (empty($formData['end_time'])) {
                $formData['end_time'] = time() + (3600 * 24 * 365);
            } else {
                $formData['end_time'] = $formData['end_time'] / 1000;
            }
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                Cache::delete('sys_banner');
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
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
            Cache::delete('sys_banner');
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
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $validate = 'app\validate\SysBannerValidate.upstatus';
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                Cache::delete('sys_banner');
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }
}
