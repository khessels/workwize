<?php

$arrAllPermissions = [
    'super admin',
    'developer with full access',
    'order products',
    'view products',
    'manage products',
];
return [
    'permissions' => $arrAllPermissions,

    'roles' => [
        [
            'name'=> 'admin',
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
