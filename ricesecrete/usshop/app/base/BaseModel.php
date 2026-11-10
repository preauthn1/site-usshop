<?php

namespace app\base;

use app\libs\NestedSets;
use app\model\system\AuthUserModel;
use app\model\user\UserModel;
use app\model\user\UserTeamModel;
use Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Db;
use think\Model;

/**
 * 模型基础类
 * Class BaseModel
 * @package app\base
 */
class BaseModel extends Model
{
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
    public $redis;
    public $redis_key;

    CONST STATUS_ENABLE = 1; // 启用|是
    CONST STATUS_DISABLE = 2; // 禁用|否

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->redis = Cache::store('redis')->handler();
        $redis_config = Config::get('cache.stores');
        $this->redis_key = $redis_config['redis']['prefix'] . $this->name;
    }

    /**
     * 删除redis key
     * @param $id
     * @param $suffix
     * @return mixed
     */
    public function delRedis($id, $suffix = '')
    {
        $key = $this->redis_key . $suffix . $id;
        return $this->redis->del($key);
    }

    /**
     * 设置redis
     * @param $obj
     * @param $suffix
     * @return mixed
     */
    public function setRedis($obj, $suffix = '')
    {
        $key = $this->redis_key . $suffix . $obj["id"];
        return $this->redis->set($key, serialize($obj));
    }

    /**
     * 获取缓存数据
     * @param $id
     * @param $suffix
     * @return mixed
     */
    public function getRedis($id, $suffix = '')
    {
        $key = $this->redis_key . $suffix . $id;
        $data = $this->redis->get($key);
        if (!empty($data)) {
            return unserialize($data);
        }
        return [];
    }

    /**
     * 获取单条数据-redis
     * @param $key
     * @param $pk
     * @param $suffix
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfoByRedis($key, $pk = 'id', $suffix = '')
    {
        $keys = $this->redis_key . $suffix . $key;
        $data = $this->redis->get($keys);
        if ($data) {
            $data = unserialize($data);
        } else {
            $data = $this->where($pk, $key)->find();
            if (!empty($data)) {
                $this->setRedis($data->toArray(), $suffix);
            }
        }
        return $data;
    }

    /**
     * 获取单条数据
     * @param $id
     * @param string $field
     * @param string $with
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfo($id, $field = '*', $with = '')
    {
        return $this->with($with)->field($field)->where('id', $id)->find();
    }

    /**
     * 获取单条数据
     * @param $id
     * @param string $field
     * @param string $with
     * @return array|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getInfoMap($map, $field = '*', $with = '')
    {
        return $this->with($with)->field($field)->where($map)->find();
    }

    /**
     * 获取所有数据-redis
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAllByRedis()
    {
        $dataList = [];
        if ($this->redis->exists($this->redis_key)) {
            $objList = $this->redis->get($this->redis_key);
            if (!empty($objList)) {
                $dataList = unserialize($objList);
            }
        } else {
            $objList = $this->select()->toArray();
            foreach ($objList as $obj) {
                array_push($dataList, $obj);
            }
            $this->redis->set($this->redis_key, serialize($dataList));
        }
        return $dataList;
    }

    /**
     * 获取所有数据-redis rank
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAllByRedisRank()
    {
        $dataList = [];
        if ($this->redis->exists($this->redis_key . ':rank')) {
            $objList = $this->redis->get($this->redis_key . ':rank');
            if (!empty($objList)) {
                $dataList = unserialize($objList);
            }
        } else {
            $objList = $this->order('rank', 'DESC')->select()->toArray();
            foreach ($objList as $obj) {
                array_push($dataList, $obj);
            }
            $this->redis->set($this->redis_key . ":rank", serialize($dataList));
        }
        return $dataList;
    }

    /**
     * 获取分页列表
     * @param $map
     * @param $order
     * @param $current_page
     * @param $list_rows
     * @param string|array $field
     * @return array
     * @throws \think\db\exception\DbException
     */
    public function getPageList($map, $order, $current_page, $list_rows, $field = '*')
    {
        $model = $this;
        if (isset($map['like'])) {
            foreach ($map['like'] as $key => $value) {
                $model = $model->whereLike($key, $value, 'OR');
            }
            unset($map['like']);
        }
        $model = $model->where($map)->field($field);
        $dataList = $model
            ->order($order)
            ->paginate(['page' => $current_page, 'list_rows' => $list_rows], false);
        $current = $dataList->currentPage();
        $total = $dataList->total();
        $limit = $dataList->listRows();
        $ret = ['total' => $total, 'current' => $current, 'limit' => $limit, 'dataList' => $dataList->items()];
        return $ret;
    }

    /**
     * 获取所有 符合条件的数据
     * @param array $map
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAll($map = [], $field = '*')
    {
        $list = $this->where($map)->field($field)->select();
        if (empty($list)) {
            return [];
        }
        return $list->toArray();
    }

    /**
     * 获取所有 符合条件的数据
     * @param array $map
     * @param string $order
     * @param string $field
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList($map = [], $order = 'id DESC', $field = '*')
    {
        $list = $this->where($map)->order($order)->field($field)->select();
        if (empty($list)) {
            return [];
        }
        return $list->toArray();
    }

    /**
     * 保存数据
     * @param $data
     * @return array
     */
    public function saveData($data): array
    {
        if (!empty($data['id'])) {
            $flag = $this->update($data, ['id' => $data['id']]);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $flag = $this->strict(false)->insertGetId($data);
            $id = $flag;
        }
        return ['flag' => $flag, 'id' => $id];
    }

    /**
     * 保存数据
     * @param $data
     * @param bool $setRedis
     * @param bool $setRedisRank
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function saveDataRedis($data, $setRedis = false, $setRedisRank = false)
    {
        if (!empty($data['id'])) {
            $flag = $this->update($data, ['id' => $data['id']]);
            $id = $data['id'];
        } else {
            unset($data['id']);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $flag = $this->strict(false)->insertGetId($data);
            $id = $flag;
        }
        if (false !== $flag && !empty($id) && $setRedis) {
            $obj = $this->getInfo($id);
            if ($setRedisRank) {
                if (isset($data['rank'])) {
                    $this->redis->zAdd($this->redis_key . ':rank', $data['rank'], $id);
                }
                if (isset($data['status'])) {
                    if ($data['status'] == 1) {
                        $this->redis->zAdd($this->redis_key . ':rank', $obj['rank'], $id);
                    } else {
                        $this->redis->zRem($this->redis_key . ':rank', $id);
                    }
                }
                $this->setRedis($obj);
            } else {
                if (isset($data['status'])) {
                    if ($data['status'] == 0) {
                        $this->delRedis($id);
                    } else {
                        $this->setRedis($obj);
                    }
                } else {
                    $this->setRedis($obj);
                }
            }
        }
        return ['flag' => $flag, 'id' => $id];
    }

    /**
     * 判断表名是否存在
     * @param $full_table_name 表名称带前缀
     * @param string $config 数据库配置
     * @return bool
     */
    public static function existTable($full_table_name, $config = "")
    {
        $exist = Db::connect($config)->query("show tables like '{$full_table_name}'");
        return empty($exist) ? false : true;
    }

    /**
     * 判断表是否存在字段
     * @param string $full_table_name 表名称带前缀
     * @param string $field 字段名称
     * @param string $config 数据库配置
     * @return bool
     */
    public static function existTableField($full_table_name, $field, $config = "")
    {
        $fields = Db::connect($config)->query("show COLUMNS FROM {$full_table_name}");
        $res = [];
        foreach ($fields as $value) {
            $res[] = $value['Field'];
        }
        return in_array($field, $res);
    }


    /**
     * 获取团队用户id
     * @throws ModelNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws Exception
     */
    public function searchTeamAttr($query, $field, $data)
    {
        // 获取管理员信息
        $admin_id = request()->currentUser['user_id'];
        $admin = AuthUserModel::find($admin_id);
        if ($admin_id != 1) {
            if ($admin->agent_id > 0) {
//                $nestedObj = (new NestedSets(new UserTeamModel()));
//                $team_id = UserTeamModel::where('user_id', $admin->agent_id)->value('id');
//                $level_user_ids = $nestedObj->getPath($team_id, ['id', 'user_id', 'activated'], 1)->column('user_id');
//                $query->whereIn('id', $level_user_ids);
                $query->where($field, $admin->agent_id);
            } else {
                $query->whereIn('id', []);
            }
        }
    }

}
