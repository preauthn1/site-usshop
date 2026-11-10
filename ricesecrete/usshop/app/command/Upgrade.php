<?php
declare (strict_types=1);

namespace app\command;

use app\upgrade\App;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

/**
 * 版本升级
 * Class Upgrade
 * @package app\command
 */
//
class Upgrade extends Command
{
    protected function configure()
    {
        $this->setName('upgrade')->setDescription('数据库升级');
        $this->addOption('ver', 'r', Option::VALUE_REQUIRED, '版本编号');
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
        if ($input->hasOption("ver")) {
            $ver = $input->getOption("ver");
            $ver = empty($ver) ? 0 : intval($ver);
            App::clearCacheVersion($ver);
        }
        $output->writeln("当前版本号: " . App::getCacheVersion());
        App::upgrade();
        $output->writeln("最新版本号: " . App::getVersion());
    }
}
