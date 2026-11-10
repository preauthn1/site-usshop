<?php
declare (strict_types=1);

namespace app\base;

use app\libs\api\ApiResponse;
use think\exception\ValidateException;
use think\Validate;
use think\App;

/**
 * 控制器基础类
 * Class BaseController
 * @package app\base
 */
abstract class BaseController
{
    use ApiResponse;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 当前用户信息
     * user_id：用户id
     * user_type：用户类型 0后台管理员;1客户
     * @var
     */
    protected $currentUser;

    /**
     * 客户端类型
     */
    protected $clientType;

    /**
     * 是否小程序端
     * @var bool
     */
    protected $isWeapp;

    /**
     * 模型
     */
    protected $model;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;
        $this->clientType = $this->request->header('CLIENT-TYPE');
        $this->isWeapp = ($this->clientType == 'AppIndex') ? true : false;
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
        $this->currentUser = $this->request->currentUser;
    }

    /**
     * 验证数据
     * @access protected
     * @param array $data 数据
     * @param string|array $validate 验证器名或者验证规则数组
     * @param array $message 提示信息
     * @param bool $batch 是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }
        $v->message($message);
        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }
        return $v->failException(true)->check($data);
    }
}
