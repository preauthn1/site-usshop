<?php

namespace app\model\system;

use app\base\BaseModel;
use app\libs\Aes;
use Firebase\JWT\JWT;
use think\facade\Config;
use think\model\concern\SoftDelete;

/**
 * 模型-权限用户
 * Class AuthUserModel
 * @package app\model
 */
class AuthUserModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'auth_user';

    /**
     * 彻底删除
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delComplete($id)
    {
        $user = self::onlyTrashed()->find($id);
        AuthGroupAccessModel::destroy(['user_id' => $id], true);
        return $user->force()->delete();
    }

    /**
     * 模型关联-角色中间表
     * @return \think\model\relation\HasOne
     */
    public function roleAccess()
    {
        return $this->hasOne(AuthGroupAccessModel::class, 'user_id', 'id')->bind(['group_id']);
    }

    /**
     * 获取分页列表
     * @param $group_id
     * @param $status
     * @param $keyword
     * @param $current_page
     * @param $list_rows
     * @return array
     */
    public function getPageData($group_id, $status, $keyword, $current_page, $list_rows)
    {
        $map = [];
        $admin_name = Config::get('system.administrator');
        if (!empty($admin_name)) {
            array_push($map, ['a.username', '<>', $admin_name]);
        }
        if (!empty($group_id)) {
            array_push($map, ['b.group_id', '=', $group_id]);
        }
        if ($status != null) {
            array_push($map, ['a.status', '=', $status]);
        }
        if (!empty($keyword)) {
            array_push($map, ['a.realname', 'like', '%' . trim($keyword) . '%']);
        }
        $dataList = $this
            ->field('a.id,a.username,a.realname,a.avatar,a.status,a.create_time,c.title as group_title,t.name as bureau_name')
            ->alias('a')
            ->leftJoin('t_auth_group_access b', 'b.user_id=a.id')
            ->leftJoin('t_auth_group c', 'c.id=b.group_id')
            ->leftJoin('t_tourism_bureau t', 't.id=a.bureau_id')
            ->where($map)
            ->order('a.create_time', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $ret = ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
        return $ret;
    }

    /**
     * 设置用户登录存储信息
     * @param $data
     * @return mixed
     */
    public function setUserLoginStorage($data)
    {
        $flag = $this->redis->hSet($this->redis_key, $data['id'], serialize($data));
        $this->redis->expire($this->redis_key, $data['expires_in']);
        return $flag;
    }

    /**
     * 获取用户登录存储信息
     * @param $user_id
     * @return mixed
     */
    public function getUserLoginStorage($user_id)
    {
        $data = $this->redis->hGet($this->redis_key, $user_id);
        if ($data) {
            $data = unserialize($data);
        }
        return $data;
    }

    /**
     * 入口
     * @param $param
     * @return array
     */
    public function login($param)
    {
        try {
            $aes = new Aes();
            $param['password'] = $aes->webDecrypt($param['password']);
            $user = $this
                ->with(['roleAccess'])
                ->field('id,username,password,status,bureau_id')
                ->where('username', '=', $param['username'])
                ->find();
            if (empty($user)) {
                return ['code' => 2006];
            } else if (!password_verify($param['password'], $user['password'])) {
                return ['code' => 2007];
            } else {
                $admin_name = Config::get('system.administrator');
                if ($admin_name != $user['username']) {
                    if ($user['status'] == 0) {
                        return ['code' => 2008];
                    }
                    if (!empty($user['group_id'])) {
                        $db_auth_group = new AuthGroupModel();
                        $role = $db_auth_group->getInfoByRedis($user['group_id']);
                        if ($role['status'] == 0) {
                            return ['code' => 2009];
                        }
                    } else {
                        return ['code' => 2009];
                    }
                }
                $token_set = Config::get('system.token_set');
                $jwtKey = Config::get('system.jwt_key');
                $access_token = JWT::encode(['user_id' => $user['id'], 'create_time' => time(), 'expires_in' => $token_set['admin']['access_expires'], 'type' => 'access'], $jwtKey);
                $refresh_token = JWT::encode(['user_id' => $user['id'], 'create_time' => time(), 'expires_in' => $token_set['admin']['refresh_expires'], 'type' => 'refresh'], $jwtKey);
                $this->setUserLoginStorage([
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'realname' => $user['realname'],
                    'status' => $user['status'],
                    'group_id' => $user['group_id'],
                    'bureau_id' => $user['bureau_id'],
                    'expires_in' => $token_set['admin']['refresh_expires'],
                    'access_token' => $access_token,
                    'refresh_token' => $refresh_token
                ]);
                $aes_web = Config::get('system.aes');
                $key_iv_str = $aes_web['key'] . "_" . $aes_web['iv'] . "_" . time();
                $aes_tool = new Aes();
                $key_iv = $aes_tool->webEncrypt($key_iv_str);
                return ['code' => 1, 'data' => [
                    'key_iv' => $key_iv,
                    'access_token' => $access_token,
                    'group_id' => $user['group_id'],
                    'bureau_id' => $user['bureau_id'],
                    'refresh_token' => $refresh_token
                ]];
            }
        } catch (\Exception $e) {
            return ['code' => 0, 'msg' => $e->getMessage()];
        }
    }

    /**
     * 获取管理员名称
     * @param $id
     * @return array|string[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getAdminName($id)
    {
        $temp = self::where('id', $id)->field('username,realname')->find();
        if (empty($temp)) {
            return [
                'username' => '未知',
                'realname' => '未知'
            ];
        } else {
            return [
                'username' => $temp['username'],
                'realname' => $temp['realname']
            ];
        }
    }
}
