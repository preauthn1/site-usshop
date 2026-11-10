<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\base\BaseEncryptController;
use app\libs\api\ApiIndexCode;
use app\model\system\LanguageItemDetailModel;
use app\model\system\LanguageItemModel;
use app\model\system\LanguageModel;
use app\validate\LanguageItemDetailValidate;
use think\facade\Cache;
use think\facade\Db;
use think\Request;

class LanguageItemDetail extends BaseEncryptController
{

    protected function initialize()
    {
        parent::initialize();
        $this->model = new LanguageItemDetailModel();
    }

    /**
     * 添加翻译
     */
    public function onSave(Request $request)
    {
        if ($request->param('write_lang', '') == 'update') {
            $this->reWriteLang();
            return self::returnMsg(ApiIndexCode::SUCCESS);
        }
        $formData = [
            'id' => $request->param('id'),
            'language_id' => $request->param('language_id'),
            'item_id' => $request->param('item_id'),
            'text' => $request->param('text'),
        ];
        $validate = LanguageItemDetailValidate::class;
        $this->validate($formData, $validate);

        $ret = $this->model->saveData($formData);
        if (false !== $ret['flag']) {
//            $this->reWriteLang();
            return self::returnMsg(ApiIndexCode::SUCCESS);
        } else {
            return self::returnMsg(ApiIndexCode::FAIL);
        }

    }

    /**
     * 导出/导入文件
     * @param Request $request
     */
    public function onFile(Request $request)
    {
        if ($request->param('write_lang', '') == 'out_file') {
            $this->excel();
            exit();
        }
        if ($request->param('write_lang', '') == 'input_file') {
            $this->import();
            return self::returnMsg(ApiIndexCode::SUCCESS);
        }
        return self::returnMsg(ApiIndexCode::FAIL);
    }

    private function reWriteLang()
    {
        Cache::delete(LanguageModel::$cacheKeyTemp);
        Cache::delete(LanguageModel::$cacheKeyTempTime);
        $langs = LanguageModel::where('status', 1)->order('is_default DESC, id ASC')->select();
        $items = LanguageItemModel::order('id DESC')->distinct(true)->select();
        $items_detail = LanguageItemDetailModel::field('language_id,item_id,text')->select();
        $items_detail_arr = [];
        foreach ($items_detail as $item) {
            $items_detail_arr[$item['language_id'] . "_" . $item['item_id']] = $item['text'];
        }
        $lang_config = [];
        foreach ($langs as $lang) {
            $lang_config[$lang->code] = [];
            foreach ($items as $item) {
                $lang_config[$lang->code][$item->title] = $items_detail_arr[$lang->id . "_" . $item['id']] ?? $item->title;
            }
        }

        foreach ($lang_config as $lang => $config) {
            $lang_file = app_path() . "lang/{$lang}.php";
            $code = "<?php \r\nreturn " . var_export(array_filter($config), true) . ";";
            file_put_contents($lang_file, $code);
        }
    }

    //导出数据
    private function excel()
    {
        $langs = LanguageModel::getCacheList();
        $lang_arr = [];
        $lang_arr[] = 'ID';
        $lang_arr[] = '条目';
        foreach ($langs as $lang) {
            $lang_arr[] = $lang->code;
        }
        $item_arr = LanguageItemModel::where('id', '>', 0)->column('title', 'id');
        $item_detail_list = LanguageItemDetailModel::where('id', '>', 0)->select();
        $item_detail_arr = [];
        foreach ($item_detail_list as $item) {
            $item_detail_arr[$item['language_id'] . "_" . $item['item_id']] = $item['text'];
        }
        $list_data = [];
        $list_data[] = $lang_arr;

        foreach ($item_arr as $key => $value) {
            $line_arr = [];
            $line_arr[] = $key;
            $line_arr[] = $value;
            foreach ($langs as $lang) {
                $line_arr[] = $item_detail_arr[$lang->id . "_" . $key] ?? '';
            }
            $list_data[] = $line_arr;
        }
        // 设置响应头以下载文件
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        header('Cache-Control: max-age=0');
        // 打开输出流
        $output = fopen('php://output', 'w');

        // 将数据写入 CSV 文件
        foreach ($list_data as $row) {
            fputcsv($output, $row);
        }
        // 关闭输出流
        fclose($output);
    }

    private function import()
    {
        $file = request()->file('file');
        if (empty($file)) {
            return self::returnMsg(ApiIndexCode::FAIL, '请选择文件');
        }

        // 移动上传文件到临时目录
        $info = $file->move(sys_get_temp_dir());
        if ($info) {
            // 获取文件路径
            $filePath = $info->getPathname();
            // 打开文件
            if (($handle = fopen($filePath, "r")) !== FALSE) {
                $data = [];
                // 读取文件内容并转换为 UTF-8 编码
                $fileContent = file_get_contents($filePath);
                $encoding = mb_detect_encoding($fileContent, 'UTF-8,GB2312,GBK,BIG5');
                $fileContent = mb_convert_encoding($fileContent, 'UTF-8', $encoding);
                file_put_contents($filePath, $fileContent);
                // 重新打开文件
                $handle = fopen($filePath, "r");
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    // 将每一行的数据转换为 UTF-8 编码
                    $data[] = $row;
                }
                fclose($handle);
                // 删除临时文件
                unlink($filePath);
                $item_detail_list = LanguageItemDetailModel::where('id', '>', 0)->select();
                $item_detail_arr = [];
                foreach ($item_detail_list as $item) {
                    $item_detail_arr[$item['language_id'] . "_" . $item['item_id']] = $item['text'];
                }
                // 处理数据
                if (!empty($data)) {
                    $langs = LanguageModel::getCacheList();
                    $langs_code_id = [];
                    foreach ($langs as $lang) {
                        $langs_code_id[$lang['code']] = $lang['id'];
                    }
                    // 假设第一行是标题行
                    $headers = array_shift($data);
                    $list_update = [];
                    $list_add = [];
                    foreach ($data as $row) {
                        $item = array_combine($headers, $row);
                        foreach ($item as $key => $value) {
                            if ($key != 'ID' && $key != '条目') {
                                $map = [
                                    'item_id' => $item["ID"],
                                    'language_id' => $langs_code_id[$key],
                                ];
                                if (isset($item_detail_arr[$langs_code_id[$key] . "" . $item["ID"]])) {
                                    if ($item_detail_arr[$langs_code_id[$key] . "" . $item["ID"]] != $value) {
                                        $list_update[] = [
                                            'item_id' => $item["ID"],
                                            'language_id' => $langs_code_id[$key],
                                            'text' => $value,
                                        ];
                                    }
                                } else {
                                    $temp = LanguageItemDetailModel::where($map)->find();
                                    if (!empty($temp)) {
                                        if ($temp['text'] != $value) {
                                            $list_update[] = [
                                                'item_id' => $item["ID"],
                                                'language_id' => $langs_code_id[$key],
                                                'text' => $value,
                                            ];
                                        }
                                    } else {
                                        $list_add[] = [
                                            'item_id' => $item["ID"],
                                            'language_id' => $langs_code_id[$key],
                                            'text' => $value
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    foreach ($list_update as $item) {
                        $map = [
                            'item_id' => $item["item_id"],
                            'language_id' => $item['language_id'],
                        ];
                        $updata = [
                            'text' => $item['text'],
                        ];
                        //更新文本内容
                        LanguageItemDetailModel::where($map)->update($updata);
                    }
                    foreach ($list_add as $item) {
                        LanguageItemDetailModel::insert($item);
                    }
                    return self::returnMsg(ApiIndexCode::SUCCESS, '导入成功');
                } else {
                    return self::returnMsg(ApiIndexCode::FAIL, '文件内容为空');
                }
            } else {
                return self::returnMsg(ApiIndexCode::FAIL, '无法打开文件');
            }
        } else {
            // 上传失败获取错误信息
            return self::returnMsg(ApiIndexCode::FAIL, $file->getError());
        }
    }

}