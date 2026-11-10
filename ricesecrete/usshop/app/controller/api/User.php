<?php

namespace app\controller\api;

use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use app\base\BaseIndexEncryptController;
use app\base\BaseModel;
use app\libs\Alipay\Alipay;
use app\libs\AliyunSms;
use app\libs\WorkWeixin;
use app\model\money\MoneyLogModel;
use app\model\order\OrderGoodsModel;
use app\model\system\CurrencyModel;
use app\model\system\SysConfigModel;
use app\model\user\ThirdAuthModel;
use app\model\user\UserCollectModel;
use app\model\user\UserHobbyModel;
use app\model\user\UserModel;
use app\model\user\UserViewModel;
use app\transform\api\UserTransform;
use app\transform\ThirdAuthTransform;
use app\validate\ChangePasswordValidate;
use app\validate\UserValidate;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use liliuwei\social\GetInfo;
use liliuwei\social\Oauth;
use Psr\SimpleCache\InvalidArgumentException;
use think\App;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Log;
use think\Request;
use think\response\Json;


/**
 * 用户接口
 * Class User
 * @package app\controller
 */
class User extends BaseIndexEncryptController
{
    protected $model;

    protected function initialize()
    {
        parent::initialize();
    }

    public function __construct(App $app, UserModel $model)
    {
        parent::__construct($app);
        $this->model = $model;
    }

    /**
     * 用户登录
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function onLogin(Request $request): Json
    {
        $param['type'] = $request->param('type', 'email');
        $param['email'] = $request->param('email');
        $param['phone'] = $request->param('phone');
        $param['password'] = $request->param('password');
        $param['cid'] = $request->param('cid', '');

        if ($param['type'] == 'email') {
            $validate = UserValidate::class . '.loginEmail';
        } else {
            $validate = UserValidate::class . '.loginPhone';
        }
        $this->validate($param, $validate);

        $user_model = new UserModel();
        $out_data = $user_model->login($param);
        return self::returnUnMsg($out_data);
    }

    /**
     * 使用refresh_token获取新token
     * @param Request $request
     * @return Json
     */
    public function onToken(Request $request): Json
    {
        $refresh_token = $request->param('refresh_token');
        $user_model = new UserModel();
        $out_data = $user_model->getToken($refresh_token);
        return self::returnUnMsg($out_data);
    }

    /**
     * 获取用户信息
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function getUserInfo(Request $request): Json
    {
        $user_id = $this->currentUser['user_id'];
        $field = [
            'id',
            'username',
            'phone',
            'email',
            'nick_name',
            'head_img',
//            'vip_level',
            'gender',
            'birthday',
            'province',
            'city',
            'commission',
            'balance',
//            'integral',
//            'wx_open_id',
//            'qq_open_id',
//            'alipay_open_id',
            'is_agent',
            'language',
            'currency'  // Added new fields
        ];

        $user_data = UserModel::field($field)->withCount(['address', 'notification' => function ($query) {
            $query->where('read', BaseModel::STATUS_DISABLE);
        }])
            ->where('id', $user_id)
            ->find();
        $user_data['currency_info'] = CurrencyModel::where('code', $user_data['currency'])->find();

        // 获取下级人数
//        $nestedObj = new NestedSets(new UserTeamModel());
//        $team_id = UserTeamModel::where('user_id', $user_id)->value('id');
//        $level2_user = $nestedObj->getBranch($team_id, ['id', 'user_id', 'activated'], 2);
//        $level_activated_count = $level2_user->where('activated', 1)->count();

        //待付款
        $user_data['order_status_' . OrderGoodsModel::STATUS_PAYMENT] = OrderGoodsModel::where('user_id', $user_id)
            ->where('status', OrderGoodsModel::STATUS_PAYMENT)
            ->count();
        //待发货
        $user_data['order_status_' . OrderGoodsModel::STATUS_PAID] = OrderGoodsModel::where('user_id', $user_id)
            ->where('status', OrderGoodsModel::STATUS_PAID)
            ->count();
        //待签收
        $user_data['order_status_' . OrderGoodsModel::STATUS_SEND] = OrderGoodsModel::where('user_id', $user_id)
            ->where('status', OrderGoodsModel::STATUS_SEND)
            ->count();

//        //待评价
//        $user_data['user_order_status' . OrderGoodsModel::STATUS_VERIFY] = OrderGoodsModel::where('user_id', $user_id)
//            ->where('status', OrderGoodsModel::STATUS_VERIFY)
//            ->count();
//        // 退货
//        $user_data['user_order_status' . OrderGoodsModel::STATUS_REFUNDING] = OrderGoodsModel::where('user_id', $user_id)
//            ->whereIn('status', [OrderGoodsModel::STATUS_REFUNDING, OrderGoodsModel::STATUS_REFUND, OrderGoodsModel::STATUS_REFUSE])
//            ->count();

//        $user_data['team_activated_count'] = $level_activated_count;
        $user_data['consumption_amount'] = 0; // FIXME 当前消费值

        $user_data['hobby'] = $this->getUserHobbyStr($user_id);
        $user_data = (new UserTransform())->transform($user_data);
        return self::returnMsg(1, $user_data);
    }

    /**
     * 获取用户选择的兴趣
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     */
    private function getUserHobbyStr($user_id): string
    {
        $user_hobby = UserHobbyModel::with([
            'hobby' => function ($query) {
                $query->field(['id', 'title']);
            }
        ])->where('user_id', $user_id)
            ->field(['id', 'user_id', 'hobby_id'])
            ->order('hobby_id')
            ->select();

        $hobby = collect($user_hobby->column('hobby'))->column('title');
        return implode(' ', $hobby);
    }

    /**
     * 注册
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function onRegister(Request $request): Json
    {
        $formData = [
            'phone' => $request->param('phone', ''),
            'email' => $request->param('email', ''),
            'type' => $request->param('type', 'email'),
            'code' => $request->param('code'),
            'password' => $request->param('password'),
            'confirm_password' => $request->param('confirm_password'),
            'popularize_code' => $request->param('popularize_code'),
        ];
        // 判断是否需要邀请码
        if (SysConfigModel::getConfigKeyValue('inviteCodeRequired')) {
            // 判断邀请码是否正确
            if (!$formData['popularize_code']) {
                return self::returnMsg(0, [], '邀请码不能为空');
            }
        }
        if ($formData['type'] == 'email') {
            requestLimit('register_'. $formData['email']);
            $validate = UserValidate::class . '.registerEmail';
            $formData['nick_name'] = explode('@', $formData['email'])[0];
        } else {
            requestLimit('register_'. $formData['phone']);
            $validate = UserValidate::class . '.registerPhone';
        }
        $this->validate($formData, $validate);
        unset($formData['code']);
        unset($formData['confirm_password']);
        $res = $this->model->saveData($formData);

        if (false !== $res['flag']) {
            $out_data = $this->model->login($formData);
            return self::returnUnMsg($out_data);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 发送验证码
     * @return Json
     * @throws ClientException
     * @throws InvalidArgumentException
     * @throws ServerException
     */
    public function sendCode(): Json
    {
        $phone_number = $this->request->param('phone');
        // 验证手机号格式
        if (!preg_match('/^1[23456789]\d{9}$/', $phone_number)) {
            return self::returnMsg(0, [], '手机号格式错误');
        }
        $code = rand(100000, 999999);

        $res = AliyunSms::sendSms($phone_number, $code);
        if ($res['code'] === 1) {
            return self::returnUnMsg(1, '', '验证码已发送，请注意查收');
        }
        return self::returnUnMsg(0, [], $res['msg']);
    }

    /**
     * 手机号一键登录
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function onPhoneLogin(): Json
    {
        $param = [
            'access_token' => $this->request->param('access_token'),
            'openid' => $this->request->param('openid'),
        ];

        $validate = UserValidate::class . '.uniPhoneLogin';
        $this->validate($param, $validate);

        $query_string = '';
        foreach ($param as $k => $v) {
            $query_string .= $k . '=' . $v . '&';
        }
        $query_string = rtrim($query_string, '&');

        $config = Config::get('system.uniapp');
        $secret = $config['getPhoneNumberSecret'];

        $sign = hash_hmac('sha256', $query_string, $secret);
        $url = $config['getPhoneNumberUrl'] . "?sign=" . $sign . "&" . $query_string;

        $client = new Client();

        try {
            $response = $client->request('GET', $url);
        } catch (GuzzleException | Exception $e) {
            Log::channel('uni')->error("获取手机号失败，原因：" . $e->getMessage());
            return self::returnMsg(0, [], '获取手机号失败');
        }
        $content = $response->getBody()->getContents();
        Log::channel('uni')->error("获取数据：" . $content);
        // {"code":0,"errCode":0,"errMsg":"","success":true,"phoneNumber":"19199855451"}
        //        {"success":false,"error":{"code":"FunctionBizError","message":"非法访问"}}
        $res = json_decode($content, true);

        if ($res['success']) {
            $out_data = $this->model->login(['phone' => $res['phoneNumber']], true, 'phone');
            return self::returnUnMsg($out_data);
        } else {
            return self::returnMsg(0, [], '获取手机号失败');
        }
    }


    /**
     * 更新用户信息
     * @param Request $request
     * @return Json
     * @throws Exception
     */
    public function updateUserInfo(Request $request): Json
    {
        $allow_filed = ['nick_name', 'gender', 'head_img', 'birthday', 'province', 'city', 'language', 'currency'];

        $formData = $request->post();
        if (empty($formData)) {
            return self::returnMsg(0, [], '参数不能为空');
        }

        $diff_filed = array_diff(array_keys($request->post()), $allow_filed);
        if (count($diff_filed) > 0) {
            return self::returnMsg(0, [], '非法的字段' . implode(',', $diff_filed));
        }

        $validate = UserValidate::class . '.updateInfo';
        $this->validate($formData, $validate);

        $formData['id'] = $this->currentUser['user_id'];
        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
            return self::returnMsg(1);
        } else {
            return self::returnMsg(0);
        }
    }

    /**
     * 三方登录
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function onSocialLogin(): Json
    {
        // QQ access_token
        // 微信 code
        // 支付宝 code

        $code = $this->request->param('code');
        $type = $this->request->param('type');
        $cid = $this->request->param('cid', '');
        $access_token = $this->request->param('access_token');

        if (!$type) {
            return self::returnMsg(0, '', '参数错误');
        }
        if ($type === 'weixin' || $type === 'alipay') {
            if (!$code) {
                return self::returnMsg(0, '', '参数错误');
            }
        } else {
            if ($type === 'qq') {
                if (!$access_token) {
                    return self::returnMsg(0, '', '参数错误');
                }
            }
        }

        $open_id_field = 'id';
        $open_id_value = '';
        if ($type === 'weixin' || $type === 'qq') {
            // 获取对象实例
            $token = null;
            if ($type === 'qq') {
                $token = ['access_token' => $access_token];
            }
            $sns = Oauth::getInstance($type, $token);

            if ($type === 'weixin') {
                // 获取TOKEN
                try {
                    $token = $sns->getAccessToken($code);
                } catch (Exception $e) {
                    if ($e->getMessage() === 'Undefined index: access_token') {
                        return self::returnMsg(0, '', '授权失败，请重新授权');
                    }
                    return self::returnMsg(0, '', '参数错误');
                }
            }

            //获取当前第三方登录用户信息
            if (is_array($token)) {
                $user_info = GetInfo::getInstance($type, $token);
                $open_id_value = $open_id = $sns->openid();
                $formData = [
                    'nick_name' => $user_info['nickname'],
                    'head_img' => $user_info['avatar'],
                ];

                switch ($type) {
                    case 'weixin':
                        $formData['wx_open_id'] = $open_id;
                        $formData['gender'] = $user_info['sex'] == '男' ? 1 : 2;
                        $open_id_field = 'wx_open_id';
                        break;
                    case 'qq':
                        $formData['qq_open_id'] = $open_id;
                        $formData['gender'] = $user_info['gender'] == '男' ? 1 : 2;
                        $open_id_field = 'qq_open_id';
                        break;
                    default:
                }
            } else {
                return self::returnMsg(0, [], '获取第三方用户的基本信息失败');
            }
        } else {
            if ($type === 'alipay') {
                $result = (new Alipay())->auth($code);
                $alipay_open_id = $result['open_id'];
                $formData = [
                    'alipay_open_id' => $alipay_open_id,
                ];
                $open_id_field = 'alipay_open_id';
                $open_id_value = $alipay_open_id;
            }
        }
        $exist_user = UserModel::where($open_id_field, $open_id_value)->find();

        // 如果已经登录，绑定，如果未登录，判断是否存在该用户，如果不存在，创建用户
        if ($this->currentUser) {
            // 判断是否绑定过其他账号
            if ($exist_user && $exist_user->id != $this->currentUser['user_id']) {
                return self::returnMsg(0, '', '已绑定其他账号，请解绑后再尝试绑定');
            }
            // 没有绑定，绑定用户
            UserModel::where('id', $this->currentUser['user_id'])->update([$open_id_field => $open_id_value]);
            return self::returnMsg(0, '', '绑定成功');
        } else {
            $formData['cid'] = $cid;
            if (!$exist_user) {
                // 创建一个用户
                $res = $this->model->saveData($formData);
                if (false !== $res['flag']) {
                    $out_data = $this->model->login($formData, true, $type);
                    return self::returnUnMsg($out_data);
                } else {
                    return self::returnMsg(0);
                }
            } else {
                $out_data = $this->model->login($formData, true, $type);
                return self::returnUnMsg($out_data);
            }
        }
    }

    /**
     * 验证验证码是否正确
     * @param Request $request
     * @return Json
     * @throws InvalidArgumentException
     */
    public function checkCode(Request $request): Json
    {
        $param['phone'] = $request->param('phone');
        $param['code'] = $request->param('code');

        $validate = UserValidate::class . '.checkCode';
        $this->validate($param, $validate);

        // 重新生成验证码，修改密码的时候需要重新验证
        $code = rand(100000, 999999);
        Cache::store('redis')->set($param['phone'], $code, 600);
        return self::returnMsg(1, ['code' => $code]);
    }

    /**
     * 验证邮箱验证码是否正确
     * @param Request $request
     * @return Json
     * @throws InvalidArgumentException
     */
    public function checkEmailCode(Request $request): Json
    {
        $param['phone'] = $request->param('phone');
        $param['code'] = $request->param('code');

        $validate = UserValidate::class . '.checkCode';
        $this->validate($param, $validate);

        // 重新生成验证码，修改密码的时候需要重新验证
        $code = rand(100000, 999999);
        Cache::store('redis')->set($param['phone'], $code, 600);
        return self::returnMsg(1, ['code' => $code]);
    }

    /**
     * 通过手机验证码修改密码
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function changePasswordByPhone(Request $request): Json
    {
        $param['code'] = $request->param('code');
        $param['phone'] = $request->param('phone');
        $param['password'] = $request->param('password');
        $param['confirm_password'] = $request->param('confirm_password');

        $validate = ChangePasswordValidate::class . '.changePasswordByPhone';
        $this->validate($param, $validate);

        // 修改密码
        $user = UserModel::where('phone', $param['phone'])->find();
        if ($user) {
            $user->password = password_hash($param['password'], PASSWORD_DEFAULT);
            $user->save();
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 通过邮箱验证码修改密码
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function changePasswordByEmail(Request $request): Json
    {
        $param['code'] = $request->param('code');
        $param['email'] = $request->param('email');
        $param['password'] = $request->param('password');
        $param['confirm_password'] = $request->param('confirm_password');

        $validate = ChangePasswordValidate::class . '.changePasswordByEmail';
        $this->validate($param, $validate);

        // 修改密码
        $user = UserModel::where('email', $param['email'])->find();
        if ($user) {
            $user->password = password_hash($param['password'], PASSWORD_DEFAULT);
            $user->save();
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 修改密码
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function changePassword(Request $request): Json
    {
        $param['old_password'] = $request->param('old_password');
        $param['password'] = $request->param('password');
        $param['confirm_password'] = $request->param('confirm_password');

        $validate = ChangePasswordValidate::class . '.changePassword';
        $this->validate($param, $validate);


        $user = $this->model->where('id', $this->currentUser['user_id'])->find();
        if (!password_verify($param['old_password'], $user['password'])) {
            return self::returnMsg(2011);
        }
        // 修改密码
        $user->password = password_hash($param['password'], PASSWORD_DEFAULT);
        $user->save();
        return self::returnMsg(1);
    }

    /**
     * 获取收藏列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getCollectList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', ''); //1旅游套餐 2正常商品 3积分商品
        if (empty($this->currentUser['user_id'])) {
            return self::returnMsg(0, '参数错误');
        } else {
            $map = [
                'a.user_id' => $this->currentUser['user_id']
            ];
            if (!empty($type)) {
                $map[] = ['a.type', '=', $type];
            }
            $data = UserCollectModel::getCollect($map, $page, $limit, $type);
            return self::returnMsg(1, $data);
        }
    }

    /**
     * 收藏/取消收藏套餐
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function onCollectProduct(Request $request)
    {
        $id = $request->param('id', '');
        $type = $request->param('type', '');
        if (empty($id)) {
            return self::returnMsg(0, [], '参数错误');
        }
        if (strpos($id, ',')) {
            $ids = explode(',', $id);
        } else {
            $ids = [$id];
        }
        if (strpos($type, ',')) {
            $types = explode(',', $type);
        } else {
            $types = [$type];
        }
        $out_data = [
            'is_collect' => 0
        ];
        if (isset($this->currentUser['user_id'])) {
            foreach ($ids as $key => $id) {
                if (isset($types[$key])) {
                    $type = $types[$key];
                } else {
                    $type = UserCollectModel::TYPE_TOURISM;
                }
                if (UserCollectModel::isCollect($this->currentUser['user_id'], $id, $type)) {
                    UserCollectModel::delCollect($this->currentUser['user_id'], $id, $type);
                    $out_data = [
                        'is_collect' => 0
                    ];
                } else {
                    UserCollectModel::addCollect($this->currentUser['user_id'], $id, $type);
                    $out_data = [
                        'is_collect' => 1
                    ];
                }
            }
        }
        return self::returnMsg(1, $out_data);
    }

    /**
     * 获取浏览记录列表
     * @param Request $request
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function getViewList(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $type = $request->param('type', ''); //1旅游套餐 2正常商品 3积分商品
        if (empty($this->currentUser['user_id'])) {
            return self::returnMsg(0, '参数错误');
        } else {
            $map = [
                'a.user_id' => $this->currentUser['user_id']
            ];
            if (!empty($type)) {
                $map[] = ['a.type', '=', $type];
            }
            $data = UserViewModel::getView($map, $page, $limit, $type);
            return self::returnMsg(1, $data);
        }
    }

    /**
     * 删除浏览记录
     * @param Request $request
     * @return Json
     */
    public function onDelView(Request $request)
    {
        $id = $request->param('id', '');
        if (isset($this->currentUser['user_id'])) {
            if (strpos($id, ',')) {
                $ids = explode(',', $id);
                foreach ($ids as $v) {
                    UserViewModel::delView($this->currentUser['user_id'], $v, UserViewModel::TYPE_TOURISM);
                }
            } else {
                UserViewModel::delView($this->currentUser['user_id'], $id, UserViewModel::TYPE_TOURISM);
            }
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    // 获取三方登录方式
    public function getThirdAuthType(ThirdAuthTransform $transform): Json
    {
        $is_login = $this->request->get('is_login', '');
        $query = ThirdAuthModel::where('status', 1);
        if ($is_login) {
            $query->where('is_login', 1);
        }
        $third_auth = $query->select();
        $data = $transform->transformCollection($third_auth->toArray());
        return self::returnMsg(1, $data);
    }

    /**
     * 发送邮箱验证码
     * @return Json
     * @throws InvalidArgumentException
     */
    public function sendEmailCode(): Json
    {
        $param['email'] = $this->request->post('email', '');
        $result = filter_var($param['email'], FILTER_SANITIZE_EMAIL);
        if (!filter_var($result, FILTER_VALIDATE_EMAIL)) {
            return self::returnMsg(0, [], '邮箱格式错误');
        }

        $work_weixin = new WorkWeixin();
        $code = rand(100000, 999999);
        Cache::store('redis')->set($param['email'], $code, 600);
        $res = $work_weixin->sendEmail($param['email'], '邮箱验证码', '您的邮箱验证码为：' . $code . '，请勿泄露给他人');
        if ($res) {
            return self::returnMsg(1);
        }
        return self::returnMsg(0);
    }

    /**
     * 绑定手机号
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function bindPhone(Request $request): Json
    {
        $param['phone'] = $request->param('phone');
        $param['code'] = $request->param('code');
        $user_id = $this->currentUser['user_id'];

        $validate = UserValidate::class . '.checkCode';
        $this->validate($param, $validate);

        // 验证通过，绑定手机号
        // 判断手机号是否被绑定
        $exist_user = UserModel::where('phone', $param['phone'])
            ->where('id', '<>', $user_id)
            ->find();
        if ($exist_user) {
            return self::returnMsg(0, [], '手机号已被绑定');
        }
        $this->model->update(['phone' => $param['phone']], ['id' => $user_id]);
        return self::returnMsg(1);
    }

    /**
     * 绑定邮箱
     * @param Request $request
     * @return Json
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function bindEmail(Request $request): Json
    {
        $param['email'] = $request->param('email');
        $param['code'] = $request->param('code');

        $validate = UserValidate::class . '.checkEmailCode';
        $this->validate($param, $validate);

        // 验证通过，绑定邮箱
        // 判断邮箱是否被绑定
        $user_id = $this->currentUser['user_id'];
        $exist_user = UserModel::where('email', $param['email'])
            ->where('id', '<>', $user_id)
            ->find();
        if ($exist_user) {
            return self::returnMsg(0, [], '邮箱已被绑定');
        }
        $this->model->update(['email' => $param['email']], ['id' => $user_id]);
        return self::returnMsg(1);
    }

    /**
     * 获取钱包记录
     * @param Request $request
     * @return Json
     * @throws DbException
     */
    public function getMoneyLog(Request $request)
    {
        $page = $request->param('page', 1);
        $limit = $request->param('limit', 10);
        $balance_type = $request->param('balance_type', MoneyLogModel::BALANCE_TYPE);//1佣金余额 2积分余额 3冻结余额 4账户余额
        $change_type = $request->param('change_type', MoneyLogModel::CHANGE_TYPE_SUB);//-1 减少 1增加
        $user_id = $this->currentUser['user_id'];
        $data = MoneyLogModel::getMoneyLog($user_id, $page, $limit, $balance_type, $change_type);
        return self::returnMsg(1, $data);
    }
}
