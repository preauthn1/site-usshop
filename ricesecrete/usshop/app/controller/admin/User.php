<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\base\BaseModel;
use app\libs\api\ApiIndexCode;
use app\libs\NestedSets;
use app\model\system\CurrencyModel;
use app\model\system\LanguageModel;
use app\model\user\UserModel;
use app\model\user\UserTeamModel;
use app\validate\UserValidate;
use think\App;
use think\facade\Config;
use think\facade\Db;
use think\Request;
use think\response\Json;

/**
 * 用户管理
 * User
 * Class User
 * @package app\controller
 */
class User extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->model = new UserModel();
    }

    /**
     * 分页列表
     * @param  Request  $request
     * @param  int  $page
     * @param  int  $limit
     * @return Json
     */
    public function getPage(Request $request, $page = 1, $limit = 10)
    {
        $create_time = $request->param('create_time', []);
        $id = $request->param('id', '');
        $email = $request->param('email', '');
        $popularize_code = $request->param('popularize_code', '');
        $is_agent = $request->param('is_agent', '');
        $top_user_email = $request->param('top_user_email', '');
        $keyword = $request->param('keyword', '');

        $map = [];
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        if (!empty($popularize_code)) {
            $map[] = ['popularize_code', '=', $popularize_code];
        }
        if (!empty($email)) {
            $map[] = ['email', '=', $email];
        }
        if (is_numeric($id)) {
            $map[] = ['id', '=', $id];
        }
        if (is_numeric($is_agent)) {
            $map[] = ['is_agent', '=', $is_agent];
        }
        if ($top_user_email) {
            $top_user = $this->model->getInfoMap(['email' => $top_user_email]);
            if ($top_user) {
                $map[] = ['top_user_id', '=', $top_user['id']];
            } else {
                $map['top_user_id'] = '-1';
            }
        }

        if (!empty($create_time)) {
            $map[] = ['create_time', '>=', strtotime($create_time[0])];
            $map[] = ['create_time', '<=', strtotime($create_time[1])];
        }
        if (!empty($keyword)) {
            $map[] = ['email|nick_name', 'like', '%' . trim($keyword) . '%'];
        }

        $data = $this->model->getPageData($map, $page, $limit);
        $dataList = $data['dataList'];
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
        $data = $this->model->getInfo($id);
        if (!empty($data)) {
            unset($data['password']);
            return self::returnMsg(1, $data);
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
        // `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
        //  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
        //  `user_id` int DEFAULT '0' COMMENT '站内消息用户id',
        //  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息详情 （可以是富文本）',
        //  `read` tinyint(1) DEFAULT '1' COMMENT '1未读  2已读',
        $formData = [
            'id' => $request->param('id'),
            'phone' => $request->param('phone'),
            'email' => $request->param('email'),
            'password' => $request->param('password'),
            'gender' => $request->param('gender'),
            'nick_name' => $request->param('nick_name'),
            'head_img' => $request->param('head_img'),
            'level' => $request->param('level'),
            'status' => $request->param('status'),
            'is_agent' => $request->param('is_agent'),
            'language' => $request->param('language', ''),
            'currency' => $request->param('currency', ''),
        ];
        // 获取默认货币和默认语言
        if ($formData['language'] == '') {
            $formData['language'] = LanguageModel::where('is_default', BaseModel::STATUS_ENABLE)
                ->where('status', BaseModel::STATUS_ENABLE)
                ->value('code');
        }
        if ($formData['currency'] == '') {
            $formData['currency'] = CurrencyModel::where('is_default', BaseModel::STATUS_ENABLE)
                ->where('status', BaseModel::STATUS_ENABLE)
                ->value('code');
        }
        if (empty($formData['id'])) {
            if (empty($formData['password'])) {
                $default_pwd = Config::get('system.default_pwd');
                $formData['password'] = password_hash($default_pwd, PASSWORD_BCRYPT);
            } else {
                $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT);
            }
        } else {
            if (empty($formData['password'])) {
                unset($formData['password']);
            } else {
                $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT);
            }
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
     * @return \think\response\Json
     * @throws \Exception
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $validate = UserValidate::class . '.upStatus';
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }


    /**
     * 修改用户上级id
     * @param  Request  $request
     * @return Json
     */
    public function onChangeTopUserId(Request $request): Json
    {
        $formData = [
            'id'         => $request->param('id'),
            'top_user_id'     => $request->param('top_user_id'),
        ];
        // 获取两个id在user_team表下的id
        $user_team_model = new UserTeamModel();
        $team_user_id = $user_team_model->where('user_id', $formData['id'])->value('id');
        $team_top_user_id = $user_team_model->where('user_id', $formData['top_user_id'])->value('id');
        // 开启事务
        Db::startTrans();
        try {
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                $nestedObj = new NestedSets(new UserTeamModel());
                $result = $nestedObj->moveUnder($team_user_id, $team_top_user_id);
                if ($result) {
                    Db::commit();
                } else {
                    // 移动失败
                    Db::rollback();
                    return self::returnMsg(ApiIndexCode::FAIL, [], '修改上级失败');
                }
            }
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return self::returnMsg(ApiIndexCode::FAIL, [], $e->getMessage());
        }
    }
}
