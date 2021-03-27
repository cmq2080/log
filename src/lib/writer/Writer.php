<?php
/**
 * 描述：
 * Created at 2021/3/27 16:53 by 陈庙琴
 */

namespace mr_logger\lib\writer;


use mr_logger\lib\interface_set\WriterI;
use mr_logger\Log;

class Writer implements WriterI
{
    protected $style = '';

    public function write($file, $title, $content, $logType)
    {
        // TODO: Implement write() method.
        error_log($this->decorate($title, $content, $logType), 3, $file);
    }

    protected function getIp()
    {
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        return $ip;
    }

    /**
     * 功能：
     * Created at 2021/3/27 17:15 by 陈庙琴
     * @param $title
     * @param $content
     * @param $logType
     * @return string
     * @throws \Exception
     */
    protected function decorate($title, $content, $logType)
    {
        $str = '';
        $ip = $this->getIp();
        $timestamp = time();
        switch ($logType) {
            case Log::LOG_TYPE_TEXT:
                $str .= '[' . date('Y-m-d H:i:s', $timestamp) . '] | ' . $ip . ' | ' . $title . "\r\n";
                $str .= $content . "<br>\n";
                break;
            case Log::LOG_TYPE_HTML:
                $str .= '<div id="' . $timestamp . '">';
                $str .= '[' . date('Y-m-d H:i:s', $timestamp) . '] | ' . $ip . ' | <b style="' . $this->style . '">' . $title . "</b><br>\n";
                $str .= $content . "</div>\n";
                break;
            default:
                throw new \Exception('未知的日志模式');
        }

        return $str;
    }
}