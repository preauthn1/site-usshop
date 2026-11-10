<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\FeedbackModel;
use app\validate\FeedbackValidate;
use think\App;
use think\Request;
use think\response\Json;


/**
 * 用户收货地址接口
 * Class User
 * @package app\controller
 */
class Feedback extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, FeedbackModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }


    /**
     * 提交意见反馈
     * @param  Request  $request
     * @return Json
     */
    public function onSave(Request $request): Json
    {
        $formData = $request->post();

        $validate = FeedbackValidate::class;
        $this->validate($formData, $validate);

        $formData['user_id'] = $this->currentUser['user_id'];

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
