<?php

namespace app\upgrade;

use app\model\system\AuthRuleModel;
use app\upgrade\interfaces\Ver;
use think\facade\Db;

abstract class VersionBase implements Ver
{

    /**
     * 判断表名是否存在
     * @param string $full_table_name 表名称带前缀
     * @param string $config 数据库配置
     * @return bool
     */
    public function existTable($full_table_name, $config = "")
    {
        return $this->existTableFullName($full_table_name, $config);
    }

    /**
     * 根据SQL创建表 自动判断表是否存在
     * @param $full_table_name
     * @param string $sql
     * @param string $config
     */
    public function createTableSql($full_table_name, $sql = '', $config = "")
    {
        if (!$this->existTableFullName($full_table_name, $config)) {
            $this->execute($sql, $config);
            return true;
        }
        return false;
    }

    public function existTableFullName($full_table_name, $config = "")
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
    public function existTableField($full_table_name, $field, $config = "")
    {
        $fields = $this->getFields($full_table_name, $config);
        return in_array($field, $fields);
    }

    /**
     * 获取表的所有字段
     * @param type $full_table_name 表名称带前缀
     * @param type $config 数据库配置
     * @return type
     */
    public function getFields($full_table_name, $config = "")
    {
        $fields = Db::connect($config)->query("show COLUMNS FROM {$full_table_name}");
        $res = [];
        foreach ($fields as $value) {
            $res[] = $value['Field'];
        }

        return $res;
    }

    /**
     * 判断表是否存在字段
     * @param string $full_table_name 表名称带前缀
     * @param string $index 字段名称
     * @param string $config 数据库配置
     * @return bool
     */
    public function existTableIndex($full_table_name, $index, $config = "")
    {
        $indexs = $this->getIndexs($full_table_name, $config);
        return in_array($index, $indexs);
    }

    /**
     * 获取表的所有索引
     * @param type $full_table_name 表名称带前缀
     * @param type $config 数据库配置
     * @return type
     */
    public function getIndexs($full_table_name, $config = "")
    {
        $fields = Db::connect($config)->query("show index FROM {$full_table_name}");
        $res = [];
        foreach ($fields as $value) {
            $res[] = $value['Key_name'];
        }

        return $res;
    }

    /**
     * 执行SQL
     * @param string $sql
     * @param type $config 数据库配置
     */
    public function execute($sql, $config = "")
    {
        if (strpos($sql, ';') > 0) {
            $sql = explode(';', $sql);
            foreach ($sql as $value) {
                if (!empty(trim($value))) {
                    $value = $value . ";";
                    Db::connect($config)->execute($value);
                }
            }
        } else {
            Db::connect($config)->execute($sql);
        }
    }

    /**
     * 执行SQL 要插入的数据是否存在
     * @param type $sql
     * @param type $config 数据库配置
     * @return Array
     */
    public function getTableData($sql, $config = "")
    {
        $ret = Db::connect($config)->query($sql);
        Db::connect($config)->close();
        return $ret;
    }

    /**
     * 添加一级菜单
     * @param string $title
     * @param string $name
     * @param string $icon
     * @param int $rank
     * @return int|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addMenuNode($title = '', $name = '', $icon = "iconfont icon-gongzuotai", $rank = 100)
    {
        $auth_rule_model = new AuthRuleModel();
        $map = [
            'name' => $name
        ];
        $temp = $auth_rule_model->where($map)->find();
        if (!empty($temp)) {
            return $temp['id'];
        }
        $index_data = [
            'name' => $name,
            'title' => $title,
            'url' => "",
            'type' => 0,
            'icon' => $icon,
            'api_method' => '',
            'rank' => $rank,
            'parent_id' => 0,
            'update_time' => time(),
            'create_time' => time(),
        ];
        return $auth_rule_model->insertGetId($index_data);
    }

    /**
     * 添加子级菜单
     * @param string $title
     * @param string $name
     * @param string $pid
     * @param int $rank
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addChildNode($title = '', $name = '', $pid = '', $rank = 1)
    {
        $map = [
            'name' => $name . "Index",
        ];
        $auth_rule_model = new AuthRuleModel();
        $temp = $auth_rule_model->where($map)->find();
        if (!empty($temp)) {
            $pid_index = $temp['id'];
        } else {
            $index_data = [
                'name' => $name . "Index",
                'title' => $title,
                'url' => "/{$name}/index",
                'type' => 1,
                'icon' => '',
                'api_method' => 0,
                'rank' => $rank,
                'parent_id' => $pid,
                'update_time' => time(),
                'create_time' => time(),
            ];
            $pid_index = $auth_rule_model->insertGetId($index_data);
            $index_edit = [
                'name' => $name . "Edit",
                'title' => "编辑",
                'url' => "/{$name}/edit",
                'url_param' => '/:id?',
                'type' => 2,
                'icon' => '',
                'api_method' => 0,
                'rank' => $rank + 1,
                'parent_id' => $pid_index,
                'update_time' => time(),
                'create_time' => time(),
            ];
            $auth_rule_model->insertGetId($index_edit);
        }
        $map = [
            'name' => "{$name}/page",
        ];
        $temp = $auth_rule_model->where($map)->find();
        if (!empty($temp)) {
            return;
        }
        $index_child = [];
        $index_child[] = [
            'name' => "{$name}/page",
            'title' => "获取列表",
            'url' => "{$name}/page",
            'type' => 4,
            'icon' => '',
            'api_method' => 1,
            'rank' => 2,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/info",
            'title' => "获取详情",
            'url' => "{$name}/info",
            'type' => 4,
            'icon' => '',
            'api_method' => 1,
            'rank' => 3,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/save",
            'title' => "保存数据",
            'url' => "{$name}/save",
            'type' => 4,
            'icon' => '',
            'api_method' => 2,
            'rank' => 4,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/status",
            'title' => "修改状态",
            'url' => "{$name}/status",
            'type' => 4,
            'icon' => '',
            'api_method' => 2,
            'rank' => 5,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/delete",
            'title' => "删除数据",
            'url' => "{$name}/delete",
            'type' => 4,
            'icon' => '',
            'api_method' => 2,
            'rank' => 6,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}Status",
            'title' => "状态按钮",
            'url' => "",
            'type' => 3,
            'icon' => '',
            'api_method' => 0,
            'rank' => 7,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}DelBtn",
            'title' => "删除按钮",
            'url' => "",
            'type' => 3,
            'icon' => '',
            'api_method' => 0,
            'rank' => 8,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $auth_rule_model->insertAll($index_child);
    }
}
