<?php
namespace app\model\system;

use app\base\BaseModel;
use think\model\concern\SoftDelete;

/**
 * 模型-角色分组
 * Class AuthGroupModel
 * @package app\model
 */
class AuthGroupModel extends BaseModel
{
    use SoftDelete;
    protected $name = 'auth_group';

    /**
     * 删除redis
     */
    public function delAllRedis(){
        $this->redis->del($this->redis_key);
    }

    /**
     * 彻底删除
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delComplete($id)
    {
        $group = self::onlyTrashed()->find($id);
        AuthGroupAccessModel::destroy(['group_id'=>$id],true);
        return $group->force()->delete();
    }

    /**
     * 还原数据
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function backData($id)
    {
        $group = self::onlyTrashed()->find($id);
        $this->setRedis($group->toArray());
        return $group->restore();
    }

    /**
     * 范围查询-status
     * @param $query
     * @param $status
     */
    public function scopeStatus($query, $status)
    {
        if ($status!=null) {
            $query->where('status','=',$status);
        }
    }

    /**
     * 范围查询-keyword
     * @param $query
     * @param $keyword
     */
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->whereLike('title','%'.trim($keyword).'%');
        }
    }

    /**
     * 获取分页列表
     * @param $status
     * @param $keyword
     * @param $current_page
     * @param $list_rows
     * @param string $field
     * @return array
     */
    public function getPageData($status,$keyword,$current_page,$list_rows,$field='*')
    {
        $dataList = $this
            ->field($field)
            ->status($status)
            ->keyword($keyword)
            ->order('create_time','desc')
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows],false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $ret = ['total'=>$total,'current'=>$current,'limit'=>$limit,'dataList'=>$dataList->items()];
        return $ret;
    }
}
