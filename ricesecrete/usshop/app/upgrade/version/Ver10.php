<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 用户搜索推荐收藏
 * Class Ver10
 * @package app\upgrade\version
 */
class Ver10 extends VersionBase
{
    public function run()
    {
        //用户搜索记录
        $sql = <<<SQL
CREATE TABLE `t_user_search` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `keyword` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键词',
  `type` tinyint(1) DEFAULT '1' COMMENT '类型 1旅游套餐 2正常商品 3积分商品',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态：1启用 2禁用',
  `sort` int DEFAULT '0' COMMENT '排序',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '搜索时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户搜索记录';
SQL;
        $this->createTableSql("t_user_search", $sql);

        //用户收藏表
        $sql = <<<SQL
CREATE TABLE `t_user_collect` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '产品类型 1旅游套餐 2正常商品 3积分商品',
  `product_id` int DEFAULT '0' COMMENT '产品id',
  `delete_time` int DEFAULT '0',
  `update_time` int DEFAULT '0' COMMENT '更新时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户收藏表';
SQL;
        $this->createTableSql("t_user_collect", $sql);

        //用户浏览记录表
        $sql = <<<SQL
CREATE TABLE `t_user_view` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '产品类型 1旅游套餐 2正常商品 3积分商品',
  `product_id` int DEFAULT '0' COMMENT '产品id',
  `delete_time` int DEFAULT '0',
  `update_time` int DEFAULT '0' COMMENT '更新时间',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户浏览记录表';
SQL;
        $this->createTableSql("t_user_view", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 900);
        if ($pid) {
            $this->addChildNode('热门搜索管理', 'userSearch', $pid, 1);
        }
    }
}
