<?php
namespace app\base;

use app\libs\api\ApiResponse;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\facade\Env;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 * Class ExceptionHandle
 * @package app\base
 */
class BaseExceptionHandle extends Handle
{
    use ApiResponse;

    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {


        // 添加自定义异常处理机制
        if (Env::get('APP_DEBUG')){
            // 添加自定义异常处理机制
            $code = !empty($e->getCode()) ? $e->getCode() : 500;
            $msg = $e->getMessage();
            if ($msg) {
                $msg .= ' in ' . $e->getFile(). ' on line '. $e->getLine();
            }

            // 参数异常
            if ($e instanceof \InvalidArgumentException) {
                $code = 991;
                $msg = $e->getMessage();
            }
            // 验证异常
            if ($e instanceof ValidateException) {
                $code = 992;
                $msg = $e->getError();
            }
            // Http异常
            if ($e instanceof HttpException) {
                $code = $e->getStatusCode();
                if($code==404){
                    $msg = $e->getMessage();
                }
            }
            return self::returnMsg($code, [], $msg);
        }
        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
