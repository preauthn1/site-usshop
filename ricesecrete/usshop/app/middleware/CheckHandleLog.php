<?php

namespace app\middleware;

use app\base\BaseRequest;
use app\model\system\AuthHandleLogModel;

/**
 * 中间件-操作日志
 * Class CheckHandleLog
 * @package app\middleware
 */
class CheckHandleLog
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
        if (empty($currentUser)) {
            throw new \think\Exception('', 2011);
        }
        $requestPath = $request->pathinfo();
        if (strpos($requestPath, 'app_admin/') >= 0) {
            $rulename = str_replace('app_admin/', '', $requestPath);
            if (strpos($rulename, 'save') || strpos($rulename, 'delete')
                || strpos($rulename, 'status') || strpos($rulename, 'delcache')
                || strpos($rulename, 'editpwd') || strpos($rulename, 'reset')) {
                $db_handle_log = new AuthHandleLogModel();
                $param = $request->param();
                if (isset($param['content'])) {
                    if (mb_strlen($param['content']) > 1000) {
                        $param['content'] = mb_substr($param['content'], 0, 1000);
                    }
                }
                $db_handle_log->addHandleLog($currentUser['user_id'], $currentUser['bureau_id'], $rulename, json_encode($param));
            }
        }
        return $next($request);
    }
}
