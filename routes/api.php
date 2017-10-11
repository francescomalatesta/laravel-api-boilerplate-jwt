<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);
require base_path('routes/v1.php');
//add v2 routes file
//require base_path('routes/v2.php');
