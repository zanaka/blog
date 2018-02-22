<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

//固定パス
//$app->get('/hello', function ($request, $response, $args) {
//    echo 'Hello,world!';
//});


$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


$app->get('/hello/{name}', function ($request, $response, $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
});