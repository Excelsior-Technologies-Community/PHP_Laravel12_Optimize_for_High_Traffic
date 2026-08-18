<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$routes = app('router')->getRoutes();
foreach($routes as $r) {
    $uri = $r->uri();
    if (strpos($uri, 'discount') !== false) {
        echo $r->methods()[0] . ' ' . $uri . ' - ' . $r->getName() . PHP_EOL;
    }
}