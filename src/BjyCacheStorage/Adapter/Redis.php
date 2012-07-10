<?php

namespace BjyCacheStorage\Adapter;

use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Cache\Storage\Capabilities;
use Zend\Cache\Storage\FlushableInterface;

class Redis extends AbstractAdapter implements FlushableInterface
{
    protected $predis;

    protected function internalGetItem(& $normalizedKey, & $success = null, & $casToken = null)
    {
        $val = $this->predis->get($normalizedKey);

        if ($val == null) {
            $success = false;
        } else {
            $success = true;
        }

        return unserialize($val);
    }

    protected function internalSetItem(& $normalizedKey, & $value)
    {
        $this->predis->set($normalizedKey, serialize($value));

        if ($this->getOptions()->getTtl()) {
            $this->predis->expire($normalizedKey, $this->getOptions()->getTtl());
        }

        return false;
    }

    protected function internalRemoveItem(& $normalizedKey)
    {
        return $this->predis->del($normalizedKey);
    }

    public function flush()
    {
        return $this->predis->flushdb();
    }

    public function getPredis()
    {
        return $this->predis;
    }

    public function setPredis($predis)
    {
        $this->predis = $predis;
    }
}
