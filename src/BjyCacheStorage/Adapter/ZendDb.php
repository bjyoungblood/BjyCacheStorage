<?php

namespace BjyCacheStorage\Adapter;

use \ArrayObject;
use Zend\Cache\Storage\Adapter\AbstractAdapter;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Stdlib\Hydrator\ArraySerializable;

class ZendDb extends AbstractAdapter
{
    protected $adapter;

    public function setOptions($options)
    {
        if (!$options instanceof ZendDbOptions) {
            $options = new ZendDbOptions($options);
        }

        return parent::setOptions($options);
    }

    protected function internalGetItem(& $normalizedKey, & $success = null, & $casToken = null)
    {
        $sql = new Select();
        $sql->from($this->getOptions()->getTableName());

        $where = new Where;
        $where->equalTo($this->getOptions()->getKeyField(), $normalizedKey);

        $result = $this->selectWith($sql->where($where));
        $result = count($result) ? $result->current() : false;

        if ($result) {
            $success = true;
            return unserialize($result[$this->getOptions()->getValueField()]);
        } else {
            $success = false;
            return false;
        }
    }

    protected function internalSetItem(& $normalizedKey, & $value)
    {
        $sql = new Sql($this->getDbAdapter(), $this->getOptions()->getTableName());

        try {
            $insert = $sql->insert();
            $insert->values(array(
                $this->getOptions()->getKeyField() => $normalizedKey,
                $this->getOptions()->getValueField() => serialize($value)
            ));

            $statement = $sql->prepareStatementForSqlObject($insert);
            $result = $statement->execute();
        } catch (\Exception $e) {
            $where = new Where;
            $where->equalTo($this->getOptions()->getKeyField(), $normalizedKey);

            $update = $sql->update();
            $update->set(array($this->getOptions()->getValueField() => serialize($value)))
                ->where($where);

            $statement = $sql->prepareStatementForSqlObject($update);
            $result = $statement->execute();
        }

        return true;
    }

    protected function internalRemoveItem(& $normalizedKey)
    {
        $sql = new Sql($this->getDbAdapter(), $this->getOptions()->getTableName());

        $where = new Where;
        $where->equalTo($this->getOptions()->getKeyField(), $normalizedKey);

        $delete = $sql->delete();
        $delete->where($where);

        $statement = $sql->prepareStatementForSqlObject($delete);
        $result = $statement->execute();
    }

    protected function selectWith(Select $select)
    {
        $adapter = $this->getDbAdapter();
        $statement = $adapter->createStatement();
        $select->prepareStatement($adapter, $statement);
        $result = $statement->execute();

        $resultSet = new HydratingResultSet;
        $resultSet->setObjectPrototype(new ArrayObject);
        $resultSet->setHydrator(new ArraySerializable);

        $resultSet->initialize($result);

        return $resultSet;
    }

    public function getDbAdapter()
    {
        return $this->getOptions()->getDbAdapter();
    }

}
