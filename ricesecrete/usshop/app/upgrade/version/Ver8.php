<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 客服
 * Class Ver8
 * @package app\upgrade\version
 */
class Ver8 extends VersionBase
{

    public function run()
    {
        //客服表
        $sql = <<<SQL
CREATE TABLE `t_customer_service` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint unsigned NOT NULL COMMENT '1-电话；2-企业微信客服',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '内容',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用，2禁用',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `create_time` int unsigned NOT NULL DEFAULT '0',
  `update_time` int unsigned NOT NULL DEFAULT '0',
  `delete_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='客服表';
SQL;
        $this->createTableSql("t_customer_service", $sql);
        $pid = $this->addMenuNode('系统管理', 'system', 'iconfont icon-xitongshezhi', 900);
        if ($pid) {
            $this->addChildNode('客服管理', 'customerService', $pid, 1);
        }

    }
}
