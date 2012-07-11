<?php

namespace BjyCacheStorage\Adapter;

use Zend\Cache\Storage\Adapter\AdapterOptions;

class ZendDbOptions extends AdapterOptions
{
    protected $tableName = 'cache';
    protected $keyField = 'key';
    protected $valueField = 'value';
    protected $dbAdapter;

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function getKeyField()
    {
        return $this->keyField;
    }

    public function setKeyField($keyField)
    {
        $this->keyField = $keyField;
        return $this;
    }

    public function getValueField()
    {
        return $this->valueField;
    }

    public function setValueField($valueField)
    {
        $this->valueField = $valueField;
        return $this;
    }

    public function getDbAdapter()
    {
        return $this->dbAdapter;
    }

    public function setDbAdapter($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        return $this;
    }
}
