<?php

use think\facade\Route;

//【通知】支付宝支付回调
Route::rule('notify/alipay', 'api.PayNotify/alipay');
//【通知】微信支付回调
Route::rule('notify/wechat', 'api.PayNotify/wechat');
//【通知】微信支付回调
Route::rule('notify/recharge/:ident', 'api.PayNotify/rechargeNotify');
