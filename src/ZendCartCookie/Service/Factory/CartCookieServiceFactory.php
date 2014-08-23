<?php
namespace ZendCartCookie\Service\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendCartCookie\Service\CartCookie;

/**
* Factory
*/
class CartCookieServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $sm)
    {
    	$service = new CartCookie();
        $service->setRequest($sm->get('request'));
        $service->setResponse($sm->get('response'));
        $service->setCookieVerifierAdapter($sm->get('zendcartcookie_cartcookieveririfer_adapter'));
        $service->setCookieConfig($sm->get('config')['zendcartcookie']);
        $service->setPersistentZendCartService($sm->get('zendcartcookie_persistent_zendcart_service'));
        return $service;
    }
}