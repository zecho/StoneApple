<?php

require_once __DIR__.'/bootstrap.php';

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


// -----------------------------------------------
// Controllers

$app->match('/', function() use ($app) {
    return $app['twig']->render('index.html.twig', array(
        'html' => '<h1>Coucou</h1>',
        'title' => 'Stone Apple - Coucou'
    ));
})->bind('homepage');

$app->match('/pomm', function() use ($app) {
    $connection = $app['pomm']->getDatabase()->getConnection();
    $posts = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')->findAll();

    return $app['twig']->render('posts.html.twig', array(
        'html' => '<h1>Pomm</h1>',
        'title' => 'Stone Apple - Pomm',
        'posts' => $posts
    ));
})->bind('pomm');

$app->error(function (\Exception $e, $code) use ($app) {
    if ($app['debug']) {
        return;
    }
    $message  = "[$code] ";
    switch ($code) {
    case 404:
        $message .= 'Sorry, the requested page could not be found.';
        break;
    default:
        $message .= 'Sorry, something went terribly wrong.';
        $message .= "<pre>$e . $code . </pre>";
    }

    return new Response($message, $code);
});
