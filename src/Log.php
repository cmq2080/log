<?php
namespace mr_logger;

use mr_logger\lib\Config;
use mr_logger\lib\trait_set\ConfigTrait;

class Log
{
    use ConfigTrait;

    const LEVEL_DEBUG = 'debug'; // 默认（黑色）
    const LEVEL_INFO = 'info'; // 绿色
    const LEVEL_NOTICE = 'notice'; // 蓝色
    const LEVEL_WARNING = 'warning'; // 橙色
    const LEVEL_ERROR = 'error'; // 红色

    const WRITE_MODE_NORMAL = 1;
    const WRITE_MODE_IN_ONE = 2;

    const LOG_TYPE_TEXT = 1;
    const LOG_TYPE_HTML = 2;

    private static $instance = null;
    private $config = null;
    private $title = '';
    private $branchDir = '';

    public static function dir($branchDir = null)
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        self::$instance->clear();
        if ($branchDir !== null) {
            self::$instance->branchDir = $branchDir;
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * 功能：清空数据
     * Created at 2021/3/27 15:37 by 陈庙琴
     */
    private function clear()
    {
        $this->config = new Config();
        $this->title = '';
        $this->branchDir = '';
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 功能：写入日志（核心功能）
     * Created at 2021/3/27 16:00 by 陈庙琴
     * @param $level
     * @param $logType
     */
    public function write($content, $level)
    {

        $content = $this->toString($content);

        $toWriteFile = $this->getToWriteFile($level);
        $writer = $this->getWriter($level);
        $writer->write($toWriteFile, $this->title, $content, $this->config->get('log_type'));
    }

    private function getToWriteFile($level)
    {
        $dirname = realpath($this->config->get('base_dir') . '/' . $this->branchDir . '/' . date('Ym'));
        if (is_dir($dirname) === false) {
            mkdir($dirname);
        }

        $filename = date('d');
        $writeMode = $this->config->get('write_mode');
        if ($writeMode === self::WRITE_MODE_NORMAL) {
            $filename .= '_' . $level;
        } else if ($writeMode === self::WRITE_MODE_IN_ONE) {
        }
        $extension = $this->config->get('extension');
        $filename .= '.' . ltrim($extension, '.');

        return $dirname . '/' . $filename;
    }

    private function toString($content)
    {
        // 异常类自动转换文本
        if ($content instanceof \Exception) {
            $content = date('Y-m-d H:i:s') . ' - line ' . $content->getLine() . ' in ' . $content->getFile() . ':<span style="' . $this->getStyle(self::LEVEL_ERROR) . '">' . $content->getMessage() . "</span><br>\n" . $content->getTraceAsString();
        }

        // 不是字符串的直接JSON序列化
        if (is_string($content) === false) {
            $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        return $content;
    }

    private function getWriter($level)
    {
        $writer = '\\mr_logger\\lib\\writer\\' . ucfirst($level);
        if (class_exists($writer) === false) {
            throw new \Exception('Writer不存在');
        }

        return new $writer();
    }

    /**
     * 功能：读取日志（次核心功能）
     * Created at 2021/3/27 16:00 by 陈庙琴
     */
    public function read()
    {
    }

}