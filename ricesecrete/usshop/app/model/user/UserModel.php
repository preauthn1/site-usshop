<?php

namespace app\model\user;

use app\base\BaseModel;
use app\libs\Aes;
use app\libs\Util;
use app\model\order\OrderGoodsModel;
use app\model\system\SysNotificationModel;
use Exception;
use Firebase\JWT\JWT;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Config;
use think\Model;
use think\model\relation\BelongsTo;
use think\model\relation\HasMany;

/**
 * 模型-用户
 * Class UserModel
 * @package app\model
 */
class UserModel extends BaseModel
{

    protected $name = 'user';

    public function topUser(): BelongsTo
    {
        return $this->belongsTo(self::class, 'top_user_id', 'id');
    }

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('username|phone|nick_name', '%' . trim($keyword) . '%');
        }
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param $order
     * @param string $field
     * @return array
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->withSearch(['team'], [
                'team' => 'id'
            ])
            ->with('top_user')
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 用户登录
     * @param $param
     * @param bool $is_oauth
     * @param string $oauth_type
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function login($param, bool $is_oauth = false, string $oauth_type = ''): array
    {
        if (!$is_oauth) {
            $user_info = $this->where($param['type'], $param[$param['type']])->find();
        } else {
            switch ($oauth_type) {
                case 'weixin':
                    $user_info = $this->where('wx_open_id', $param['wx_open_id'])->find();
                    break;
                case 'qq':
                    $user_info = $this->where('qq_open_id', $param['qq_open_id'])->find();
                    break;
                case 'alipay':
                    $user_info = $this->where('alipay_open_id', $param['alipay_open_id'])->find();
                    break;
                case 'phone':
                    $user_info = $this->where('phone', $param['phone'])->find();
                    if (empty($user_info)) {
                        $this->saveData([
                            'phone' => $param['phone'],
                        ]);
                        $user_info = $this->where('phone', $param['phone'])->find();
                    }
                    break;
                default:
                    $user_info = [];
            }
        }
        if (empty($user_info)) {
            return ['code' => 2010];
        }
        if (isset($param['password'])) {
            if (!$is_oauth && !password_verify($param['password'], $user_info['password'])) {
                return ['code' => 2011];
            }
        }
        // 更新用户设备信息
        if (isset($param['cid'])) {
            $this->update(['cid' => $param['cid']], ['id' => $user_info['id']]);
        }
        $jwtKey = Config::get('system.jwt_key');
        $token_set = Config::get('system.token_set');
        $access_token = JWT::encode([
            'user_id' => $user_info['id'], 'create_time' => time(),
            'expires_in' => $token_set['index']['access_expires'], 'type' => 'access'
        ], $jwtKey);
        $refresh_token = JWT::encode([
            'user_id' => $user_info['id'], 'create_time' => time(),
            'expires_in' => $token_set['index']['refresh_expires'], 'type' => 'refresh'
        ], $jwtKey);
        $setInfo['id'] = $user_info['id'];
        $setInfo['phone'] = $user_info['phone'];
        $setInfo['email'] = $user_info['email'];
        $setInfo['vip_level'] = $user_info['vip_level'];
        $setInfo['popularize_code'] = $user_info['popularize_code'];
        $setInfo['team_level'] = $user_info['team_level'];
        $setInfo['commission'] = $user_info['commission'];
        $setInfo['access_token'] = $access_token;
        $setInfo['refresh_token'] = $refresh_token;
        $setInfo['expires_in'] = $token_set['index']['refresh_expires'];
        $aes_web = Config::get('system.aes');
        $key_iv_str = $aes_web['key'] . "_" . $aes_web['iv'] . "_" . time();
        $aes_tool = new Aes();
        $key_iv = $aes_tool->webEncrypt($key_iv_str);
        $this->setUserLoginStorage($setInfo);
        return [
            'code' => 1, "data" => [
                "token" => $access_token,
                "refresh_token" => $refresh_token,
                "key_iv" => $key_iv,
                "expires_time" => $token_set['index']['access_expires']
            ]
        ];
    }


    /**
     * 获取刷新toekn
     * @param string $refresh_token
     * @return array|int[]
     */
    public function getToken($refresh_token = '')
    {
        if (empty($refresh_token)) {
            return ['code' => 2007];
        }
        $jwt_key = Config::get('system.jwt_key');
        $tokenInfo = (array)JWT::decode($refresh_token, $jwt_key, array('HS256'));
        if (empty($tokenInfo)) {
            return ['code' => 2007];
        } else {
            $storage = $this->getUserLoginStorage($tokenInfo['user_id']);
            if (empty($storage)) {
                return ['code' => 2007];
            }
            if ($storage['refresh_token'] == $refresh_token) {
                $user_id = $tokenInfo['user_id'];
                $token_set = Config::get('system.token_set');
                if ((time() - intval($tokenInfo['create_time'])) < $token_set['admin']['refresh_expires']) {
                    $access_token = JWT::encode([
                        'user_id' => $user_id, 'create_time' => time(),
                        'expires_in' => $token_set['admin']['access_expires'],
                        'type' => 'access'
                    ], $jwt_key);
                    $refresh_token = JWT::encode([
                        'user_id' => $user_id, 'create_time' => time(),
                        'expires_in' => $token_set['admin']['refresh_expires'],
                        'type' => 'refresh'
                    ], $jwt_key);
                    $storage['access_token'] = $access_token;
                    $storage['refresh_token'] = $refresh_token;
                    $storage['expires_in'] = $token_set['index']['refresh_expires'];
                    $this->setUserLoginStorage($storage);
                    $aes_web = Config::get('system.aes');
                    $key_iv_str = $aes_web['key'] . "_" . $aes_web['iv'] . "_" . time();
                    $aes_tool = new Aes();
                    $key_iv = $aes_tool->webEncrypt($key_iv_str);
                    return [
                        'code' => 1,
                        'data' => [
                            'token' => $access_token,
                            'refresh_token' => $refresh_token,
                            'key_iv' => $key_iv,
                            "expires_time" => $token_set['index']['access_expires']
                        ]
                    ];
                }
            }
        }
        return ['code' => 2007];
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
     * 获取用户状态
     * @param $user_id
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getStatus($user_id)
    {
        $user_info = $this->where('id', $user_id)->field('status')->find();
        return $user_info['status'];
    }

    /**
     * 获取用户昵称和头像
     * @param $user_id
     * @return UserModel|array|mixed|string[]|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getNameHead($user_id)
    {
        $user_info = self::where('id', $user_id)->field('nick_name,head_img')->find();
        if (empty($user_info)) {
            return ['nick_name' => '', 'head_img' => ''];
        }
        return $user_info;
    }

    /**
     * 获取用户手机号
     * @param $user_id
     * @return UserModel|array|mixed|string[]|Model
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getPhone($user_id)
    {
        $phone = self::where('id', $user_id)->value('phone');
        if (empty($phone)) {
            return '';
        }
        return $phone;
    }

    /**
     * 保存数据
     * @param $data
     * @return array
     * @throws Exception
     */
    public function saveData($data): array
    {
        if (!empty($data['id'])) {
            $flag = $this->update($data, ['id' => $data['id']]);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $data['password'] = password_hash($data['password'] ?? Util::getRandomString(20), PASSWORD_DEFAULT);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $flag = $this->strict(false)->insertGetId($data);
            $id = $flag;
            // 注册成功后事件
            if (!isset($data['popularize_code'])) {
                $data['popularize_code'] = 0;
            }
            event('UserRegister', ['id' => $id, 'popularize_code' => $data['popularize_code']]);
        }
        return ['flag' => $flag, 'id' => $id];
    }


    /**
     * 生成邀请码
     * @param $user_id
     * @return string
     */
    public function generatePopularizeCode($user_id): string
    {
        $len = 6 - strlen($user_id);
        return Util::getRandomString($len, '0123456789') . $user_id;
    }

    /**
     * 通过邀请码查询用户ID
     * @param $popularize_code
     * @return mixed
     */
    public function getUserIdByPopularizeCode($popularize_code)
    {
        return UserModel::where('popularize_code', $popularize_code)->value('id');
    }

    public function address(): HasMany
    {
        return $this->hasMany(UserAddressModel::class, 'user_id', 'id');
    }

    public function notification(): HasMany
    {
        return $this->hasMany(SysNotificationModel::class, 'user_id', 'id');
    }

    public static function getUserInfo($id, $field)
    {
        $temp = self::where('id', $id)->field($field)->find();
        if (empty($temp)) {
            $field_arr = explode(',', $field);
            $temp_arr = [];
            foreach ($field_arr as $v) {
                $temp_arr[$v] = "";
            }
            return $temp_arr;
        }
        return $temp;
    }
}
