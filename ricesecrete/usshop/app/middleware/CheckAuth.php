<?php
namespace app\middleware;

use app\base\BaseRequest;

/**
 * 中间件-权限检测
 * Class CheckAuth
 * @package app\middleware
 */
class CheckAuth
{
    /**
     * 接管处理
     * @param BaseRequest $request
     * @param \Closure $next
     * @return mixed
     * @throws \think\Exception
     * @throws \throwable
     */
    public function handle(BaseRequest $request, \Closure $next)
    {
        $currentUser = $request->currentUser;
        if(empty($currentUser)){
            throw new \think\Exception('',2011);
        }
        return $next($request);
    }
}
