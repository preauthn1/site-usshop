<?php

namespace app\controller\admin;

use app\base\BaseController;
use app\libs\Util;
use app\model\goods\GoodsProductModel;
use app\model\goods\GoodsTypeModel;
use app\model\system\LanguageModel;
use think\facade\Cache;
use think\Request;

/**
 * GoodsType 商品分类
 * Class GoodsType
 * @package app\controller
 */
class GoodsType extends BaseController
{
    protected function initialize()
    {
        parent::initialize();
        $this->model = new GoodsTypeModel();
    }

    /**
     * 层级列表
     * @param Request $request
     * @return \think\response\Json
     */
    public function getTree(Request $request)
    {
        $map = [];
        $status = $request->param('status');
        if (!empty($status)) {
            $map[] = ['status', '=', $status];
        }
        $list = $this->model->where($map)->select();
        $tree = Util::levelTreeChildren($list, 0);
        Util::listOrder($tree, 'sort', 'desc', 'number');
        return self::returnMsg(1, ['treeData' => $tree]);
    }

    /**
     * 获取单条数据
     * @param string $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo($id = '')
    {
        $data = $this->model->getInfo($id);
        return self::returnMsg(1, $data);
    }

    /**
     * 修改
     * @param Request $request
     * @return \think\response\Json
     */
    public function onEdit(Request $request)
    {
        $formData = [
            "name" => $request->param("name"),
            "img" => $request->param("img"),
            "sort" => $request->param("sort"),
            "status" => $request->param("status"),
            "is_show" => $request->param("is_show"),
            "url" => $request->param("url"),
            "id" => $request->param("id"),//id
        ];

        $validate = 'app\validate\GoodsTypeValidate.Update';
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);//修改是否添加进缓存。。。
            if (false !== $ret['flag']) {
                $lang = LanguageModel::getCacheList();
                foreach ($lang as $key => $value) {
                    $data_key = 'sys_goods_type_all_' . 0 . $value['code'];
                    Cache::delete($data_key);
                }
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }

    /**
     * 添加子分类
     * @param Request $request
     * @return \think\response\Json
     */
    public function onAdd(Request $request)
    {
        $formData = [
            "id" => $request->param("id"),//id
            "name" => $request->param("name"),
            "img" => $request->param("img"),
            "status" => $request->param("status"),
            "is_show" => $request->param("is_show"),
            "sort" => $request->param("sort"),
            "url" => $request->param("url"),
            "parent_id" => $request->param("parent_id"),//id等于父级
        ];
        if (empty($formData['id'])) {
            $validate = 'app\validate\GoodsTypeValidate.Add';
        } else {
            $validate = 'app\validate\GoodsTypeValidate.Update';
        }
        $result = $this->validate($formData, $validate);
        if (true !== $result) {
            return self::returnMsg(991, [], $result);
        } else {
            $ret = $this->model->saveData($formData);//修改是否添加进缓存。。。
            if (false !== $ret['flag']) {
                Cache::delete('sys_tourism_type');
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
    }

    /**
     * 上线状态
     * @param Request $request
     * @return \think\response\Json
     */
    public function onStatus(Request $request)
    {
        $formData = [
            'id' => $request->param('id'),
            'status' => $request->param('status'),
        ];
        $is_show = $request->param('is_show');
        if (!empty($is_show)) {
            $up_data = [
                'id' => $request->param('id'),
                'is_show' => $request->param('is_show'),
            ];
            $ret = $this->model->saveData($up_data);
            if (false !== $ret['flag']) {
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        }
        if (is_array($formData['id'])) {
            $list = [];
            foreach ($formData['id'] as $key => $value) {
                $list[] = ['id' => $value, 'status' => $formData['status']];
            }
            $ret = $this->model->saveAll($list);
            if ($ret) {
                Cache::delete('sys_tourism_type');
                return self::returnMsg(1);
            } else {
                return self::returnMsg(0);
            }
        } else {
            $validate = 'app\validate\GoodsTypeValidate.upstatus';
            $result = $this->validate($formData, $validate);
            if (true !== $result) {
                return self::returnMsg(991, [], $result);
            } else {
                $child_ids = $this->model->getChildId($formData['id']);
                if (is_array($child_ids)) {
                    $list = [];
                    foreach ($child_ids as $key => $value) {
                        $list[] = ['id' => $value, 'status' => $formData['status']];
                    }
                    $ret = $this->model->saveAll($list);
                    if ($ret) {
                        Cache::delete('sys_tourism_type');
                        return self::returnMsg(1);
                    } else {
                        return self::returnMsg(0);
                    }
                }
                $ret = $this->model->saveData($formData);
                if (false !== $ret['flag']) {
                    return self::returnMsg(1);
                } else {
                    return self::returnMsg(0);
                }
            }
        }
    }

    /**
     * 删除
     * @param $id
     * @return \think\response\Json
     */
    public function onDelete($id)
    {
        $goods_model = new GoodsProductModel();
        $ret = $goods_model->where('type_id', $id)->find();
        if (!empty($ret)) {
            return self::returnMsg(0, [], '该分类下有产品不能删除');
        }
        $ret = $this->model->where('parent_id', $id)->find();
        if (!empty($ret)) {
            return self::returnMsg(0, [], '该分类下有子分类不能删除');
        }
        $flag = $this->model::destroy(['id' => $id]);
        if (false !== $flag) {
            Cache::delete('sys_tourism_type');
            return self::returnMsg(1);
        } else {
            return self::returnMsg(1002);
        }
    }
}
