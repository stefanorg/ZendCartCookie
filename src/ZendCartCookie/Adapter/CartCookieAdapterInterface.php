<?php

namespace ZendCartCookie\Adapter;

interface CartCookieAdapterInterface
{
	public function findByVerifier($verifier);

	public function persist($verifier, $cartId);
}