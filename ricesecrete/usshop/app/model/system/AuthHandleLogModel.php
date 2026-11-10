<?php

namespace app\model\system;

use app\base\BaseModel;
use app\libs\Aes;
use Firebase\JWT\JWT;
use think\facade\Config;
use think\model\concern\SoftDelete;

/**
 * 模型-管理员操作日志
 * Class AuthHandleLogModel
 * @package app\model
 */
class AuthHandleLogModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'auth_handle_log';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @return array
     */
    public function getPageData($map = [], $current_page = 1, $list_rows = 50)
    {
        $dataList = $this
            ->alias('a')
            ->join('t_auth_user b', 'a.admin_id = b.id')
            ->field('a.*,b.username as admin_name')
            ->where($map)
            ->order('a.create_time', 'desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
    }

    /**
     * 创建操作日志
     * @param string $admin_id
     * @param string $bureau_id
     * @param string $url
     * @param string $param
     * @return AuthHandleLogModel|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addHandleLog($admin_id = '', $bureau_id = '', $url = '', $param = '')
    {
        // `admin_id` int NOT NULL DEFAULT '0' COMMENT '管理员id',
        //  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '操作说明',
        //  `url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作路由',
        //  `param` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '操作参数',
        //  `bureau_id` int DEFAULT '0' COMMENT '旅行社id',
        $auth_rule_model = new AuthRuleModel();
        $name = $auth_rule_model->getParentName($url);
        $in_data = [
            'admin_id' => $admin_id,
            'name' => $name,
            'url' => $url,
            'param' => $param,
            'bureau_id' => $bureau_id,
            'create_time' => time(),
            'update_time' => time(),
        ];
        return self::create($in_data);
    }
}
