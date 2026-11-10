<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 兴趣表
 * Class Ver3
 * @package app\upgrade\version
 */
class Ver4 extends VersionBase
{

    public function run()
    {
        // 兴趣表
        $sql = <<<SQL
CREATE TABLE `t_hobby` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '兴趣名称',
  `src` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '兴趣图片',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态：1启用，2禁用',
  `sort` int unsigned NOT NULL DEFAULT '0' COMMENT '排序，数值越大权重越高',
  `create_time` int NOT NULL DEFAULT '0',
  `update_time` int NOT NULL DEFAULT '0',
  `delete_time` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='兴趣爱好';
SQL;
        $ret = $this->createTableSql("t_hobby", $sql);
        if ($ret) {
            $sql = <<<SQL
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (1, '摄影', 'http://ykzn.oss-cn-shanghai.aliyuncs.com/hobby/2024-08-02/40dc28827c52fd584ba5b0c719275bfd205c5fe0.png', 0, 2, 1722581357, 1723019766, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (2, '户外', 'http://ykzn.oss-cn-shanghai.aliyuncs.com/hobby/2024-08-02/b69aac91f9b54f88faa4c60e44dc1245fd2f5f7e.png', 1, 1, 1722581623, 1722834114, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (3, '其他', '', 1, 0, 0, 0, 1722581751);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (4, '徒步', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (5, '登山', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (6, '露营', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (7, '骑行', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (8, '攀岩', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (9, '潜水', '', 1, 0, 0, 0, 0);
INSERT INTO `t_hobby` (`id`, `title`, `src`, `status`, `sort`, `create_time`, `update_time`, `delete_time`) VALUES (10, '滑雪', '', 1, 0, 0, 0, 0);
SQL;
            $this->execute($sql);
        }
        //用户兴趣表
        $sql = <<<SQL
CREATE TABLE `t_user_hobby` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned NOT NULL,
  `hobby_id` int unsigned NOT NULL,
  `create_time` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='用户兴趣表';
SQL;
        $this->createTableSql("t_user_hobby", $sql);
        $pid = $this->addMenuNode('内容管理', 'content', 'iconfont el-icon-edit-outline', 500);
        if ($pid) {
            $this->addChildNode('兴趣列表', 'hobby', $pid, 1);
        }
    }
}
