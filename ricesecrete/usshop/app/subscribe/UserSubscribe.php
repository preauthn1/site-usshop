<?php
declare (strict_types = 1);

namespace app\subscribe;

use app\libs\NestedSets;
use app\model\user\UserModel;
use app\model\user\UserTeamModel;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

class UserSubscribe
{
    protected $model;
    public function __construct(UserModel $model)
    {
        $this->model = $model;
    }

    protected string $eventPrefix = 'User';

    /**
     * @throws ModelNotFoundException
     * @throws DbException
     * @throws DataNotFoundException
     * @throws Exception
     */
    public function onRegister($user)
    {
        $user_id = $user['id'];
        // 注册成功，生成邀请码
        $popularize_code = $this->model->generatePopularizeCode($user_id);
        $this->model->update(['popularize_code' => $popularize_code], ['id' => $user_id]);

        $parent_id = 0;
        $time = time();
        // 注册成功，处理团队逻辑
        if ($user['popularize_code']) {

            // 设置用户上级
            $parent_user_id = $this->model->getUserIdByPopularizeCode($user['popularize_code']);
            $this->model->update([
                'top_time' => $time,
                'top_user_id' => $parent_user_id
            ], ['id' => $user_id]);

            // 更新团队树
            $parent_id = UserTeamModel::where('user_id', $parent_user_id)->value('id');
        }
        $data = ['user_id' => $user_id];
        $data['create_time'] = $time;
        $data['update_time'] = $time;
        $nestedObj = new NestedSets(new UserTeamModel());
        $nestedObj->insert($parent_id, $data);
    }
    public function onLogin($user)
    {
        // 登录成功，更新
        // UserLogin事件响应处理
    }
}
