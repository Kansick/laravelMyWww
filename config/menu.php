<?php
return [
    'navbar' => [
        [
            'type' => 'logo',
            'title' => 'Сайт сервис',
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
                ]
            ]
        ],
        [
            'type' => 'menu',
            'title' => 'Тир листы',
            'url' => '/tiers/',
            'active' => true,
            'childs' => [
                [
                    'type' => 'child',
                    'title' => 'Игры',
                    'url' => '/tiers/games/',
                    'active' => true
                ],
                [
                    'type' => 'child',
                    'title' => 'Мультимедиа',
                    'url' => '/tiers/media/',
                    'active' => true
                ],
                [
                    'type' => 'child',
                    'title' => 'Книги',
                    'url' => '/tiers/books/',
                    'active' => true
                ],
            ]
        ]
    ],
];