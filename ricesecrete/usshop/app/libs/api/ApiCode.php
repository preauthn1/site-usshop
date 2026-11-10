<?php

namespace app\libs\api;

/**
 * API return codes
 * Class ApiCode
 * @package app\libs\api
 */
class ApiCode
{
    static public $code = [
        '1' => 'Operation successful',
        '0' => 'Operation failed',

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
        '2005' => 'Token invalid',
        '2006' => 'User does not exist',
        '2007' => 'Incorrect password',
        '2008' => 'Account does not exist or has been disabled',
        '2009' => 'User group does not exist or has been disabled',
        '2010' => 'Account has been banned',
        '2011' => 'Account has no permission',
        '2012' => 'WeChat authorization failed',

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
    ];
}