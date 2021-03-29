<?php
namespace mr_logger\lib;

use mr_logger\Log;

class Config
{
    private $data = [
        'base_dir' => '',
        'write_mode' => Log::WRITE_MODE_NORMAL,
        'log_type' => Log::LOG_TYPE_TEXT,
        'extension' => 'log'
    ];

    /**
     * 功能：设置数据
     * Created at 2021/3/27 15:12 by 陈庙琴
     * @param $key
     * @param null $value
     * @throws \Exception
     */
    public function set($key, $value = null)
    {
        if ($value !== null) { // 设置一个
            if (is_string($key) === false) { // 此时键必须是字符串
                throw new \Exception('设置配置错误：当仅传入两个参数时第一个参数必须为字符串');
            }
            $this->data[$key] = $value;
            return;
        }

        if (is_array($key) === false) { // 第二个参数没有传，那第一个参数必须是关联数组，否则无法收场
            throw new \Exception('设置配置错误：当仅传入一个参数时该参数必须为关联数组');
        }
        $this->data = array_merge($this->data, $key);
        return;
    }

    /**
     * 功能：获取数据
     * Created at 2021/3/27 15:16 by 陈庙琴
     * @param null $key
     * @return array|mixed|null
     */
    public function get($key = null)
    {
        return $key === null ?
            $this->data :
            (isset($this->data[$key]) === true ? $this->data[$key] : null);
    }
}