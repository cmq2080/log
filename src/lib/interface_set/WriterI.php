<?php
namespace mr_logger\lib\interface_set;

interface WriterI
{
    public function write($file, $title, $content, $logType);
}