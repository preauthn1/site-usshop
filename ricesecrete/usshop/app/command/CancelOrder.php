<?php
declare (strict_types=1);

namespace app\command;

use app\model\order\OrderModel;
use app\model\system\SysConfigModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;

/**
 * 取消未支付订单
 * Class Upgrade
 * @package app\command
 */
//
class CancelOrder extends Command
{
    protected function configure()
    {
        $this->setName('cancel_order')->setDescription('取消未支付订单');
        $this->addOption('time', 't', Option::VALUE_REQUIRED, '时间');
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
        $time = SysConfigModel::getConfigKeyValue('OrderCancelTime');
        if ($input->hasOption("time")) {
            $time = $input->getOption("time");
            $time = empty($time) ? 60 * 30 : intval($time);
        }
        $order_model = new OrderModel();
        // 取消未支付订单
        $order_model->cancelOrderList($time);
        $output->writeln("取消未支付订单: " . date("Y-m-d H:i:s"));
        // 未支付短信提醒
        $order_model->paymentOrdersSmsList($time - (15 * 30));
        $output->writeln("提现未支付短信提醒: " . date("Y-m-d H:i:s"));
    }
}
