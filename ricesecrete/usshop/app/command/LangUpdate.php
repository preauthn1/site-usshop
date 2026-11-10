<?php
declare (strict_types=1);

namespace app\command;

use app\libs\GoogleTranslate;
use app\model\system\LanguageItemDetailModel;
use app\model\system\LanguageItemModel;
use app\model\system\LanguageModel;
use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * 更新语言
 * Class Upgrade
 * @package app\command
 */
//php think lang_update
class LangUpdate extends Command
{
    protected function configure()
    {
        $this->setName('lang_update')->setDescription('取消未支付订单');
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
        $lang_list = LanguageItemModel::where('id', '>', 0)->order('id', 'asc')->select();
        $langs = LanguageModel::getCacheList();
        $trans = new GoogleTranslate();
        $LanguageItemDetailModel = new LanguageItemDetailModel();
        foreach ($lang_list as $lang) {
            foreach ($langs as $lang_item) {
                $map = [
                    'language_id' => $lang_item['id'],
                    'item_id' => $lang['id'],
                ];
                $temp = $LanguageItemDetailModel->where($map)->find();
                $source = "auto";
                if (empty($temp)) {
                    $lang['title'] = str_replace("&", "____", $lang['title']);
                    print_r($lang['title'] . $source . "|" . $lang_item['code'] . "添加\n");
                    $result = $trans->translate($source, $lang_item['code'], $lang['title']);
                    $result = str_replace("____", "&", $result);
                    print_r($result . $source . "|" . $lang_item['code'] . "翻译\n");
                    $add_data = [
                        'language_id' => $lang_item['id'],
                        'item_id' => $lang['id'],
                        'text' => $result
                    ];
                    $LanguageItemDetailModel->insert($add_data);
                } else if (empty($temp['text'])) {
                    $lang['title'] = str_replace("&", "____", $lang['title']);
                    print_r($lang['title'] . $source . "|" . $lang_item['code'] . "更新\n");
                    $result = $trans->translate($source, $lang_item['code'], $lang['title']);
                    $result = str_replace("____", "&", $result);
                    print_r($result . $source . "|" . $lang_item['code'] . "翻译\n");
                    $up_data = [
                        'text' => $result
                    ];
                    $LanguageItemDetailModel->where($map)->update($up_data);
                }
            }
        }
    }
}
