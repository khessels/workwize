<?php

$arrAllPermissions = [
    'Super Admin',
    'order products',
    'view products',
    'manage products',
];
return [
    'permissions' => $arrAllPermissions,

    'roles' => [
        [
            'name'=> 'developer',
            'permissions' => $arrAllPermissions
        ],
        [
            'name'=> 'customer',
            'permissions' => ['order products', 'view products']
        ],
        [
            'name'=> 'supplier',
            'permissions' => ['manage products', 'view products']
        ],
    ]
];
