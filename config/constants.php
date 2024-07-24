<?php

$arrAllPermissions = [
    'Super Admin',
    'order products',
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
            'permissions' => ['order products']
        ],
        [
            'name'=> 'supplier',
            'permissions' => ['manage products']
        ],
    ]
];
