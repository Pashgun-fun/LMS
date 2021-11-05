<?php
return [
    'checkFields' => [
        'login',
        'email',
        'data',
        'pass',
        'confirm'
    ],
    'checkEditFields' => [
        'login',
        'email',
        'pass',
        'confirm',
        'index',
        'descr'
    ],
    'checkArticlesAndNewsFields' => [
        'title',
        'text'
    ],
    'checkSession' => [
        'ROLE',
        'NAME',
        'id'
    ],
    'checkDatabaseTables' => [
        'articles',
        'news',
        'users',
    ]
];