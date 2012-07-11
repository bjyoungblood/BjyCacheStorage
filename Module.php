<?php

namespace BjyCacheStorage;

use Predis\Client;
use Zend\Cache\StorageFactory;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap($e)
    {
        $mainSm = $e->getTarget('application')->getServiceManager();

        $adapterPluginManager = StorageFactory::getAdapterPluginManager();
        $adapterPluginManager->setFactory('redis', function($sm) use ($mainSm) {
            $adapter = new Adapter\Redis;
            $adapter->setPredis($mainSm->get('predis'));
            return $adapter;
        }, false);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'predis' => function($sm) {
                    $config = $sm->get('Configuration');
                    $predis = new Client($config['bjycachestorage']['redis']);
                    return $predis;
                },
            ),
        );
    }
}
