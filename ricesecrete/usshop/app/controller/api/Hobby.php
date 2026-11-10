<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\model\system\HobbyModel;
use app\model\user\UserHobbyModel;
use app\transform\UserHobbyTransform;
use app\validate\UserHobbyValidate;
use think\App;
use think\Request;
use think\response\Json;


/**
 * 兴趣接口
 * Class User
 * @package app\controller
 */
class Hobby extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, HobbyModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 获取用户兴趣
     * @param UserHobbyTransform $transform
     * @return Json
     */
    public function index(UserHobbyTransform $transform): Json
    {
        $user_id = $this->currentUser['user_id'];
        $hobby = $this->model->alias('h')
            ->leftJoin('user_hobby uh', "h.id = uh.hobby_id and uh.user_id={$user_id}")
            ->field(['h.id', 'h.title', 'h.src', 'uh.user_id'])
            ->select();
        $data = $transform->transformCollection($hobby->toarray());
        return self::returnMsg(1, $data);
    }

    /**
     * 获取用户兴趣
     * @return Json
     */
    public function hobbyList(): Json
    {
        $user_id = $this->currentUser['user_id'];
        $hobby = $this->model->alias('h')
            ->leftJoin('user_hobby uh', "h.id = uh.hobby_id and uh.user_id={$user_id}")
            ->field(['h.id', 'h.title', 'h.type', 'uh.user_id'])
            ->order('h.sort', 'desc')
            ->select();
        $hobby_list = [];
        $hobby_title = [];
        $hobby_content = [];
        // {title:"身高",value:""},
        //    {title:"体重",value:""},
        //    {title:"胸围",value:"",text:1},
        //    {title:"胸围",value:"",text:2},
        //    {title:"臀围",value:""},
        //    {title:"腰围",value:""},
        //    {title:"请选择您的偏好",value:""},
        foreach ($hobby as $key => $value) {
            if (isChinese($value['title'])) {
                $value['title'] = lang($value['title']);
            }
            if (!isset($hobby_list[$value['type']])) {
                $title = lang(HobbyModel::$hobbyType[intval($value['type'])]);
                $hobby_title[$value['type']] = [
                    'title' => $title,
                    'value' => []
                ];
                $hobby_list[$value['type']] = [
                    'type' => $value['type'],
                    'title' => $title,
                    'content' => [],
                ];
            }
            $hobby_content[$value['type']][] = [
                'id' => $value['id'],
                'title' => $value['title'],
            ];
            if ($value['user_id']) {
                $hobby_title[$value['type']]['value'][] = $value['id'];
            }
        }
        foreach ($hobby_list as &$value) {
            $value['content'] = $hobby_content[$value['type']];
            unset($value['type']);
        }
        return self::returnMsg(1, [
            'paransInfo' => $hobby_title,
            'typeList' => $hobby_list
        ]);
    }

    /**
     * 更新用户兴趣
     * @param Request $request
     * @return Json
     */
    public function update(Request $request): Json
    {
        $formData = $request->post();
        $this->validate($formData, UserHobbyValidate::class . '.user_edit');

        $user_id = $this->currentUser['user_id'];
        // 获取已选择的兴趣
        $old_hobby_ids = UserHobbyModel::where('user_id', $user_id)->column('hobby_id');
        // 获取用户提交的兴趣
        $new_hobby_ids = $formData['ids'];
        $new_hobby_ids = explode(',', $new_hobby_ids);
        // 处理兴趣
        $add_hobby = array_diff($new_hobby_ids, $old_hobby_ids);
        $del_hobby = array_diff($old_hobby_ids, $new_hobby_ids);

        if (!empty($add_hobby)) {
            $hobby_ids = HobbyModel::whereIn('id', $add_hobby)->column('id');
            $data = [];
            foreach ($hobby_ids as $hobby_id) {
                $data[] = [
                    'user_id' => $user_id,
                    'hobby_id' => $hobby_id,
                ];
            }
            !empty($data) && UserHobbyModel::insertAll($data);
        }
        if (!empty($del_hobby)) {
            UserHobbyModel::where('user_id', $user_id)
                ->whereIn('hobby_id', $del_hobby)
                ->delete();
        }
        return self::returnMsg(1);

    }
}
