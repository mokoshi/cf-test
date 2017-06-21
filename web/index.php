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
    $id = $requst->query->get('id') ?: 1;
    $stream = function () use ($id) {
        readfile('images/' . $id . '.jpg');
    };

    ob_end_clean();

    return $app->stream($stream, 200, array('Content-Type' => 'image/jpeg'));
});

$app->run();
