<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\system\ChainModel;
use app\validate\ChainValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 控制器-虚拟币链管理
 * Class Chain
 * @package app\controller\admin
 */
class Chain extends BaseEncryptController
{

    public function __construct(App $app, ChainModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 获取分页数据
     */
    public function getPage($name = '', $code = '', $page = 1, $limit = 10)
    {
        $map = [];
        if ($name !== '') {
            $map[] = ['name', 'like', "%{$name}%"];
        }
        if ($code !== '') {
            $map[] = ['code', 'like', "%{$code}%"];
        }


        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
        return self::returnMsg(1, [
            'totalCount'  => $data['total'],
            'currentPage' => $data['current'],
            'pageList'    => $dataList
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return Json
     */
    public function getInfo($id): Json
    {
        $chain = $this->model->getInfo($id);
        if (!empty($chain)) {
            return self::returnMsg(1, $chain);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存数据
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'          => $request->param('id'),
            'name'        => $request->param('name'),
            'code'        => $request->param('code'),
            'icon'        => $request->param('icon'),
            'description' => $request->param('description'),
            'status'      => $request->param('status'),
        ];

        if (empty($formData['id'])) {
            $validate = ChainValidate::class.'.add';
        } else {
            $validate = ChainValidate::class;
        }
        $this->validate($formData, $validate);
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 删除数据
     */
    public function onDelete($id): Json
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            $this->model->delRedis($id);
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param  Request  $request
     * @return Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id'     => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
