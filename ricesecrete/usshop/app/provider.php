<?php
use app\base\BaseExceptionHandle;
use app\base\BaseRequest;

return [
    'think\Request'          => BaseRequest::class,
    'think\exception\Handle' => BaseExceptionHandle::class,
];
