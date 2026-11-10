<?php

namespace app\jobs;

use app\model\system\SysBulletinModel;
use app\model\system\SysNotificationModel;
use think\facade\Db;
use think\queue\Job;

class BulletinJob
{
    public function add_notification(Job $job, $bulletin_id)
    {
        $bulletin = (new SysBulletinModel())->getInfo($bulletin_id);
        $bulletin_id = $bulletin['id'];
        // 查找没有添加个公告的用户
        $notification_model = new SysNotificationModel();
        // 获取已添加公告的用户
        $has_bulletin_users = $notification_model->getHasBulletinUsers($bulletin_id);
        // 之前添加过公告的用户修改状态
        $notification_model->updateByBulletinId($bulletin);

        Db::name('user')
            ->where('id', 'not in', $has_bulletin_users)
            ->field('id')
            ->chunk(1000, function ($users) use ($bulletin) {
                $data = [];
                $time = time();
                foreach ($users as $user) { // 循环构建要添加的数据
                    $data[] = [
                        'user_id' => $user['id'],
                        'status' => $bulletin['status'],
                        'type' => 2,
                        'title' => $bulletin['title'],
                        'content' => $bulletin['content'],
                        'is_popup' => $bulletin['is_popup'],
                        'bulletin_id' => $bulletin['id'],
                        'create_time' => $time,
                        'update_time' => $time,
                    ];
                }
                Db::name('sys_notification')->replace()->insertAll($data);
            });

        $job->delete();
    }
    public function delete_notification(Job $job, $bulletin_id)
    {
        $notification_model = new SysNotificationModel();
        $notification_model->deleteByBulletinId($bulletin_id);
        $job->delete();
    }
}
