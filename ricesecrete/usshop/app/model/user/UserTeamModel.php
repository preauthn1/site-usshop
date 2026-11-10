<?php

namespace app\model\user;

use app\base\BaseModel;
use app\libs\NestedSets;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 模型-团队
 * Class UserModel
 * @package app\model
 */
class UserTeamModel extends BaseModel
{
    protected $name = 'user_team';

    /**
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     */
    public function getTeamUsers($user_id, $level = 2)
    {
        $team_user = $this->where('user_id', $user_id)->find();
        $nestedObj = new NestedSets($this);
        $field = ['id', 'user_id', 'activated', 'level'];
        return $nestedObj->getBranch($team_user->id, $field, $level);
    }
}
