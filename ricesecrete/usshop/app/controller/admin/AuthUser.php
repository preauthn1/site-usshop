<?php

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\Aes;
use app\model\system\AuthGroupAccessModel;
use app\model\system\AuthGroupModel;
use app\model\system\AuthRuleModel;
use app\model\system\AuthUserModel;
use app\model\system\LanguageModel;
use app\model\system\SysConfigModel;
use Firebase\JWT\JWT;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Lang;
use think\Request;

/**
 * 权限用户
 * Class AuthUser
 * @package app\controller
 */
class AuthUser extends BaseEncryptController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new AuthUserModel();
    }

    /**
     * 分页列表
     * @param string $group_id
     * @param string $status
     * @param string $keyword
     * @param int $page
     * @param int $limit
     * @return \think\response\Json
     */
    public function getPage($group_id = '', $status = '', $keyword = '', $page = 1, $limit = 10)
    {
        $data = $this->model->getPageData($group_id, $status, $keyword, $page, $limit, 'a.id,a.username,a.realname,a.status,a.create_time,c.title as group_title');
        return self::returnMsg(1, [
            'totalCount' => $data['total'],
            'currentPage' => $data['current'],
            'pageList' => $data['dataList']
        ]);
    }

    /**
     * 单条信息
     * @param $id
     * @return \think\response\Json
     */
    public function getInfo($id)
    {
        $user = $this->model->getInfo($id, 'id,username,realname,status,bureau_id', ['roleAccess']);
        if (!empty($user)) {
            return self::returnMsg(1, $user);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 用户信息
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getProfile()
    {
        $user = $this->model->getInfo($this->currentUser['user_id'], 'id,username,realname,avatar', ['roleAccess']);
        if (!empty($user)) {
            $admin_name = Config::get('system.administrator');
            $is_admin = $admin_name == $user['username'];
            $rules = '';
            if (!$is_admin && !empty($user['group_id'])) {
                $db_auth_group = new AuthGroupModel();
                $role = $db_auth_group->getInfoByRedis($user['group_id']);
                $rules = $role['rules'];
            }
            $db_rule = new AuthRuleModel();
            $lang = strtolower(Lang::getLangSet());
            $key = 'auth_menu_' . $lang . "_" . $user['group_id'];
            $ruleInfo_str = Cache::get($key, '');
            if (empty($ruleInfo_str)) {
                $ruleInfo = $db_rule->getAuthMenu($rules, $is_admin);
                Cache::set($key, serialize($ruleInfo), 3600 * 24);
            } else {
                $ruleInfo = unserialize($ruleInfo_str);
            }
            //获取系统配置
            $sys_config_model = new SysConfigModel();
            $list = $sys_config_model->where([])->field('name,key,value')->select();
            $sys_config = [];
            foreach ($list as $value) {
                $sys_config[$value['key']] = $value['value'];
            }
            $sys_config['langCacheTime'] = Cache::get(LanguageModel::$cacheKeyTempTime, 0);
            return self::returnMsg(1, [
                'realname' => $user['realname'],
                'avatar' => $user['avatar'],
                'menuTree' => $ruleInfo['menuTree'],
                'permission' => $ruleInfo['permission'],
                'sysConfig' => $sys_config,
                'lang_list' => LanguageModel::getCacheList(),
            ]);
        } else {
            return self::returnMsg(1001);
        }
    }

    /**
     * 保存
     * @param Request $request
     * @return \think\response\Json
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onSave(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'username' => $request->param('username'),
            'password' => $request->param('password'),
            'realname' => $request->param('realname'),
            'status' => $request->param('status'),
            'group_id' => $request->param('group_id'),
            'bureau_id' => $request->param('bureau_id'),
            'agent_id' => $request->param('agent_id'),
            'avatar' => $request->param('avatar'),
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\AuthUserValidate.add';
            if (empty($formData['password'])) {
                $default_pwd = Config::get('system.default_pwd');
                $formData['password'] = password_hash($default_pwd, PASSWORD_BCRYPT);
            } else {
                $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT);
            }
        } else {
            $validate = 'app\validate\AuthUserValidate.edit';
            if (!empty($formData['password'])) {
                $formData['password'] = password_hash($formData['password'], PASSWORD_BCRYPT);
            } else {
                unset($formData['password']);
            }
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);
            if (false !== $ret['flag']) {
                if (!empty($formData['group_id'])) {
                    $user_id = $ret['id'];
                    $db_access = new AuthGroupAccessModel();
                    $db_access->where('user_id', $user_id)->delete(true);
                    $db_access->saveData([
                        'user_id' => $user_id,
                        'group_id' => $formData['group_id']
                    ]);
                }
                $this->model->delRedis($ret['id']);
                AuthRuleModel::clearAuthMenuCache();
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
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
            $this->model->delRedis($id);
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }

    /**
     * 保存状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $validate = 'app\validate\AuthUserValidate.upstatus';
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
     * 登录
     * @param Request $request
     * @return \think\response\Json
     */
    public function onLogin(Request $request)
    {
        $formData = [
            'username' => $request->param('username'),
            'password' => $request->param('password'),
            'captcha' => $request->param('captcha')
        ];
        $result = $this->validate($formData, 'app\validate\AuthUserValidate.login');
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $data = $this->model->login($formData);
            return self::returnUnMsg($data);
        }
    }

    /**
     * 退出登录
     * @return \think\response\Json
     */
    public function onLogout()
    {
        $flag = $this->model->delRedis($this->currentUser['user_id']);
        if (false !== $flag) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 修改密码
     * @param $oldpwd
     * @param $newpwd
     * @return \think\response\Json
     */
    public function onEditpwd($oldpwd, $newpwd)
    {
        $aes = new Aes();
        $oldpwd = $aes->decrypt($oldpwd);
        $newpwd = $aes->decrypt($newpwd);
        $user = $this->model->where('id', $this->currentUser['user_id'])->find();
        if (!password_verify($oldpwd, $user['password'])) {
            return self::returnMsg(2007);
        } else {
            $formData = [
                'id' => $this->currentUser['user_id'],
                'password' => password_hash($newpwd, PASSWORD_BCRYPT)
            ];
            $flag = $this->model->saveData($formData);
            if (false !== $flag) {
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }

    /**
     * 刷新token
     * @param $refresh_token
     * @return \think\response\Json
     */
    public function onToken($refresh_token)
    {
        $jwtKey = Config::get('system.jwt_key');
        $refreshToken = (array)JWT::decode($refresh_token, $jwtKey, array('HS256'));
        $storage = $this->model->getUserLoginStorage($refreshToken['user_id']);
        if ($storage['refresh_token'] == $refresh_token) {
            $user_id = $refreshToken['user_id'];
            $token_set = Config::get('system.token_set');
            if ((time() - intval($refreshToken['create_time'])) < $token_set['admin']['refresh_expires']) {
                $access_token = JWT::encode(['user_id' => $user_id, 'create_time' => time(), 'expires_in' => $token_set['admin']['access_expires'], 'type' => 'access'], $jwtKey);
                $refresh_token = JWT::encode(['user_id' => $user_id, 'create_time' => time(), 'expires_in' => $token_set['admin']['refresh_expires'], 'type' => 'refresh'], $jwtKey);
                $storage['access_token'] = $access_token;
                $storage['refresh_token'] = $refresh_token;
                $this->model->setUserLoginStorage($storage);
                return self::returnMsg(1, [
                    'access_token' => $access_token,
                    'refresh_token' => $refresh_token
                ]);
            }
        }
        return self::returnMsg(2005);
    }

    /**
     * 删除用户缓存
     * @param string $id
     * @return \think\response\Json
     */
    public function onDelCache($id = '')
    {
        if (!empty($id)) {
            $flag = $this->model->delRedis($id);
        } else {
            $flag = $this->model->redis->del($this->model->redis_key);
        }
        if (false !== $flag) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }
}
