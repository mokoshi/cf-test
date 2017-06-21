<?php

require('../vendor/autoload.php');

error_reporting(E_ERROR | E_PARSE);

$app = new Silex\Application();
$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// Our web handlers

$app->get('/image', function(\Symfony\Component\HttpFoundation\Request $requst) use($app) {
    ob_end_clean();

    $id = $requst->query->get('id') ?: 1;
    $stream = function () use ($id) {
        readfile('images/' . $id . '.jpg');
    };

    return $app->stream(
        $stream,
        200,
        [
            'Content-Type' => 'image/jpeg',
            'Cache-Control' => 'max-age=60 public',
        ]
    );
});

$app->run();
