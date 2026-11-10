<?php

namespace app\model\order;

use app\base\BaseModel;
use app\model\goods\GoodsProductModel;
use app\model\tourism\TourismProductModel;
use app\model\user\UserModel;
use think\model\concern\SoftDelete;

/**
 * 模型-OrderCommentModel
 * Class OrderCommentModel
 * @package app\model
 */
class OrderCommentModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'order_comment';

    /**
     * 获取分页列表
     * @param $map
     * @param $current_page
     * @param $list_rows
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageData($map, $current_page, $list_rows, $order = 'id DESC', $field = '*')
    {
        $dataList = $this
            ->field($field)
            ->where($map)
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $items = $dataList->items();
        foreach ($items as &$item) {
            if (!empty($item['images'])) {
                $item['images'] = json_decode($item['images'], true);
            } else {
                $item['images'] = [];
            }
            if ($item['type'] == OrderModel::TYPE_TOURISM) {
                $product_info = TourismProductModel::getProductInfo($item['product_id']);
            } else {
                $product_info = GoodsProductModel::getProductInfo($item['product_id']);
            }
            $item['product_name'] = $product_info['title'];
            $item['product_img'] = $product_info['img'];
        }
        return ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $items];
    }

    /**
     * 添加评论
     * @param array $comment
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addCommentLog($comment = [])
    {
        // $up_data = [
        //            'order_id' => 30,
        //            'product_id' => 55,
        //            'type' => OrderModel::TYPE_TOURISM,
        //            'user_id' => 1,
        //            'content' => '测试评论',
        //            'images' => [
        //                'http://ykzn.oss-cn-shanghai.aliyuncs.com/head_img/2024-08-22/5de36e13e03833c9ca6ae96c448fbf5c3454c041.png',
        //                'http://ykzn.oss-cn-shanghai.aliyuncs.com/head_img/2024-08-22/5de36e13e03833c9ca6ae96c448fbf5c3454c041.png',
        //                'http://ykzn.oss-cn-shanghai.aliyuncs.com/head_img/2024-08-22/5de36e13e03833c9ca6ae96c448fbf5c3454c041.png',
        //            ],
        //            'anonymous' => 1,
        //            'all_score' => 5,
        //            'tour_guide_score' => 5,
        //            'service_score' => 5,
        //            'experience_score' => 5,
        //        ];
        $map = [
            'order_id' => $comment['order_id'],
            'user_id' => $comment['user_id'],
        ];
        $temp = self::where($map)->find();
        if (!empty($temp)) {
            $in_data = [
                'content' => $comment['content'],
                'images' => json_encode($comment['images']),
                'images_num' => count($comment['images']),
                'anonymous' => $comment['anonymous'],// 是否匿名
                'all_score' => $comment['all_score'],// 综合评分
                'tour_guide_score' => $comment['tour_guide_score'],// 导游水平
                'service_score' => $comment['service_score'],// 服务态度
                'experience_score' => $comment['experience_score'],// 游玩体验
                'update_time' => time(),
            ];
            $ret = self::where($map)->update($in_data);
            if (!$ret) {
                return false;
            }
            $order_model = new OrderModel();
            if ($comment['type'] == OrderModel::TYPE_TOURISM) {
                $ret = $order_model->changeOrderStatus($comment['type'], $comment['order_id'], OrderTourismModel::STATUS_EVALUATED);
            } else {
                $ret = $order_model->changeOrderStatus($comment['type'], $comment['order_id'], OrderGoodsModel::STATUS_EVALUATED);
            }
            if ($ret) {
                return true;
            }
            return false;
        }
        $user_data = UserModel::getNameHead($comment['user_id']);
        //  `anonymous` tinyint(1) DEFAULT '1' COMMENT '是否匿名 1非匿名  2匿名',
        //  `all_score` float(10,2) DEFAULT '0.00' COMMENT '体验指数 综合评分',
        //  `tour_guide_score` float(10,2) DEFAULT '0.00' COMMENT '导游水平 评分',
        //  `service_score` float(10,2) DEFAULT '0.00' COMMENT '服务态度 评分',
        //  `experience_score` float(10,2) DEFAULT '0.00' COMMENT '游玩体验 评分',
        $in_data = [
            'order_id' => $comment['order_id'],
            'user_id' => $comment['user_id'],
            'type' => $comment['type'],
            'user_name' => $user_data['nick_name'],
            'user_head' => $user_data['head_img'],
            'product_id' => $comment['product_id'],
            'content' => $comment['content'],
            'images' => json_encode($comment['images']),
            'images_num' => count($comment['images']),
            'anonymous' => $comment['anonymous'],// 是否匿名
            'all_score' => $comment['all_score'],// 综合评分
            'tour_guide_score' => $comment['tour_guide_score'],// 导游水平
            'service_score' => $comment['service_score'],// 服务态度
            'experience_score' => $comment['experience_score'],// 游玩体验
            'create_time' => time(),
            'update_time' => time(),
        ];
        self::startTrans();
        $ret = self::insert($in_data);
        if ($ret) {
            $order_model = new OrderModel();
            if ($comment['type'] == OrderModel::TYPE_TOURISM) {
                $ret = $order_model->changeOrderStatus($comment['type'], $comment['order_id'], OrderTourismModel::STATUS_EVALUATED);
            } else {
                $ret = $order_model->changeOrderStatus($comment['type'], $comment['order_id'], OrderGoodsModel::STATUS_EVALUATED);
            }
            if (!$ret) {
                self::rollback();
                return false;
            }
            self::commit();
            return true;
        } else {
            self::rollback();
            return false;
        }
    }

    /**
     * 获取评论列表
     * @param $product_id
     * @param int $limit
     * @return OrderCommentModel[]|array|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCommentList($product_id, $limit = 1)
    {
        $map = [
            'product_id' => $product_id,
        ];
        // `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '主键，自增ID',
        //  `order_id` int DEFAULT '0' COMMENT '产品订单id',
        //  `product_id` int DEFAULT '0' COMMENT '套餐id',
        //  `user_id` int DEFAULT '0' COMMENT '用户id',
        //  `user_name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户昵称',
        //  `user_head` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '用户头像',
        //  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '评价内容',
        //  `images` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '图集(视频） json 数组类型',
        //  `anonymous` tinyint(1) DEFAULT '1' COMMENT '是否匿名 1非匿名  2匿名',
        //  `all_score` float(10,2) DEFAULT '0.00' COMMENT '体验指数 综合评分',
        //  `tour_guide_score` float(10,2) DEFAULT '0.00' COMMENT '导游水平 评分',
        //  `service_score` float(10,2) DEFAULT '0.00' COMMENT '服务态度 评分',
        //  `experience_score` float(10,2) DEFAULT '0.00' COMMENT '游玩体验 评分',
        $field = "id,order_id,product_id,user_id,user_name,user_head,content,images,anonymous,all_score,tour_guide_score,service_score,experience_score,update_time";
        $temp = self::where($map)->order('id DESC')->field($field)->limit($limit)->select();
        if (empty($temp)) {
            return [
                'total' => 0,
                'dataList' => []
            ];
        }
        foreach ($temp as $k => $v) {
            $temp[$k]['images'] = json_decode($v['images'], true);
            $temp[$k]['time'] = date('Y-m-d', strtotime($v['update_time']));
            if ($v['anonymous'] == 2) {
                $temp[$k]['user_name'] = '匿名用户';
            }
        }
        return [
            'total' => self::where($map)->count(),
            'dataList' => $temp
        ];
    }
}
