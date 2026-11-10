<?php

namespace app\repository;

use app\model\system\UserBankModel;

class UserBankRepository extends Repository
{
    protected $model;


    public function __construct()
    {
        parent::__construct();
        //绑定模型
        $this->model = new UserBankModel();
    }

}
