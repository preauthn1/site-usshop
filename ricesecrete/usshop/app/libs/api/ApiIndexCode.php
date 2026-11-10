<?php

namespace app\libs\api;

/**
 * API return codes
 * Class ApiIndexCode
 * @package app\libs\api
 */
class ApiIndexCode
{
    const SUCCESS = 1;  // Success
    const FAIL = 0;    // Failure

    public static $code = [
        self::SUCCESS => 'Operation successful',
        self::FAIL => 'Operation failed',

        '500' => 'Unknown error',
        '991' => 'Parameter error',
        '992' => 'Exception error',

        '1001' => 'Record not found',
        '1002' => 'Delete operation failed',
        '1003' => 'Add operation failed',
        '1004' => 'Update operation failed',

        '2001' => 'Illegal request source',
        '2002' => 'Invalid Token',
        '2003' => 'Token does not match user',
        '2004' => 'Token expired',
        '2007' => 'Refresh Token expired',

        '2010' => 'User does not exist',
        '2011' => 'Incorrect password',
        '2012' => 'Account does not exist or has been disabled',
        '2013' => 'User group does not exist or has been disabled',
        '2014' => 'Account has been banned',
        '2015' => 'Account has no permission',
        '2016' => 'WeChat authorization failed',

        '3001' => 'Unsupported file type',
        '3002' => 'File verification failed',
        '3003' => 'File upload failed',
        '3004' => 'No file detected',
        '3005' => 'File record not found',
        '3006' => 'File deletion failed',
        '4001' => 'Verification code error or expired',

        '5001' => 'Incorrect verification code',
        '5002' => 'Too many verification attempts, please request a new code',
        '5003' => 'System detected code brushing behavior',
        '5004' => 'Failed to send',
        '5005' => 'Invalid phone number format',

        // Order related
        '6001' => 'Order parameter error',
        '6002' => 'Product does not exist',
        '6003' => 'Product specification does not exist',
        '6004' => 'Order creation failed',
        '6005' => 'Order detail creation failed',
        '6006' => 'Order verification failed',
        '6007' => 'Exceeded package number limit',
        '6008' => 'Order already reviewed',
        '6009' => 'Order already verified',
        '6010' => 'Order already paid',
        '6011' => 'Payment information creation failed',
        '6012' => 'Payment information update failed',
        '6013' => 'Payment information does not exist',
        '6014' => 'Unpaid orders exist',
        '6015' => 'Order information does not exist',
        '6016' => 'Insufficient inventory',
        '6017' => 'You have a customization application in progress, please wait for processing',

        // Recharge related
        '7001' => 'Insufficient balance',

        // Bank card related
        '8001' => 'You can add up to %d bank cards',

        // Points related
        '9001' => 'You have used up your daily points conversion limit, try again tomorrow',
        '9002' => 'Points to commission conversion failed, please try again later',
    ];
}