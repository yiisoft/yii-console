<?php

return [
    'cache' => function () {
        return new Yiisoft\Cache\Cache(new \Yiisoft\Cache\ArrayCache());
    }
];
