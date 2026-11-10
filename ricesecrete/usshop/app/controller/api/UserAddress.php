<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\api\ApiIndexCode;
use app\model\user\UserAddressModel;
use app\transform\UserAddressTransform;
use app\validate\UserAddressValidate;
use think\App;
use think\db\exception\DbException;
use think\Request;
use think\response\Json;


/**
 * 用户收货地址接口
 * Class User
 * @package app\controller
 */
class UserAddress extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, UserAddressModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 获取用户收货地址
     * @param  Request  $request
     * @param  UserAddressTransform  $transform
     * @return Json
     * @throws DbException
     */
    public function getPage(Request $request, UserAddressTransform $transform): Json
    {
        $user_id = $this->currentUser['user_id'];
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);

        $map[] = ['user_id', '=', $user_id];
        $addresses = $this->model->getPageList($map, 'id asc', $page, $limit);
        $data = $transform->transformPages($addresses);
        return self::returnMsg(1, $data);
    }

    /**
     * 更新用户收货地址
     * @param  Request  $request
     * @return Json
     */
    public function onSave(Request $request): Json
    {
        $formData = $request->post();

        if (empty($formData['id'])) {
            $validate = UserAddressValidate::class.'.add';
        } else {
            $validate = UserAddressValidate::class;
        }
        $this->validate($formData, $validate);

        $formData['user_id'] = $this->currentUser['user_id'];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            if ($formData['is_default'] === true) {
                // 把其他的is_default改为2
                $this->model->resetIsDefault($ret['id']);
            }
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } else {
            return self::returnMsg(ApiIndexCode::FAIL);
        }
    }

    /**
     * 删除用户收货地址
     * @param $id
     * @return Json
     */
    public function onDelete($id): Json
    {
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 设置默认地址
     * @param  Request  $request
     * @return Json
     * @throws DbException
     */
    public function onIsDefault(Request $request): Json
    {
        $formData = [
            'id'         => $request->param('id'),
            'is_default' => $request->param('is_default'),
        ];
        $validate = UserAddressValidate::class.'.isDefault';
        $this->validate($formData, $validate);

        // 判断是否至少有一个默认地址
        $count = $this->model->where(['user_id' => $this->currentUser['user_id'], 'is_default' => 1])->count();
        if ($count == 0) {
            return self::returnMsg(ApiIndexCode::FAIL, [],'至少设置一个默认地址');
        }

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            if ($formData['is_default'] === true) {
                // 把其他的is_default改为2
                $this->model->resetIsDefault($ret['id']);
            }
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } else {
            return self::returnMsg(ApiIndexCode::FAIL);
        }
    }
}
