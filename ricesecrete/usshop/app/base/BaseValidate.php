<?php

namespace app\base;

use think\Validate;

/**
 * 验证基础类
 * Class BaseValidate
 * @package app\base
 */
class BaseValidate extends Validate
{
    public function sceneAdd()
    {
        return $this->remove('id', 'require');
    }

    public function sceneUpStatus()
    {
        return $this->only(['id', 'status']);
    }

    protected function parseErrorMsg(string $msg, $rule, string $title)
    {
        $title = lang($title);
        if (0 === strpos($msg, '{%')) {
            $msg = $this->lang->get(substr($msg, 2, -1));
        } elseif ($this->lang->has($msg)) {
            $msg = $this->lang->get($msg);
        }

        if (is_array($msg)) {
            return $this->errorMsgIsArray($msg, $rule, $title);
        }

        // rule若是数组则转为字符串
        if (is_array($rule)) {
            $rule = implode(',', $rule);
        }

        if (is_scalar($rule) && false !== strpos($msg, ':')) {
            // 变量替换
            if (is_string($rule) && strpos($rule, ',')) {
                $array = array_pad(explode(',', $rule), 3, '');
            } else {
                $array = array_pad([], 3, '');
            }

            $msg = str_replace(
                [':attribute', ':1', ':2', ':3'],
                [$title, $array[0], $array[1], $array[2]],
                $msg
            );

            if (strpos($msg, ':rule')) {
                $msg = str_replace(':rule', (string) $rule, $msg);
            }
        }

        return $msg;
    }
}
