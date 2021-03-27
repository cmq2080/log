<?php
namespace mr_logger\lib\trait_set;

trait ConfigTrait
{
    public function setConfig($key, $value = null)
    {
        $this->config->set($key, $value);
        return $this;
    }

    public function getConfig($key)
    {
        return $this->config->get($key);
    }
}