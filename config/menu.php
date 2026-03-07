<?php
return [
    'navbar' => [
        [
            'type' => 'logo',
            'title' => 'My services',
            'url' => '/',
            'active' => true,
        ],
        [
            'type' => 'menu',
            'title' => 'Сервисы',
            'url' => '/services/',
            'active' => true,
            'childs' => [
                [
                    'type' => 'child',
                    'title' => 'Расчёт аренды',
                    'url' => '/services/rent/',
                    'active' => true
                ],
                [
                    'type' => 'child',
                    'title' => 'Пароли',
                    'url' => '/services/passwords/',
                    'active' => true
                ]
            ],
        ],
    ],
];