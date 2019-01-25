<?php
return [
    'adminEmail' => 'admin@example.com',
    'sitemap' => [
        'seller_items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Мои товары', 'url' => ['/lot/index'], 'items' => [
                ['label' => 'Список', 'url' => ['/lot/index']],
                ['label' => 'Добавить товар', 'url' => ['/lot/create']],
            ]],
        ],
        'customer_items' => [
            ['label' => 'Главная', 'url' => ['/site/index']],
            ['label' => 'Мои покупки', 'url' => ['/lot/purchase']],
        ]
    ]
];
