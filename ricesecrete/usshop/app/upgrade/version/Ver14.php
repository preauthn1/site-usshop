<?php

namespace app\upgrade\version;

use app\upgrade\VersionBase;

/**
 * 商品多语言处理
 * Class Ver14
 * @package app\upgrade\version
 */
class Ver14 extends VersionBase
{

    public function run()
    {
        //商品翻译拓展表
        $sql = <<<SQL
CREATE TABLE `t_goods_lang` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
  `lang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '语言类型',
  `goods_id` int DEFAULT '0' COMMENT '商品id',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
  `photo` text COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
  `details` text COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='商品名称多语言表';
SQL;
        $this->createTableSql("t_goods_lang", $sql);
    }
}
