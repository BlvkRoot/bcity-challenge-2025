<?php

declare (strict_types = 1);

spl_autoload_register(
    function ($class) {
        $path = __DIR__ . './src/' . lcfirst(str_replace('\\', '/', $class)) . '.php';

        if (file_exists($path)) {
            require $path;
        }
    });

use App\Modules\Entities\Client;

var_dump(new Client("Angelo", "ANG001"));
