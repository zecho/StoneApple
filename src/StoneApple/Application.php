<?php

namespace StoneApple;

use Silex\Application as SilexApplication;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Pomm\Silex\PommServiceProvider;

class Application extends SilexApplication
{
    /**
     * @param string $env
     *
     * @return null
     */
    public function __construct($env='prod')
    {
        parent::__construct();

        // load environment configuration
        $app = $this;
        require __DIR__. "/../../resources/config/$env.php";

        $this->registerServiceProviders();
        $this->registerRoutes();
        $this->error(array($this, 'handleErrors'));
    }

    private function registerServiceProviders()
    {
        $this->register(new UrlGeneratorServiceProvider());
        $this->register(new TwigServiceProvider(), array(
            'twig.path'       => __DIR__.'/view',
        ));
        $this->register(new PommServiceProvider(), array(
                'pomm.class_path' => __DIR__.'/vendor/pomm',
                'pomm.databases' => array(
                    'default' => array(
                        'dsn' => $this['pomm.dns'],
                ))
        ));
    }

    private function registerRoutes()
    {
        $this->match('/', array($this, 'handleHomepage'))->bind('homepage');
        $this->match('/post/list', array($this, 'handlePostsList'))->bind('posts_list');
        $this->match('/post/{slug}', array($this, 'handlePost'))->bind('post');
    }

    public function handleHomepage()
    {
        return $this['twig']->render('index.html.twig', array(
            'html' => '<h1>Coucou</h1>',
            'title' => 'Stone Apple - Coucou'
        ));
    }

    public function handlePost($slug)
    {
        $connection = $this['pomm']->getDatabase()->getConnection();
        $post = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')->findByPK(array('slug' => $slug));

        return $this['twig']->render('post.html.twig', array(
            'title' => sprintf('Stone Apple - %s', $post->getTitle()),
            'post' => $post
        ));
    }

    public function handlePostsList()
    {
        $connection = $this['pomm']->getDatabase()->getConnection();
        $posts = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')->findAll();

        return $this['twig']->render('posts.html.twig', array(
            'title' => 'Stone Apple - Pomm',
            'posts' => $posts
        ));
    }

    public function handleErrors(\Exception $e, $code)
    {
        if ($this['debug']) {
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
    }

}