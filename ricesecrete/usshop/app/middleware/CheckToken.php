<?php

namespace app\middleware;

use app\base\BaseRequest;
use app\model\system\AuthGroupModel;
use app\model\system\AuthRuleModel;
use app\model\system\AuthUserModel;
use app\model\user\UserModel;
use Firebase\JWT\JWT;
use think\facade\Config;

/**
 * 中间件-Token检测
 * Class CheckToken
 * @package app\middleware
 */
class CheckToken
{
    /**
     * 接管处理
     * @param  BaseRequest  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \think\Exception
     * @throws \throwable
     */
    public function handle(BaseRequest $request, \Closure $next)
    {
        $accessToken = $request->header('Authorization');
        if (empty($accessToken)) {
            $accessToken = $request->get('token');
        }
        if ($accessToken && !empty($accessToken)) {
            try {
                $jwt_key = Config::get('system.jwt_key');
                $tokenInfo = (array) JWT::decode($accessToken, $jwt_key, array('HS256'));
            } catch (\Exception $e) {
                throw new \think\Exception('', 2002);
            }
            $clientType = $request->header('Client-Type');
            if (empty($clientType)) {
                $clientType = $request->get('type');
            }
            switch ($clientType) {
                case 'AppAdmin':
                {
                    $requestPath = $request->pathinfo();
                    $currentUser = $this->initAppAdmin($accessToken, $tokenInfo, $requestPath);
                    break;
                }
                case 'AppIndex':
                {
                    $currentUser = $this->initAppIndex($accessToken, $tokenInfo);
                    break;
                }
                default:
                {
                    throw new \think\Exception('', 2001);
                    break;
                }
            }
            $request->currentUser = $currentUser;
        }
        return $next($request);
    }

    /**
     * 初始化-后台
     * @param $accessToken
     * @param $tokenInfo
     * @param $requestPath
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function initAppAdmin($accessToken, $tokenInfo, $requestPath)
    {
        $db_user = new AuthUserModel();
        $userData = $db_user->getUserLoginStorage($tokenInfo['user_id']);
        if (!empty($userData) && $accessToken == $userData['access_token']) {
            $token_set = Config::get('system.token_set');
            if (!in_array($requestPath, $token_set['admin']['white_path'])) {
                if ((time() - intval($tokenInfo['create_time'])) < $token_set['admin']['access_expires']) {
                    $admin_name = Config::get('system.administrator');
                    if ($userData['username'] != $admin_name) {
                        if ($userData['status'] == 0) {
                            throw new \think\Exception('', 2008);
                        }

                        if (!empty($userData['group_id'])) {
                            $db_auth_group = new AuthGroupModel();
                            $role = $db_auth_group->getInfoByRedis($userData['group_id']);
                            if (!empty($role)) {
                                if ($role['status'] == 0) {
                                    throw new \think\Exception('', 2009);
                                }
                                $db_rule = new AuthRuleModel();
                                $rulename = str_replace('app_admin/', '', $requestPath);
                                $rule = $db_rule->getInfoByRedis($rulename, 'url');
                                $ruleIdArr = explode(',', $role['rules']);
                                if (!$rule || !in_array($rule['id'], $ruleIdArr)) {
                                    throw new \think\Exception('', 2011);
                                }
                            } else {
                                throw new \think\Exception('', 2009);
                            }
                        } else {
                            throw new \think\Exception('', 2009);
                        }
                    }
                } else {
                    throw new \think\Exception('', 2004);
                }
            }
            return [
                'user_id'   => $userData['id'],
                'bureau_id' => $userData['bureau_id'],
                'user_type' => 0
            ];
        } else {
            throw new \think\Exception('', 2003);
        }
    }


    /**
     * 初始化-客户端
     * @param $accessToken  -前端token
     * @param $tokenInfo  -解密之后的用户信息
     * @return array
     * @throws \think\Exception
     */
    protected function initAppIndex($accessToken, $tokenInfo)
    {
        $user_model = new UserModel();
        $userData = $user_model->getUserLoginStorage($tokenInfo['user_id']);
        if (!empty($userData) && $accessToken == $userData['access_token']) {
            $status = $user_model->getStatus($userData['id']);
            if ($status != 1) {
                throw new \think\Exception('', 2008);
            }
            return [
                'user_id'         => $userData['id'],
                'phone'           => $userData['phone'],
                'email'           => $userData['email'],
                'vip_level'       => $userData['vip_level'],
                'popularize_code' => $userData['popularize_code'],
                'team_level'      => $userData['team_level'],
                'commission'      => $userData['commission'],
                'user_type'       => 1
            ];
        } else {
            //取消-不然一直提醒未登录
            throw new \think\Exception('', 2003);
        }
    }

}
