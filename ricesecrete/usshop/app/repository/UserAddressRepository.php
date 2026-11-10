<?php

namespace app\repository;

use app\model\user\UserAddressModel;

class UserAddressRepository extends Repository
{
    protected $model;


    public function __construct()
    {
        parent::__construct();
        //绑定模型
        $this->model = new UserAddressModel();
    }

}
