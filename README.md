BjyCacheStorage
===============
Provides cache storage adapters for backends not supplied by Zend\Cache.

Adapters
--------
 - Redis (requires Predis >= v0.7)
 - ZendDb (using most RDBMS platforms)

Usage
-----
```php
$objectProxy = PatternFactory::factory('object', array(
    'object' => $object,
    'storage' => 'redis'
));
```

Or for more options:

```php
$objectProxy = PatternFactory::factory('object', array(
    'object' => $object,
    'storage' => array(
        'adapter' => array(
            'name' => 'zenddb',
            'options' => array(
                'adapter'    => new Zend\Db\Adapter\Adapter,
                'tablename'  => 'cache',
                'keyfield'   => 'key',
                'valuefield' => 'value'
            ),
        ),
    ),
);
```

Configuration
-------------
For configuration options for Redis, please check the readme for the [Predis library](https://github.com/nrk/predis)

All configuration options for Zend\Db are shown in the example above
