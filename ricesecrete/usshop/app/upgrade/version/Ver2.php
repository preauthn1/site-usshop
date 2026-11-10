<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 站内信添加
 * Class Ver2
 * @package app\upgrade\version
 */
class Ver2 extends VersionBase
{

    public function run()
    {
        //站内信
        $sql = <<<SQL
CREATE TABLE `t_sys_notification` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1-个人消息 2-系统消息',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `user_id` int unsigned NOT NULL DEFAULT '0' COMMENT '站内消息用户id',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息详情 （可以是富文本）',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用，2禁用',
  `read` tinyint(1) NOT NULL DEFAULT '2' COMMENT '1已读  2未读',
  `is_popup` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否是弹窗公告 1-是 2-否',
  `bulletin_id` int unsigned NOT NULL DEFAULT '0' COMMENT '公告id',
  `delete_time` int unsigned NOT NULL DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='站内通知';
SQL;
        $this->createTableSql("t_sys_notification", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('站内信管理', 'notification', $pid, 1);
        }

        //站内信
        $sql = <<<SQL
CREATE TABLE `t_sys_bulletin` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '消息详情 （可以是富文本）',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用，2禁用',
  `is_popup` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否是弹窗公告 1-是 2-否',
  `delete_time` int unsigned NOT NULL DEFAULT '0' COMMENT '删除日期',
  `update_time` int unsigned NOT NULL DEFAULT '0' COMMENT '更新日期',
  `create_time` int unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='公告';
SQL;
        $this->createTableSql("t_sys_bulletin", $sql);

        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('公告管理', 'bulletin', $pid, 1);
        }

    }
}
