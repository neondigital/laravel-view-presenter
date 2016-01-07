<?php

return [

    'namespace' => "App\\Http\\ViewPresenters\\",

    'auto_decorators' => [
        'App\\Entities\\User' => Neondigital\LaravelViewPresenter\Decorators\Doctrine::class,
    ],
];
