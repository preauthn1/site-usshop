<?php

use think\facade\Env;

return [
    'api_key'    => Env::get('amadeus.api_key', ''),
    'api_secret' => Env::get('amadeus.api_secret', '')
];
