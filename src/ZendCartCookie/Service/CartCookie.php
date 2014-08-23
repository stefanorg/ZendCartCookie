<?php

namespace ZendCartCookie\Service;

use Zend\Session\Container;
use Zend\Math\Rand;
use ZendCartCookie\Adapter\CartCookieAdapterInterface;
use Zend\Http\Header\Cookie;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Http\Header\SetCookie;
use ZendCart\Event\CartEvent;
use Zend\EventManager\Event;

class CartCookie
{
    protected $cookieVerifierAdapter;
    protected $request;
    protected $response;
    protected $cookieConfig;
    protected $persistentZendCartService;

    public function retrieveCartVerifierCookie()
    {
        $cookies = $this->getRequest()->getCookie();
        if(isset($cookies['zendcart_verifier'])) {
            $this->setSessionVariableFromVerifier($cookies['zendcart_verifier']);
        }
    }

    public function setSessionVariableFromVerifier($verifier)
    {
        $cartCookie = $this->getCookieVerifierAdapter()->findByVerifier($verifier);

        if($cartCookie) {
            $container = new Container('zfProducts');
            $container->cartId = $cartCookie->getCartId();
            if($this->getPersistentZendCartService()){
                $container->products = $this->getPersistentZendCartService()->restoreZendCartArray($cartCookie->getCartId());
            }
        }
    }

    public function setCartVerifierCookie(Event $e)
    {
        $cartId = $e->getParam('cart_id');
        // $cartId = $e->getCartId();

        $verifier = $this->createVerifier();
        $adapter = $this->getCookieVerifierAdapter();
        $adapter->persist($verifier, $cartId);

        $cookie = new SetCookie('zendcart_verifier', $verifier, time()+($this->getCookieExpiry()*60), $this->getCookieUrl(), $this->getCookieDomain(), $this->getCookieSecure());
        $this->getResponse()->getHeaders()->addHeader($cookie);
    }

    public function removeCartVerifierCookie(Event $e)
    {
        $cookie = new SetCookie('zendcart_verifier', null, time()-3600, $this->getCookieUrl());
        $this->getResponse()->getHeaders()->addHeader($cookie);
    }

    public function createVerifier($length=32, $characterSet='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
    {
    	//TODO: Should probably handle check for duplication... chances are 2x10^52 so unlikely.
        return Rand::getString($length, $characterSet);
    }

    public function setCookieVerifierAdapter(CartCookieAdapterInterface $adapter)
    {
        $this->cookieVerifierAdapter = $adapter;
        return $this;
    }

    public function getCookieVerifierAdapter()
    {
        return $this->cookieVerifierAdapter;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setCookieConfig($config)
    {
        $this->cookieConfig = $config;
        return $this;
    }

    public function getCookieConfig()
    {
        return $this->cookieConfig;
    }

    public function setCookieExpiry($expiry)
    {
        $this->cookieConfig['cart_cookie_expiry'] = $expiry;
        return $this;
    }

    public function getCookieExpiry()
    {
        return $this->cookieConfig['cart_cookie_expiry'];
    }

    public function setCookieDomain($domain)
    {
        $this->cookieConfig['cart_cookie_domain'] = $domain;
        return $this;
    }

    public function getCookieDomain()
    {
        return $this->cookieConfig['cart_cookie_domain'];
    }

    public function setCookieUrl($url)
    {
        $this->cookieConfig['cart_cookie_url'] = $url;
        return $this;
    }

    public function getCookieUrl()
    {
        return $this->cookieConfig['cart_cookie_url'];
    }

    public function setCookieSecure($httpsOnly)
    {
        $this->cookieConfig['cart_cookie_secure'] = $httpsOnly;
        return $this;
    }

    public function getCookieSecure()
    {
        return $this->cookieConfig['cart_cookie_secure'];
    }

    /**
     * Gets the value of persistentZendCartService.
     *
     * @return mixed
     */
    public function getPersistentZendCartService()
    {
        return $this->persistentZendCartService;
    }

    /**
     * Sets the value of persistentZendCartService.
     *
     * @param mixed $persistentZendCartService the persistent zend cart service
     *
     * @return self
     */
    public function setPersistentZendCartService($persistentZendCartService)
    {
        $this->persistentZendCartService = $persistentZendCartService;

        return $this;
    }
}
