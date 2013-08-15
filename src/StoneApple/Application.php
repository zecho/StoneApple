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

        // converter for array_agg() posts
        $conn = $this['pomm']->getDatabase()->getConnection();
        $map = $conn->getMapFor('\StoneAppleDev\PublicSchema\Post');
        $this['pomm']->getDatabase()
            ->registerConverter('Post', new \Pomm\Converter\PgEntity($map), array('public.post'));

        $map = $conn->getMapFor('\StoneAppleDev\PublicSchema\Tag');
        $this['pomm']->getDatabase()
            ->registerConverter('Tag', new \Pomm\Converter\PgEntity($map), array('public.tag'));
    }

    private function registerRoutes()
    {
        $this->match('/', array($this, 'handlePostsList'))->bind('homepage');
        $this->match('/post/{slug}', array($this, 'handlePost'))->bind('post');
        $this->match('/tag/{slug}', array($this, 'handleTag'))->bind('tag');
    }

    public function handlePost($slug)
    {
        $connection = $this['pomm']->getDatabase()->getConnection();
        $posts = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')
            ->getOneWithTags($slug);

        if($posts->count() == 0) {
            $this->abort(404, sprintf("Post '%s' does not exist.", $slug));
        }

        $post = $posts->current();

        return $this['twig']->render('post.html.twig', array(
            'title' => sprintf('Stone Apple - %s', $post->getTitle()),
            'post' => $post
        ));
    }

    public function handlePostsList()
    {
        $connection = $this['pomm']->getDatabase()->getConnection();
        $posts = $connection->getMapFor('\StoneAppleDev\PublicSchema\Post')
            ->findAll('ORDER BY created_at DESC');

        return $this['twig']->render('posts.html.twig', array(
            'title' => 'Stone Apple - Pomm',
            'posts' => $posts
        ));
    }

    public function handleTag($slug)
    {
        $connection = $this['pomm']->getDatabase()->getConnection();

        $tags = $connection->getMapFor('\StoneAppleDev\PublicSchema\Tag')
            ->getOneWithPosts($slug);

        if($tags->count() == 0) {
            $this->abort(404, sprintf("Tag '%s' does not exist.", $slug));
        }

        $tag = $tags->current();

        return $this['twig']->render('tag.html.twig', array(
            'title' => sprintf('Stone Apple - Tagged "%s"', $tag->getLabel()),
            'tag' => $tag
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