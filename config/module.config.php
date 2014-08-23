<?php
return [
    'zendcartcookie' => [
        'cart_cookie_expiry' => 10080,   // Cookie expiry time in mins
        'cart_cookie_domain' => null,    // Set to null this defaults to the current domain
        'cart_cookie_secure' => false,   // Should the cookie be secure only?
        'cart_cookie_url'    => '/',     // Location to set in the cookie - default to root
        'entity_class_name'  => 'ZendCartCookie\Entity\CartCookie'
    ],
    'doctrine' => [
        'driver' => [
            'zendcartcookie_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/ZendCartCookie/Entity',
            ],

            'orm_default' => [
                'drivers' => [
                    'ZendCartCookie\Entity' => 'zendcartcookie_entity',
                ],
            ],
        ],
    ]
];
