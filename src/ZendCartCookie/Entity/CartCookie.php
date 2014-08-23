<?php
namespace ZendCartCookie\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="_cart_cookie")
 */
class CartCookie {

    /**
     * @ORM\Id
     * @ORM\Column(name="cart_verifier", length=32)
     */
    protected $cartVerifier;

    /**
     * @ORM\Column(name="cart_id", type="string", nullable=false)
     */
    protected $cartId;


    /**
     * Gets the value of cartVerifier.
     *
     * @return mixed
     */
    public function getCartVerifier()
    {
        return $this->cartVerifier;
    }
    
    /**
     * Sets the value of cartVerifier.
     *
     * @param mixed $cartVerifier the cart verifier 
     *
     * @return self
     */
    public function setCartVerifier($cartVerifier)
    {
        $this->cartVerifier = $cartVerifier;

        return $this;
    }

    /**
     * Gets the value of cartId.
     *
     * @return mixed
     */
    public function getCartId()
    {
        return $this->cartId;
    }
    
    /**
     * Sets the value of cartId.
     *
     * @param mixed $cartId the cart id 
     *
     * @return self
     */
    public function setCartId($cartId)
    {
        $this->cartId = $cartId;

        return $this;
    }
}