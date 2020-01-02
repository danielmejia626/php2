<?php

ini_set('display_errors', 1);
ini_set('display_starup_error', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Aura\Router\RouterContainer;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'mysql-db',
    'database'  => 'cursos',
    'username'  => 'root',
    'password'  => 'securerootpassword',
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
    'controller' => 'App\Controllers\IndexController',
    'action' => 'indexAction'
]);
$map->get('addJob', '/cursophp/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);
$map->post('saveJob', '/cursophp/jobs/add', [
    'controller' => 'App\Controllers\JobsController',
    'action' => 'getAddJobAction'
]);

$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

function printElement( $jobs) {
    //if($jobs->visible == false) {
      //return;
    //}
    echo '<li class="work-position">';
    echo '<h5>' . $jobs->title . '</h5>';
    echo '<h5>' . $jobs->description . '</h5>';
    echo '<h5>' . $jobs->getDurationasString() . '</h5>';
    echo '<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nisi sapiente sed pariatur sint exercitationem eos expedita eveniet veniam ullam, quia neque facilis dicta voluptatibus. Eveniet doloremque ipsum itaque obcaecati nihil.</p>';
    echo '<strong>Achievements:</strong>';
    echo '<ul>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '<li>Lorem ipsum dolor sit amet, 80% consectetuer adipiscing elit.</li>';
    echo '</ul>';
    echo '</li>';
  }

//var_dump($matcher);
if (!$route) {
    echo 'No route';
} else {
    $handlerData = $route->handler;
    $controllerName = $handlerData['controller'];
    $actionName = $handlerData['action'];

    $controller = new $controllerName;
    $controller->$actionName($request);
}





