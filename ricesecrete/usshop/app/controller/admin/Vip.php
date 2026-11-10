<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\model\user\RightModel;
use app\model\user\VipModel;
use app\repository\VipRepository;
use app\transform\VipTransform;
use app\validate\VipValidate;
use think\App;
use think\Request;
use think\response\Json;

/**
 * 会员等级
 * Level
 * Class Hobby
 * @package app\controller
 */
class Vip extends BaseEncryptController
{
    protected VipRepository $repository;

    public function __construct(VipModel $model, VipRepository $repository, App $app)
    {
        parent::__construct($app);
        $this->model = $model;
        $this->repository = $repository;
    }

    /**
     * 分页列表
     * @param  string  $status
     * @param  string  $type
     * @param  string  $keyword
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     */
    public function getPage(
        string $status = '',
        string $type = '',
        string $keyword = '',
        int $page = 1,
        int $limit = 10,
        string $init = ''
    ): Json {
        if (!empty($init)) {
            $map = [
                ['status', '=', 1]
            ];
            $rightsDict = (new RightModel())->getAll($map, ['id', 'name']);
            $out_data['rightsDict'] = $rightsDict;
            return self::returnMsg(1, $out_data);
        }
        $map = [];
        if (!empty($keyword)) {
            $map[] = ['title', 'like', '%'.trim($keyword).'%'];
        }
        if (!empty($type)) {
            $map[] = ['type', '=', $type];
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
    public function getInfo($id, VipTransform $transform)
    {
        $vip = $this->repository->with(['rights'])->find($id);
        $data = $transform->transform($vip->toArray());
        if (!empty($data)) {
            return self::returnMsg(1, $data);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存
     * @param  Request  $request
     * @return Json
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'         => $request->param('id'),
            'name'       => $request->param('name'),
            'level'      => $request->param('level'),
            'src'        => $request->param('src'),
            'money'      => $request->param('money'),
            'invite_num' => $request->param('invite_num'),
            'status'     => $request->param('status'),
            'rights'     => $request->param('rights'),
        ];
        if (empty($formData['id'])) {
            $validate = VipValidate::class.'.add';
        } else {
            $validate = VipValidate::class;
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
     * 删除
     * @param $id
     * @return Json
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
