<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\UserChainModel;
use app\repository\UserChainRepository;
use app\transform\UserChinaTransform;
use app\validate\UserChainValidate;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;

class UserChain extends BaseIndexEncryptController
{
    protected UserChainRepository $repository;

    public function __construct(App $app, UserChainRepository $repository, UserChainModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
        $this->repository = $repository;
    }

    /**
     * 链列表
     */
    public function index(UserChinaTransform $transform): Json
    {

        $search = [
            'user_id' => $this->currentUser['user_id']
        ];
        $user_chains = $this->repository->with(['chain'])->search($search)->get();
        $data = $transform->transformCollection($user_chains->toArray());
        return self::returnMsg(1, $data);
    }

    /**
     * 新增链
     * @throws DbException
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'           => $request->param('id'),
            'chain_id'      => $request->param('chain_id'),
            'address'      => $request->param('address', 0),
        ];

        if (empty($formData['id'])) {
            $validate = UserChainValidate::class.'.add';
        } else {
            $validate = UserChainValidate::class;
        }
        $this->validate($formData, $validate);

        $formData['user_id'] = $this->currentUser['user_id'];

        // 判断已经保存了多少个链
        $count = $this->model->where(['user_id' => $formData['user_id']])->count();

        // TODO 获取可以绑定链数量配置
        $chain_limit_count = 5;
        if ($count >= $chain_limit_count) {
            return self::returnMsg(8001, [], sprintf("最多添加 %d 张链哦～", $chain_limit_count));
        }
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
}
