<?php

return [
    'response' => [
        'formatters' => new \Yiisoft\Arrays\UnsetArrayValue(),
    ],
    'request' => [
       'cookieValidationKey' => new \Yiisoft\Arrays\UnsetArrayValue(),
    ],
    'cache' => function () {
        return new Yiisoft\Cache\Cache(new \Yiisoft\Cache\ArrayCache());
    }
];
