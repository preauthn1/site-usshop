<?php

namespace app\model\goods;

use app\base\BaseModel;
use app\model\system\LanguageModel;

/**
 * 适配商品多语言
 * 模型-GoodsLangModel
 * Class GoodsLangModel
 * @package app\model
 */
class GoodsLangModel extends BaseModel
{
    protected $name = 'goods_lang';

    /**
     * 添加商品多语言信息
     * @param $goods
     * @param string $lang
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addGoodsInfo($goods, $lang = '')
    {
        $map = [
            'goods_id' => $goods['id'],
            'lang' => $lang
        ];
        $lang_goods = self::where($map)->find();
        // `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
        //  `lang` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '语言类型',
        //  `goods_id` int DEFAULT '0' COMMENT '商品id',
        //  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
        //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
        //  `photo` text COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
        //  `details` text COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
        if (!empty($lang_goods)) {
            $up_data = [
                'lang' => $lang,
                'title' => $goods['title'],
                'img' => $goods['img'],
                'photo' => $goods['photo'],
                'details' => $goods['details'],
            ];
            $ret = self::where($map)->update($up_data);
            if (empty($ret)) {
                return false;
            }
        } else {
            $add_data = [
                'lang' => $lang,
                'goods_id' => $goods['id'],
                'title' => $goods['title'],
                'img' => $goods['img'],
                'photo' => $goods['photo'],
                'details' => $goods['details'],
            ];
            $ret = self::create($add_data);
            if (empty($ret)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 添加商品多语言信息
     * @param $goods
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addGoodsInfos($goods)
    {
        if (empty($goods['title'])) {
            return $goods;
        }
        $field = ['title', 'img', 'photo', 'details'];
        if (!is_array($goods['title'])) {
            return $goods;
        }
        $langs = LanguageModel::getCacheList();
        $title = "";
        $img = "";
        $photo = "";
        $details = "";
        foreach ($langs as $ke => $lang) {
            if ($ke == 0) {
                $title = $goods['title'][$lang['code']] ?? '';
                $img = $goods['img'][$lang['code']] ?? '';
                $photo = $goods['photo'][$lang['code']] ?? '';
                $details = $goods['details'][$lang['code']] ?? '';
            }
            $goods_arr = [];
            $goods_arr['id'] = $goods['id'];
            foreach ($field as $v) {
                if (empty($goods_arr[$v][$lang['code']])) {
                    $value = '';
                    if ($v == 'title') {
                        $value = $title;
                    }
                    if ($v == 'img') {
                        $value = $img;
                    }
                    if ($v == 'photo') {
                        $value = $photo;
                    }
                    if ($v == 'details') {
                        $value = $details;
                    }
                    $goods_arr[$v] = $goods[$v][$lang['code']] ?? $value;
                } else {
                    $goods_arr[$v] = $goods[$v][$lang['code']] ?? '';
                }
            }
            self::addGoodsInfo($goods_arr, $lang['code']);
        }
        $goods['title'] = $title;
        $goods['img'] = $img;
        $goods['photo'] = $photo;
        $goods['details'] = $details;
        return $goods;
    }

    /**
     * 获取商品多语言信息
     * @param $goods
     * @param string $lang
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getGoodsInfo($goods, $lang = '')
    {
        if (empty($lang)) {
            return $goods;
        }
        if (empty($goods['id'])) {
            return $goods;
        }
        $map = [
            'goods_id' => $goods['id'],
            'lang' => $lang,
        ];
        // `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
        //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
        //  `photo` text COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
        //  `details` text COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
        $goods_lang = self::where($map)->field('title,img,photo,details')->find();
        if (empty($goods_lang)) {
            return $goods;
        }
        $goods_arr = json_decode(json_encode($goods), true);
        foreach ($goods_arr as $k => $v) {
            if (isset($goods_lang[$k]) && isset($goods[$k])) {
                $goods[$k] = $goods_lang[$k];
            }
        }
        return $goods;
    }

    /**
     * 获取商品多语言信息
     * @param $goods
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getGoodsLangs($goods)
    {
        if (empty($goods)) {
            return [];
        }
        if (empty($goods['id'])) {
            return $goods;
        }
        $map = [
            'goods_id' => $goods['id']
        ];
        // `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标题',
        //  `img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '封面图片',
        //  `photo` text COLLATE utf8mb4_unicode_ci COMMENT '顶部展示图片 视频',
        //  `details` text COLLATE utf8mb4_unicode_ci COMMENT '详情 图集',
        $langs = LanguageModel::getCacheList();
        $goods_langs = self::where($map)->field('lang,title,img,photo,details')->select();
        $goods_arr = [];
        $field = ['title', 'img', 'photo', 'details'];
        foreach ($langs as $lang) {
            foreach ($field as $v) {
                $goods_arr[$v][$lang['code']] = $goods[$v];
            }
            foreach ($goods_langs as $goods_lang) {
                foreach ($field as $v) {
                    $goods_arr[$v][$goods_lang['lang']] = $goods_lang[$v];
                }
            }
        }
        foreach ($field as $v) {
            $goods[$v] = $goods_arr[$v];
        }
        return $goods;
    }

    /**
     * 获取商品名称
     * @param $id
     * @param string $lang
     * @return mixed|string
     */
    public static function getGoodsName($id, $lang = '')
    {
        if (empty($id)) {
            return '';
        }
        if (empty($lang)) {
            return GoodsProductModel::where('id', $id)->value('title');
        }
        $map = [
            'goods_id' => $id,
            'lang' => $lang,
        ];
        return self::where($map)->value('title');
    }
}
