BjyCacheStorage
===============
Provides cache storage adapters for backends not supplied by Zend\Cache.

Adapters
--------
 - Redis (equires Predis >= v0.7)

Usage
-----
```php
$objectProxy = PatternFactory::factory('object', array(
    'object' => $object,
    'storage' => 'BjyCacheStorage\Adapter\Redis'
));
```
