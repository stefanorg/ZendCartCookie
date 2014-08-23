<?php

namespace ZendCartCookie;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use ZendCart\Event\CartEvent;
use Zend\EventManager\SharedEventManager;
use Zend\Console\Console;

class Module implements AutoloaderProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        if(!Console::isConsole()){
            $cookieService = $e->getApplication()->getServiceManager()->get('zendcartcookie_cartcookie_service');

            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, array($cookieService, 'retrieveCartVerifierCookie'), -50);

            // Attach the cart create and delete events to the shared event manager
            $sem = $e->getApplication()->getEventManager()->getSharedManager();
            $sem->attach('ZendCart\Service\Cart', CartEvent::EVENT_CREATE_CART_POST, array($cookieService, 'setCartVerifierCookie'));
            $sem->attach('ZendCart\Service\Cart', CartEvent::EVENT_DELETE_CART_POST, array($cookieService, 'removeCartVerifierCookie'));
        }
    }


    public function getServiceConfig()
    {
        return array(
            'aliases' => [
                'zendcartcookie_persistent_zendcart_service' => 'zendcartdoctrineorm_cart_service',
            ],
            'factories' => array(
                'zendcartcookie_cartcookieveririfer_adapter' => function($sm) {
                    $om = $sm->get('Doctrine\ORM\EntityManager');
                    $config = $sm->get('config')['zendcartcookie'];
                    $adapter = new Adapter\DoctrineCartCookieAdapter($om, $config['entity_class_name']);
                    return $adapter;
                },
                'zendcartcookie_cartcookie_service' => 'ZendCartCookie\Service\Factory\CartCookieServiceFactory'
            ),
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                   __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
