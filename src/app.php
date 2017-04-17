<?php

/**
 * Created by PhpStorm.
 * User: neil
 * Date: 26/11/2016
 * Time: 00:00.
 */
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\FormServiceProvider;
use VinylStore\ServiceProviders\VinylRepositoryServiceProvider;
use VinylStore\ServiceProviders\ImageRepositoryServiceProvider;
use VinylStore\ServiceProviders\PricingRepositoryServiceProvider;
use VinylStore\ServiceProviders\UploadServiceProvider;
use VinylStore\ServiceProviders\MessageServiceProvider;
use VinylStore\UserProvider;

require_once __DIR__.'/../vendor/autoload.php';
$app = new Application();
$config = parse_ini_file(__DIR__.'/../config/config.ini', true);
$app->register(new TwigServiceProvider(array(
    'twig.options' => array(
        'debug' => true,
    ), )));
$app->extend('twig', function ($twig, Application $app) {
    $twig->addExtension(new Twig_Extension_Debug());

    return $twig;
});
$app->register(new AssetServiceProvider());
$app->register(new HttpFragmentServiceProvider());
$app->register(new ServiceControllerServiceProvider());
$app->register(new SessionServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\VarDumperServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => $config['database'],
));
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'admin' => array(
            'pattern' => '^/admin',
            'form' => array('login_path' => '/login', 'check_path' => '/admin/login_check'),
            'logout' => array('logout_path' => '/admin/logout', 'invalidate_session' => true),
            'users' => function () use ($app) {
                return new UserProvider($app['db']);
            },
            ),
        ),
    'security.encoder.bcrypt.cost' => $config['login']['bcrypt'],
    )
);
$app->register(new FormServiceProvider());
// register custom forms here.
// create a new release form
$app->extend('form.types', function ($types) {
    $types[] = new VinylStore\Forms\CreateNewReleaseType();

    return $types;
});
// upload image form
$app->extend('form.types', function ($types) use ($app) {
    $types[] = new VinylStore\Forms\ImageUploadType();

    return $types;
});
// pricing data form
$app->extend('form.types', function ($types) {
    $types[] = new VinylStore\Forms\AddPricingDataType();

    return $types;
});
// Refine slide-out form
$app->extend('form.types', function ($types) use ($app) {
    $types[] = new VinylStore\Forms\RefineType();

    return $types;
});
$app->register(new VinylRepositoryServiceProvider());
$app->register(new ImageRepositoryServiceProvider());
$app->register(new PricingRepositoryServiceProvider());
$app->register(new UploadServiceProvider());
$app->register(new MessageServiceProvider());
// exception handling
//$app->error(function (\Exception $e, Request $request, $code) use ($app) {
//    switch($code) {
//        case 404:
//            $page = 'frontend/404.html.twig';
//            break;
//        default:
//            $page = 'frontend/error.html.twig';
//    }
//    return new Response($app['twig']->render($page));
//});

return $app;
