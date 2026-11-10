<?php
declare (strict_types=1);

namespace app\command;

use app\model\system\AuthRuleModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

/**
 * 创建新的路由
 * Class NewPage
 * @package app\command
 */
class NewPage extends Command
{
    protected function configure()
    {
        $this->setName('newpage')->setDescription('创建路由');
        $this->addOption('name', 'r', Option::VALUE_REQUIRED, '控制器名称');
        $this->addOption('title', 't', Option::VALUE_REQUIRED, '标题');
        $this->addOption('parent', 'p', Option::VALUE_REQUIRED, '父级菜单');
    }

    /**
     *
     * @param Input $input
     * @param Output $output
     * @return int|void|null
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    protected function execute(Input $input, Output $output)
    {
        $name = "";
        if ($input->hasOption("name")) {
            $name = $input->getOption("name");
        }
        $title = "";
        if ($input->hasOption("title")) {
            $title = $input->getOption("title");
        }
        $parent = "";
        if ($input->hasOption("parent")) {
            $parent = $input->getOption("parent");
        }
        if (empty($name) || empty($title) || empty($parent)) {
            echo "error";
            return;
        }
        $auth_rule_model = new AuthRuleModel();
        $par_data = $auth_rule_model->where('name', $parent)->find();
        if (empty($par_data)) {
            $index_data = [
                'name' => $parent,
                'title' => $title,
                'url' => "",
                'type' => 0,
                'icon' => '',
                'api_method' => '',
                'rank' => 100,
                'parent_id' => 0,
                'update_time' => time(),
                'create_time' => time(),
            ];
            $pid = $auth_rule_model->insertGetId($index_data);
        } else {
            $pid = $par_data['id'];
        }
        $index = $auth_rule_model->where('name', $name)->find();
        if (empty($index)) {
            $index_data = [
                'name' => $name . "Index",
                'title' => $title,
                'url' => "/{$name}/index",
                'type' => 1,
                'icon' => '',
                'api_method' => 0,
                'rank' => 4,
                'parent_id' => $pid,
                'update_time' => time(),
                'create_time' => time(),
            ];
            $pid_index = $auth_rule_model->insertGetId($index_data);
        } else {
            $pid_index = $index['id'];
        }
        $index_child = [];
        $index_child[] = [
            'name' => $name . "Edit",
            'title' => "编辑",
            'url' => "/{$name}/edit",
            'url_param' => '/:id?',
            'type' => 2,
            'icon' => '',
            'api_method' => 0,
            'rank' => 1,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/page",
            'title' => "获取列表",
            'url' => "{$name}/page",
            'url_param' => '',
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
            'url_param' => '',
            'type' => 4,
            'icon' => '',
            'api_method' => 1,
            'rank' => 3,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/status",
            'title' => "修改状态",
            'url' => "{$name}/status",
            'url_param' => '',
            'type' => 4,
            'icon' => '',
            'api_method' => 2,
            'rank' => 4,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}/delete",
            'title' => "删除数据",
            'url' => "{$name}/delete",
            'url_param' => '',
            'type' => 4,
            'icon' => '',
            'api_method' => 2,
            'rank' => 5,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}Status",
            'title' => "状态按钮",
            'url' => "",
            'url_param' => '',
            'type' => 3,
            'icon' => '',
            'api_method' => 0,
            'rank' => 6,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $index_child[] = [
            'name' => "{$name}DelBtn",
            'title' => "删除按钮",
            'url' => "",
            'url_param' => '',
            'type' => 3,
            'icon' => '',
            'api_method' => 0,
            'rank' => 7,
            'parent_id' => $pid_index,
            'update_time' => time(),
            'create_time' => time(),
        ];
        $auth_rule_model->insertAll($index_child);
    }
}
