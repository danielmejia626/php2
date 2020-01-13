<?php

require_once '../vendor/autoload.php';

session_start();

$dotenv = Dotenv\Dotenv::createMutable(__DIR__. '/..');
$dotenv->load();

if (getenv('DEBUG') === 'true') {
    ini_set('display_errors', 1);
    ini_set('display_starup_error', 1);
    error_reporting(E_ALL);
}

password_hash('sueprSecurepasswd', PASSWORD_DEFAULT);

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use WoohooLabs\Harmony\Harmony;
use Zend\HttpHandlerRunner\Emitter\SapiEmitter;
use Zend\Diactoros\Response;
use WoohooLabs\Harmony\Middleware\DispatcherMiddleware;
use WoohooLabs\Harmony\Middleware\HttpHandlerRunnerMiddleware;

$log = new Logger('app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../logs/app.log', Logger::WARNING));

$container = new DI\Container();
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setAsGlobal();
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
$map->get('index', '/cursophp/', [
    'App\Controllers\IndexController',
    'indexAction',
    'auth' => true
]);
$map->get('indexJob', '/cursophp/jobs', [
    'App\Controllers\JobsController',
    'indexAction',
    'auth' => true
]);
$map->get('addJob', '/cursophp/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction',
    'auth' => true
]);
$map->get('contactoForm', '/cursophp/contact', [
    'App\Controllers\ContactController',
    'index',
]);
$map->get('deleteJob', '/cursophp/jobs/delete', [
    'App\Controllers\JobsController',
    'deleteAction',
    'auth' => true
]);
$map->get('email', '/cursophp/email', [
    'App\Controllers\UsersController',
    'getAddUser'
]);
$map->get('loginForm', '/cursophp/login', [
    'App\Controllers\AuthController',
    'getLogin'
]);
$map->get('logout', '/cursophp/logout', [
    'App\Controllers\AuthController',
    'getLogout'
]);
$map->get('admin', '/cursophp/admin', [
    'App\Controllers\AdminController',
    'getIndex',
    'auth' => true
]);
$map->post('auth', '/cursophp/auth', [
    'App\Controllers\AuthController',
    'postLogin'
]);
$map->post('saveUser', '/cursophp/save', [
    'App\Controllers\UsersController',
   'postSaveUser'
]);
$map->post('saveJob', '/cursophp/jobs/add', [
    'App\Controllers\JobsController',
    'getAddJobAction'
]);
$map->post('contactSend', '/cursophp/contact/send', [
    'App\Controllers\ContactController',
    'send'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

//var_dump($matcher);
if (!$route) {
    echo 'No route';
} else {
    try {
        $harmony = new Harmony($request, new Response());
        $harmony
            ->addMiddleware(new HttpHandlerRunnerMiddleware(new SapiEmitter()));
        if (getenv('DEBUG') === 'true'){
            $harmony->addMiddleware(new \Franzl\Middleware\Whoops\WhoopsMiddleware());
        }
            $harmony->addMiddleware(new \App\Middlewares\AuthenticationMiddleware())
            ->addMiddleware(new Middlewares\AuraRouter($routerContainer))
            ->addMiddleware(new DispatcherMiddleware($container, 'request-handler'));
        $harmony();
    } catch(Exception $e) {
        $log->warning($e->getMessage());
        $emmitter = new SapiEmitter();
        $emmitter->emit(new Response\EmptyResponse(400));
    } catch (Error $e) {
        $emmitter = new SapiEmitter();
        $emmitter->emit(new Response\EmptyResponse(500));
    }

}
