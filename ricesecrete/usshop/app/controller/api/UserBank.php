<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\UserBankModel;
use app\repository\UserBankRepository;
use app\transform\BulletinTransform;
use app\transform\UserBankTransform;
use app\validate\UserBankValidate;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;

class UserBank extends BaseIndexEncryptController
{
    protected $repository;
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, UserBankRepository $repository, UserBankModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
        $this->repository = $repository;
    }

    /**
     * 银行卡列表
     * @throws DbException
     */
    public function index(UserBankTransform $transform): Json
    {

        $search = [
            'user_id' => $this->currentUser['user_id']
        ];
        $user_banks = $this->repository->with(['bank'])->search($search)->get();
        $data = $transform->transformCollection($user_banks->toArray());
        return self::returnMsg(1, $data);
    }

    /**
     * 新增银行卡
     * @throws DbException
     */
    public function onSave(Request $request): Json
    {
        $formData = [
            'id'           => $request->param('id'),
            'bank_id'      => $request->param('bank_id'),
            'bank_no'      => $request->param('bank_no', 0),
            'opening_bank' => $request->param('opening_bank'),
            'card_no'  => $request->param('card_no'),
        ];

        if (empty($formData['id'])) {
            $validate = UserBankValidate::class.'.add';
        } else {
            $validate = UserBankValidate::class;
        }
        $this->validate($formData, $validate);

        $formData['user_id'] = $this->currentUser['user_id'];

        // 判断已经保存了多少个银行卡
        $count = $this->model->where(['user_id' => $formData['user_id']])->count();

        // TODO 获取可以绑定银行卡数量配置
        $bank_limit_count = 5;
        if ($count >= $bank_limit_count) {
            return self::returnMsg(8001, [],sprintf("最多添加 %d 张银行卡哦～", $bank_limit_count));
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
     * @return \think\response\Json
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
