<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * Banner 添加
 * Class Ver1
 * @package app\upgrade\version
 */
class Ver1 extends VersionBase
{

    public function run()
    {
        //创键建管理员表
        $sql = <<<SQL
CREATE TABLE `t_sys_banner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `src` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图片地址',
  `jump_type` tinyint unsigned NOT NULL DEFAULT '0' COMMENT '跳转类型，0无跳转，1商品页面，2外部链接 3banner详情 4内部链接',
  `jump_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '跳转地址',
  `detail` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '图片详情',
  `goods_type` tinyint(1) DEFAULT '0' COMMENT '商品类型 0正常商品 1旅游套餐 3积分商品 ',
  `goods_id` int DEFAULT '0' COMMENT '商品id',
  `area` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'index' COMMENT '显示区域 index 首页 start app启动页 travel_id旅游分类 ',
  `sort` int unsigned DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：2下线，1上线',
  `start_time` int DEFAULT '0' COMMENT '生效时间',
  `end_time` int DEFAULT '0' COMMENT '失效时间',
  `delete_time` int unsigned DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='banner表';
SQL;
        $this->createTableSql("t_sys_banner", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('Banner管理', 'banner', $pid, 1);
        }
    }
}
