<?php

namespace StoneApple;

use Silex\Application as SilexApplication;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SessionServiceProvider;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;

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
        $this->register(new SessionServiceProvider());
        $this->register(new UrlGeneratorServiceProvider());
        $this->register(new ValidatorServiceProvider());
        $this->register(new TranslationServiceProvider(), array(
            'translator.messages' => array(),
        ));
        $this->register(new FormServiceProvider());
        $this->register(new TwigServiceProvider(), array(
            'twig.path'       => __DIR__.'/view',
            'twig.form.templates' => array('Form/fields.html.twig')
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
        $this->match('/admin/', array($this, 'handleAdmin'))->bind('admin_home');
        $this->match('/admin/login', array($this, 'handleAdminLogin'))->bind('admin_login');
        $this->match('/admin/logout', array($this, 'handleAdminLogout'))->bind('admin_logout');
    }

    public function handleAdmin(Request $request)
    {
        if (null === $user = $this['session']->get('user')) {
            return $this->redirect($this['url_generator']->generate('admin_login'));
        }

        // display the form
        return $this['twig']->render('admin/index.html.twig', array(
            'title' => 'Stone Apple - Admin - Welcome',
            'user' => $user
        ));
    }

    public function handleAdminLogin(Request $request)
    {
        $form = $this['form.factory']->createBuilder('form')
            ->add('username','text', array('constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 3, 'max' => 50))
            )))
            ->add('password', 'password', array('constraints' => array(
                new Assert\NotBlank(),
                new Assert\Length(array('min' => 5, 'max' => 50))
            )))
            ->getForm();

        if ('POST' == $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $connection = $this['pomm']->getDatabase()->getConnection();
                $users = $connection->getMapFor('\StoneAppleDev\PublicSchema\User')
                    ->findWhere(sprintf("username = '%s' AND password = '%s'",
                        $data['username'], $data['password'] // TODO encrypt password!
                    ))
                ;

                if($users && $users->count() == 1) {
                    $this['session']->set('user', $users->current());
                    return $this->redirect($this['url_generator']->generate('admin_home'));
                } else {
                    // wrong!!!!
                    $error = "Your username or password is incorrect.";
                }

            } else {
                // please fill in the form like a good human
                $error = "Please correct the errors below.";
            }
        }

        $response = new Response();
        $response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'site_login'));
        $response->setStatusCode(401, 'Please sign in.');

        // display the form
        return $this['twig']->render('admin/login.html.twig', array(
            'title' => 'Stone Apple - Login',
            'form' => $form->createView(),
            'error' => isset($error)?$error:'',
            'flashes' => $this['session']->getFlashBag()->all()
        ));
    }

    public function handleAdminLogout()
    {
        $this['session']->remove('user');
        $this['session']->getFlashBag()->add('success', 'You are successfully logged out.');

        return $this->redirect($this['url_generator']->generate('admin_login'));
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
            'title' => 'Stone Apple',
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