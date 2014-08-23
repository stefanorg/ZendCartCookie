<?php

namespace ZendCartCookie\Adapter;

use Doctrine\Common\Persistence\ObjectManager;
use ZendCartCookie\Adapter\CartCookieAdapterInterface;

class DoctrineCartCookieAdapter implements CartCookieAdapterInterface {

    protected $objectManager;
    protected $entityClassName;
    protected $repo;

    function __construct(ObjectManager $om, $entityClassName){
        $this->objectManager = $om;
        $this->entityClassName = $entityClassName;
        $this->repo = $om->getRepository($entityClassName);
    }

    public function findByVerifier($verifier){
        return $this->repo->findOneBy(array('cartVerifier'=>$verifier));
    }

    public function persist($verifier, $cartId){
        $cartCookie = new $this->entityClassName();
        $cartCookie->setCartVerifier($verifier);
        $cartCookie->setCartId($cartId);
        $this->objectManager->persist($cartCookie);
        $this->objectManager->flush();
    }

}